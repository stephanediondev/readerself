<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subscriptions extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function index() {
		if(!$this->axipi_session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$data = array();

		$data['errors'] = $this->db->query('SELECT COUNT(DISTINCT(fed.fed_id)) AS count FROM '.$this->db->dbprefix('feeds').' AS fed WHERE fed.fed_lasterror IS NOT NULL AND fed.fed_id IN ( SELECT sub.fed_id FROM '.$this->db->dbprefix('subscriptions').' AS sub WHERE sub.fed_id = fed.fed_id AND sub.mbr_id = ? )', array($this->member->mbr_id))->row()->count;

		$data['last_added'] = $this->db->query('SELECT fed.*, sub.sub_id, sub.sub_title, sub.sub_direction, fed.fed_direction FROM '.$this->db->dbprefix('subscriptions').' AS sub LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = sub.fed_id WHERE fed.fed_id IS NOT NULL AND sub.mbr_id = ? GROUP BY sub.sub_id ORDER BY sub.sub_id DESC LIMIT 0,5', array($this->member->mbr_id))->result();

		if($this->config->item('folders')) {
			$query = $this->db->query('SELECT flr.* FROM '.$this->db->dbprefix('folders').' AS flr WHERE flr.mbr_id = ? GROUP BY flr.flr_id ORDER BY flr.flr_title ASC', array($this->member->mbr_id));
			$data['folders'] = array();
			$data['folders'][''] = '--';
			$data['folders'][-1] = $this->lang->line('no_folder');
			if($query->num_rows() > 0) {
				foreach($query->result() as $flr) {
					$data['folders'][$flr->flr_id] = $flr->flr_title;
				}
			}
		}

		$filters = array();
		$filters[$this->router->class.'_subscriptions_fed_title'] = array('fed.fed_title', 'like');
		$filters[$this->router->class.'_subscriptions_flr_id'] = array('sub.flr_id', 'equal');
		$filters[$this->router->class.'_subscriptions_sub_priority'] = array('sub.sub_priority', 'equal');
		if($data['errors'] > 0) {
			$filters[$this->router->class.'_subscriptions_fed_lasterror'] = array('fed.fed_lasterror', 'notnull');
		}
		$flt = $this->readerself_library->build_filters($filters);
		$flt[] = 'sub.mbr_id = \''.$this->member->mbr_id.'\'';
		$flt[] = 'fed.fed_id IS NOT NULL';
		$results = $this->readerself_model->get_subscriptions_total($flt);
		$build_pagination = $this->readerself_library->build_pagination($results->count, 50, $this->router->class.'_subscriptions');
		$data['pagination'] = $build_pagination['output'];
		$data['position'] = $build_pagination['position'];
		$data['subscriptions'] = $this->readerself_model->get_subscriptions_rows($flt, $build_pagination['limit'], $build_pagination['start'], 'fed.fed_title ASC');

		$content = $this->load->view('subscriptions_index', $data, TRUE);
		$this->readerself_library->set_content($content);
	}

	public function create() {
		if(!$this->axipi_session->userdata('mbr_id')) {
			redirect(base_url().'?u='.$this->input->get('u'));
		}

		$data = array();

		$content = array();

		$this->load->library(array('form_validation', 'analyzer_library'));

		if($this->config->item('folders')) {
			$query = $this->db->query('SELECT flr.* FROM '.$this->db->dbprefix('folders').' AS flr WHERE flr.mbr_id = ? GROUP BY flr.flr_id ORDER BY flr.flr_title ASC', array($this->member->mbr_id));
			$data['folders'] = array();
			$data['folders'][0] = $this->lang->line('no_folder');
			if($query->num_rows() > 0) {
				foreach($query->result() as $flr) {
					$data['folders'][$flr->flr_id] = $flr->flr_title;
				}
			}
		}

		$this->form_validation->set_rules('url', 'lang:url_feed', 'required');
		if($this->config->item('folders')) {
			$this->form_validation->set_rules('folder', 'lang:folder', 'required');
		}
		$this->form_validation->set_rules('priority', 'lang:priority', 'numeric');
		//$this->form_validation->set_rules('direction', 'lang:direction', '');

		$data['error'] = false;

		$data['feeds'] = array();
		if($this->input->post('url') && !$this->input->post('analyze_done')) {
			$this->analyzer_library->start($this->input->post('url'));
			$metas = $this->analyzer_library->metas;
			if(count($metas) > 0) {
				$data['feeds'][''] = '-';
				foreach($metas as $meta) {
					$add = true;
					$headers = get_headers($meta['href'], 1);

					if(isset($headers['Location']) == 1) {
						$meta['href'] = $headers['Location'];
						$headers = get_headers($meta['href'], 1);
						if(isset($headers['Location']) == 1) {
							$add = false;
						}
					}
					if($add) {
						if($meta['title'] == '') {
							$data['feeds'][$meta['href']] = $meta['href'];
						} else {
							$this->analyzer_library->encoding($meta['title']);
							$data['feeds'][$meta['href']] = $meta['title'];
						}
					}
				}
			}
		}

		if($this->form_validation->run() == FALSE || count($data['feeds']) > 0) {
			$content = $this->load->view('subscriptions_create', $data, TRUE);
		} else {
			if($this->config->item('folders')) {
				$folder = false;
				if($this->input->post('folder')) {
					$query = $this->db->query('SELECT flr.* FROM '.$this->db->dbprefix('folders').' AS flr WHERE flr.mbr_id = ? AND flr.flr_id = ? GROUP BY flr.flr_id', array($this->member->mbr_id, $this->input->post('folder')));
					if($query->num_rows() > 0) {
						$folder = $this->input->post('folder');
					}
				}
			}

			$query = $this->db->query('SELECT fed.*, sub.sub_id FROM '.$this->db->dbprefix('feeds').' AS fed LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = fed.fed_id AND sub.mbr_id = ? WHERE fed.fed_link = ? GROUP BY fed.fed_id', array($this->member->mbr_id, $this->input->post('url')));
			if($query->num_rows() == 0) {
				$parse_url = parse_url($this->input->post('url'));

				if(isset($parse_url['host']) == 1 && $parse_url['host'] == 'www.facebook.com' && $this->config->item('facebook/enabled')) {
					include_once('thirdparty/facebook/autoload.php');

					$fb = new Facebook\Facebook(array(
						'app_id' => $this->config->item('facebook/id'),
						'app_secret' => $this->config->item('facebook/secret'),
					));
					$fbApp = $fb->getApp();
					$accessToken = $fbApp->getAccessToken();

					try {
						$parts = explode('/', $parse_url['path']);
						$total_parts = count($parts);
						$last_part = $parts[$total_parts - 1 ];
						$request = new Facebook\FacebookRequest($fbApp, $accessToken, 'GET', $last_part.'?fields=link,name,about');
						$response = $fb->getClient()->sendRequest($request);
						$result = $response->getDecodedBody();

						$this->db->set('fed_title', $result['name']);
						$this->db->set('fed_url', $result['link']);
						$this->db->set('fed_description', $result['about']);
						$this->db->set('fed_link', $result['link']);
						if(isset($parse_url['host']) == 1) {
							$this->db->set('fed_host', $parse_url['host']);
						}
						$this->db->set('fed_lastcrawl', date('Y-m-d H:i:s'));
						$this->db->set('fed_datecreated', date('Y-m-d H:i:s'));
						$this->db->insert('feeds');
						$fed_id = $this->db->insert_id();

						$this->db->set('mbr_id', $this->member->mbr_id);
						$this->db->set('fed_id', $fed_id);
						if($this->config->item('folders')) {
							if($folder) {
								$this->db->set('flr_id', $folder);
							}
						}
						$this->db->set('sub_priority', $this->input->post('priority'));
						$this->db->set('sub_direction', $this->input->post('direction'));
						$this->db->set('sub_datecreated', date('Y-m-d H:i:s'));
						$this->db->insert('subscriptions');
						$sub_id = $this->db->insert_id();

						$request = new Facebook\FacebookRequest($fbApp, $accessToken, 'GET', $last_part.'?fields=feed{created_time,id,message,story,full_picture,place,type,status_type,link}');
						$response = $fb->getClient()->sendRequest($request);
						$posts = $response->getDecodedBody();
						$this->readerself_library->crawl_items_facebook($fed_id, $posts['feed']['data']);

						redirect(base_url().'subscriptions/read/'.$sub_id);

					} catch(Facebook\Exceptions\FacebookResponseException $e) {
						$data['error'] = 'Graph returned an error: ' . $e->getMessage();

					} catch(Facebook\Exceptions\FacebookSDKException $e) {
						$data['error'] = 'Facebook SDK returned an error: ' . $e->getMessage();
					}

				} else {
					include_once('thirdparty/simplepie/autoloader.php');
					include_once('thirdparty/simplepie/idn/idna_convert.class.php');

					$sp_feed = new SimplePie();
					$sp_feed->set_feed_url(convert_to_ascii($this->input->post('url')));
					$sp_feed->enable_cache(false);
					$sp_feed->set_timeout(60);
					$sp_feed->force_feed(true);
					$sp_feed->init();
					$sp_feed->handle_content_type();

					if($sp_feed->error()) {
						$data['error'] = $sp_feed->error();

					} else {
						$parse_url = parse_url($sp_feed->get_link());

						$this->db->set('fed_title', $sp_feed->get_title());
						$this->db->set('fed_url', $sp_feed->get_link());
						$this->db->set('fed_description', $sp_feed->get_description());
						$this->db->set('fed_link', $sp_feed->subscribe_url());
						if(isset($parse_url['host']) == 1) {
							$this->db->set('fed_host', $parse_url['host']);
						}
						$this->db->set('fed_lastcrawl', date('Y-m-d H:i:s'));
						$this->db->set('fed_datecreated', date('Y-m-d H:i:s'));
						$this->db->insert('feeds');
						$fed_id = $this->db->insert_id();

						$this->db->set('mbr_id', $this->member->mbr_id);
						$this->db->set('fed_id', $fed_id);
						if($this->config->item('folders')) {
							if($folder) {
								$this->db->set('flr_id', $folder);
							}
						}
						$this->db->set('sub_priority', $this->input->post('priority'));
						$this->db->set('sub_direction', $this->input->post('direction'));
						$this->db->set('sub_datecreated', date('Y-m-d H:i:s'));
						$this->db->insert('subscriptions');
						$sub_id = $this->db->insert_id();

						$data['sub_id'] = $sub_id;
						$data['fed_title'] = $sp_feed->get_title();

						$this->readerself_library->crawl_items($fed_id, $sp_feed->get_items());
					}
					$sp_feed->__destruct();
					unset($sp_feed);
				}
			} else {
				$fed = $query->row();
				if(!$fed->sub_id) {
					$this->db->set('mbr_id', $this->member->mbr_id);
					$this->db->set('fed_id', $fed->fed_id);
					if($this->config->item('folders')) {
						if($folder) {
							$this->db->set('flr_id', $folder);
						}
					}
					$this->db->set('sub_priority', $this->input->post('priority'));
					$this->db->set('sub_direction', $this->input->post('direction'));
					$this->db->set('sub_datecreated', date('Y-m-d H:i:s'));
					$this->db->insert('subscriptions');
					$sub_id = $this->db->insert_id();
				} else {
					$sub_id = $fed->sub_id;
				}
				$data['sub_id'] = $sub_id;
				$data['fed_title'] = $fed->fed_title;
			}
			if($data['error']) {
				$content = $this->load->view('subscriptions_create', $data, TRUE);
			} else {
				redirect(base_url().'subscriptions/read/'.$sub_id);
			}
		}
		$this->readerself_library->set_content($content);
	}

	public function read($sub_id) {
		if(!$this->axipi_session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$data = array();
		$data['sub'] = $this->readerself_model->get_subscription_row($sub_id);
		if($data['sub']) {

			if($this->db->dbdriver == 'mysqli') {
				$substring = 'SUBSTRING';
			} else {
				$substring = 'SUBSTR';
			}

			$data['last_added'] = $this->db->query('SELECT fed.*, sub.sub_id, sub.sub_title, sub.sub_direction, fed.fed_direction FROM '.$this->db->dbprefix('subscriptions').' AS sub LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = sub.fed_id WHERE fed.fed_id IS NOT NULL AND sub.mbr_id = ? GROUP BY sub.sub_id ORDER BY sub.sub_id DESC LIMIT 0,5', array($this->member->mbr_id))->result();

			$data['tables'] = '';

			$date_ref = date('Y-m-d H:i:s', time() - 3600 * 24 * 30);

			if($this->config->item('tags')) {
				$legend = array();
				$values = array();
				$query = $this->db->query('SELECT cat.cat_title AS ref, COUNT(DISTINCT(hst.itm_id)) AS nb FROM '.$this->db->dbprefix('history').' AS hst LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.itm_id = hst.itm_id LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = itm.fed_id LEFT JOIN '.$this->db->dbprefix('categories').' AS cat ON cat.itm_id = itm.itm_id WHERE cat.cat_id IS NOT NULL AND hst.hst_real = ? AND hst.hst_datecreated >= ? AND hst.mbr_id = ? AND sub.sub_id = ? GROUP BY ref ORDER BY nb DESC LIMIT 0,30', array(1, $date_ref, $this->member->mbr_id, $sub_id));
				if($query->num_rows() > 0) {
					foreach($query->result() as $row) {
						$legend[] = $row->ref;
						$values[] = $row->nb;
					}
				}
				$data['tables'] .= build_table_repartition($this->lang->line('items_read_by_tag').'*', $values, $legend);
			}

			$this->readerself_library->clean_authors('feed', $data['sub']->fed_id);
			$legend = array();
			$values = array();
			$query = $this->db->query('SELECT auh.auh_title AS ref, COUNT(DISTINCT(hst.itm_id)) AS nb FROM '.$this->db->dbprefix('history').' AS hst LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.itm_id = hst.itm_id LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = itm.fed_id LEFT JOIN '.$this->db->dbprefix('authors').' AS auh ON auh.auh_id = itm.auh_id WHERE itm.auh_id IS NOT NULL AND hst.hst_real = ? AND hst.hst_datecreated >= ? AND hst.mbr_id = ? AND sub.sub_id = ? GROUP BY ref ORDER BY nb DESC LIMIT 0,30', array(1, $date_ref, $this->member->mbr_id, $sub_id));
			if($query->num_rows() > 0) {
				foreach($query->result() as $row) {
					$legend[] = $row->ref;
					$values[] = $row->nb;
				}
			}
			$data['tables'] .= build_table_repartition($this->lang->line('items_read_by_author').'*', $values, $legend);

			$legend = array();
			$values = array();
			$query = $this->db->query('SELECT '.$substring.'(hst.hst_datecreated, 1, 10) AS ref, COUNT(DISTINCT(hst.itm_id)) AS nb FROM '.$this->db->dbprefix('history').' AS hst LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.itm_id = hst.itm_id LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = itm.fed_id WHERE hst.hst_real = ? AND hst.mbr_id = ? AND sub.sub_id = ? GROUP BY ref ORDER BY ref DESC LIMIT 0,30', array(1, $this->member->mbr_id, $sub_id));
			if($query->num_rows() > 0) {
				foreach($query->result() as $row) {
					$legend[] = $this->readerself_library->timezone_datetime($row->ref, 'F j, Y');
					$values[] = $row->nb;
				}
			}
			$data['tables'] .= build_table_progression($this->lang->line('items_read_by_day'), $values, $legend);

			$legend = array();
			$values = array();
			$query = $this->db->query('SELECT '.$substring.'(hst.hst_datecreated, 1, 7) AS ref, COUNT(DISTINCT(hst.itm_id)) AS nb FROM '.$this->db->dbprefix('history').' AS hst LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.itm_id = hst.itm_id LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = itm.fed_id WHERE hst.hst_real = ? AND hst.mbr_id = ? AND sub.sub_id = ? GROUP BY ref ORDER BY ref DESC LIMIT 0,12', array(1, $this->member->mbr_id, $sub_id));
			if($query->num_rows() > 0) {
				foreach($query->result() as $row) {
					$legend[] = $this->readerself_library->timezone_datetime($row->ref, 'F, Y');
					$values[] = $row->nb;
				}
			}
			$data['tables'] .= build_table_progression($this->lang->line('items_read_by_month'), $values, $legend);

			if($this->db->dbdriver == 'mysqli') {
				$days = array(7=>'Sunday', 1=>'Monday', 2=>'Tuesday', 3=>'Wednesday', 4=>'Thursday', 5=>'Friday', 6=>'Saturday');
				$legend = array();
				$values = array();
				$query = $this->db->query('SELECT IF(DATE_FORMAT(DATE_ADD(hst.hst_datecreated, INTERVAL ? HOUR), \'%w\') = 0, 7, DATE_FORMAT(DATE_ADD(hst.hst_datecreated, INTERVAL ? HOUR), \'%w\')) AS ref, COUNT(DISTINCT(hst.itm_id)) AS nb FROM '.$this->db->dbprefix('history').' AS hst LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.itm_id = hst.itm_id LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = itm.fed_id WHERE hst.hst_real = ? AND hst.hst_datecreated >= ? AND hst.mbr_id = ? AND sub.sub_id = ? GROUP BY ref ORDER BY ref ASC', array($this->axipi_session->userdata('timezone'), $this->axipi_session->userdata('timezone'), 1, $date_ref, $this->member->mbr_id, $sub_id));
				if($query->num_rows() > 0) {
					foreach($query->result() as $row) {
						$temp[$row->ref] = $row->nb;
					}
				}
				foreach($days as $i => $v) {
						$legend[] = $v;
					if(isset($temp[$i]) == 1) {
						$values[] = $temp[$i];
					} else {
						$values[] = 0;
					}
				}
				$data['tables'] .= build_table_repartition($this->lang->line('items_read_by_day_week').'*', $values, $legend);
			}

			$content = $this->load->view('subscriptions_read', $data, TRUE);
			$this->readerself_library->set_content($content);
		} else {
			$this->index();
		}
	}

	public function update($sub_id) {
		if(!$this->axipi_session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$this->load->library('form_validation');
		$data = array();
		$data['sub'] = $this->readerself_model->get_subscription_row($sub_id);
		if($data['sub']) {
			$query = $this->db->query('SELECT flr.* FROM '.$this->db->dbprefix('folders').' AS flr WHERE flr.mbr_id = ? GROUP BY flr.flr_id ORDER BY flr.flr_title ASC', array($this->member->mbr_id));
			$data['folders'] = array();
			$data['folders'][0] = $this->lang->line('no_folder');
			if($query->num_rows() > 0) {
				foreach($query->result() as $flr) {
					$data['folders'][$flr->flr_id] = $flr->flr_title;
				}
			}

			if($this->member->mbr_administrator == 1) {
				$this->form_validation->set_rules('fed_link', 'lang:url', 'required|max_length[255]');
			}

			$this->form_validation->set_rules('sub_title', 'lang:sub_title', 'max_length[255]');
			$this->form_validation->set_rules('folder', 'lang:folder', 'required');
			$this->form_validation->set_rules('priority', 'lang:priority', 'numeric');
			//$this->form_validation->set_rules('direction', 'lang:direction', '');
			if($this->form_validation->run() == FALSE) {
				$content = $this->load->view('subscriptions_update', $data, TRUE);
				$this->readerself_library->set_content($content);
			} else {
				if($this->member->mbr_administrator == 1) {
					$this->db->set('fed_link', $this->input->post('fed_link'));
					$this->db->where('fed_id', $data['sub']->fed_id);
					$this->db->update('feeds');
				}

				$this->db->set('sub_title', $this->input->post('sub_title'));
				if($this->input->post('folder') == 0) {
					$this->db->set('flr_id', '');
				} else {
					$query = $this->db->query('SELECT flr.* FROM '.$this->db->dbprefix('folders').' AS flr WHERE flr.mbr_id = ? AND flr.flr_id = ? GROUP BY flr.flr_id', array($this->member->mbr_id, $this->input->post('folder')));
					if($query->num_rows() > 0) {
						$this->db->set('flr_id', $this->input->post('folder'));
					}
				}
				$this->db->set('sub_priority', $this->input->post('priority'));
				$this->db->set('sub_direction', $this->input->post('direction'));
				$this->db->where('sub_id', $sub_id);
				$this->db->update('subscriptions');

				redirect(base_url().'subscriptions/read/'.$sub_id);
			}
		} else {
			$this->index();
		}
	}

	public function delete($sub_id) {
		if(!$this->axipi_session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$this->load->library('form_validation');
		$data = array();
		$data['sub'] = $this->readerself_model->get_subscription_row($sub_id);
		if($data['sub']) {
			$this->form_validation->set_rules('confirm', 'lang:confirm', 'required');
			if($this->form_validation->run() == FALSE) {
				$content = $this->load->view('subscriptions_delete', $data, TRUE);
				$this->readerself_library->set_content($content);
			} else {
				$this->db->where('sub_id', $sub_id);
				$this->db->delete('subscriptions');

				redirect(base_url().'subscriptions');
			}
		} else {
			$this->index();
		}
	}

	public function priority($sub_id) {
		if(!$this->axipi_session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$content = array();

		if($this->input->is_ajax_request()) {
			$this->readerself_library->set_template('_json');
			$this->readerself_library->set_content_type('application/json');

			$sub = $this->readerself_model->get_subscription_row($sub_id);
			if($sub) {
				if($sub->sub_priority == 1) {
					$this->db->set('sub_priority', 0);
					$content['status'] = 'not_priority';
				} else {
					$this->db->set('sub_priority', 1);
					$content['status'] = 'priority';
				}
				$content['sub_id'] = $sub_id;
				$this->db->where('sub_id', $sub_id);
				$this->db->where('mbr_id', $this->member->mbr_id);
				$this->db->update('subscriptions');
			}
		} else {
			$this->output->set_status_header(403);
		}
		$this->readerself_library->set_content($content);
	}

	public function export() {
		if(!$this->axipi_session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$this->readerself_library->set_template('_opml');
		$this->readerself_library->set_content_type('application/xml');

		$subscriptions = array();
		$query = $this->db->query('SELECT fed.*, sub.sub_id, sub.flr_id, flr.flr_title FROM '.$this->db->dbprefix('subscriptions').' AS sub LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = sub.fed_id LEFT JOIN '.$this->db->dbprefix('folders').' AS flr ON flr.flr_id = sub.flr_id WHERE sub.mbr_id = ? AND fed.fed_id IS NOT NULL GROUP BY sub.sub_id ORDER BY flr.flr_title ASC, fed.fed_title ASC', array($this->member->mbr_id));
		if($query->num_rows() > 0) {
			foreach($query->result() as $sub) {
				$subscriptions[$sub->flr_title][] = $sub;
			}
		}

		$data = array();
		$data['subscriptions'] = $subscriptions;

		$content = $this->load->view('subscriptions_export', $data, TRUE);
		$this->readerself_library->set_content($content);

		header('Pragma: Public');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Content-Type: application/xml');
		header('Content-Disposition: attachment; filename="subscriptions-'.date('Y-m-d').'.opml";');
		header('Content-Transfer-Encoding: binary'); 
		session_write_close();
	}

	public function import() {
		if(!$this->axipi_session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$this->load->library('form_validation');

		$this->form_validation->set_rules('hidden', 'lang:hidden');

		if($this->form_validation->run() == FALSE) {
			$data = array();
			$content = $this->load->view('subscriptions_import', $data, TRUE);
		} else {
			$content = '';
			if(isset($_FILES['file']) == 1 && $_FILES['file']['error'] == 0) {
				$obj = simplexml_load_file($_FILES['file']['tmp_name']);
				if($obj) {
					$this->folders = array();
					$this->feeds = array();
					$this->import_opml($obj->body);

					$content .= '<div class="mdl-tooltip" for="tip_back">'.$this->lang->line('back').'</div>
<main class="mdl-layout__content mdl-color--'.$this->config->item('material-design/colors/background/layout').'">
	<div class="mdl-grid">
		<div class="mdl-card mdl-color--'.$this->config->item('material-design/colors/background/card').' mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title mdl-color-text--white mdl-color--'.$this->config->item('material-design/colors/background/card-title').'">
				<h1 class="mdl-card__title-text"><i class="material-icons md-18">file_download</i>'.$this->lang->line('import').'</h1>
			</div>
			<div class="mdl-card__actions mdl-card--border">
				<a id="tip_back" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="'.base_url().'subscriptions"><i class="material-icons md-18">arrow_back</i></a>
			</div>
		</div>';

					if(count($this->folders) > 0) {
						$content_folders = '<div class="mdl-card mdl-color--'.$this->config->item('material-design/colors/background/card').' mdl-cell mdl-cell--12-col">
						<div class="mdl-card__title mdl-color-text--white mdl-color--'.$this->config->item('material-design/colors/background/card-title').'">
						<h1 class="mdl-card__title-text"><i class="material-icons md-18">folder</i>'.$this->lang->line('folders').' ('.count($this->folders).')</h1></div></div>';

						$folders = array();
						foreach($this->folders as $value) {
							$query = $this->db->query('SELECT flr.* FROM '.$this->db->dbprefix('folders').' AS flr WHERE flr.flr_title = ? AND flr.mbr_id = ? GROUP BY flr.flr_id', array($value, $this->member->mbr_id));
							if($query->num_rows() == 0) {
								$this->db->set('mbr_id', $this->member->mbr_id);
								$this->db->set('flr_title', $value);
								$this->db->set('flr_datecreated', date('Y-m-d H:i:s'));
								$this->db->insert('folders');
								$flr_id = $this->db->insert_id();
								$folders[$value] = $flr_id;
								$icon = 'plus';
							} else {
								$flr = $query->row();
								$folders[$value] = $flr->flr_id;
								$icon = 'repeat';
							}
							$content_folders .= '<div class="mdl-card mdl-color--'.$this->config->item('material-design/colors/background/card').' mdl-cell mdl-cell--4-col">
								<div class="mdl-card__title">
									<h1 class="mdl-card__title-text"><a class="mdl-color-text--'.$this->config->item('material-design/colors/text/link').'" href="'.base_url().'folders/read/'.$folders[$value].'">'.$value.'</a></h1>
								</div>
								<div class="mdl-card__actions mdl-card--border">
									<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="'.base_url().'folders/update/'.$folders[$value].'"><i class="material-icons md-18">mode_edit</i></a>
									<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="'.base_url().'folders/delete/'.$folders[$value].'"><i class="material-icons md-18">delete</i></a>
								</div>
							</div>';
						}
						if($this->config->item('folders')) {
							$content .= $content_folders;
						}
					}

					if(count($this->feeds) > 0) {
						$content .= '<div class="mdl-card mdl-color--'.$this->config->item('material-design/colors/background/card').' mdl-cell mdl-cell--12-col">
						<div class="mdl-card__title mdl-color-text--white mdl-color--'.$this->config->item('material-design/colors/background/card-title').'">
						<h1 class="mdl-card__title-text"><i class="material-icons md-18">bookmark</i>'.$this->lang->line('subscriptions').' ('.count($this->feeds).')</h1></div></div>';
						foreach($this->feeds as $obj) {
							if(isset($obj->title) == 0 && isset($obj->text) == 1) {
								$obj->title = $obj->text;
							}
							if(isset($obj->xmlUrl) == 0 && isset($obj->url) == 1) {
								$obj->xmlUrl = $obj->url;
							}
							if(isset($obj->htmlUrl) == 0 && isset($obj->url) == 1) {
								$obj->htmlUrl = $obj->url;
							}

							$query = $this->db->query('SELECT fed.*, sub.sub_id FROM '.$this->db->dbprefix('feeds').' AS fed LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = fed.fed_id AND sub.mbr_id = ? WHERE fed.fed_link = ? GROUP BY fed.fed_id', array($this->member->mbr_id, $obj->xmlUrl));
							if($query->num_rows() == 0) {
								$parse_url = parse_url($obj->htmlUrl);

								$this->db->set('fed_title', $obj->title);
								$this->db->set('fed_url', $obj->htmlUrl);
								$this->db->set('fed_link', $obj->xmlUrl);
								if(isset($parse_url['host']) == 1) {
									$this->db->set('fed_host', $parse_url['host']);
								}
								$this->db->set('fed_datecreated', date('Y-m-d H:i:s'));
								$this->db->insert('feeds');
								$fed_id = $this->db->insert_id();

								$this->db->set('mbr_id', $this->member->mbr_id);
								$this->db->set('fed_id', $fed_id);
								if($obj->flr && array_key_exists($obj->flr, $folders)) {
									$this->db->set('flr_id', $folders[$obj->flr]);
								}
								$this->db->set('sub_datecreated', date('Y-m-d H:i:s'));
								$this->db->insert('subscriptions');
								$sub_id = $this->db->insert_id();

								$icon = 'plus';
							} else {
								$fed = $query->row();
								if($fed->sub_id) {
									if($obj->flr && array_key_exists($obj->flr, $folders)) {
										$this->db->set('flr_id', $folders[$obj->flr]);
										$this->db->where('mbr_id', $this->member->mbr_id);
										$this->db->where('sub_id', $fed->sub_id);
										$this->db->update('subscriptions');
									}
									$sub_id = $fed->sub_id;

									$icon = 'repeat';
								} else {
									$this->db->set('mbr_id', $this->member->mbr_id);
									$this->db->set('fed_id', $fed->fed_id);
									if($obj->flr && array_key_exists($obj->flr, $folders)) {
										$this->db->set('flr_id', $folders[$obj->flr]);
									}
									$this->db->set('sub_datecreated', date('Y-m-d H:i:s'));
									$this->db->insert('subscriptions');
									$sub_id = $this->db->insert_id();

									$icon = 'plus';
								}
							}
							$content .= '<div class="mdl-card mdl-color--'.$this->config->item('material-design/colors/background/card').' mdl-cell mdl-cell--4-col">
								<div class="mdl-card__title">
									<h1 class="mdl-card__title-text"><a class="mdl-color-text--'.$this->config->item('material-design/colors/text/link').'" href="'.base_url().'subscriptions/read/'.$sub_id.'">'.$obj->title.'</a></h1>
									<div class="mdl-card__title-infos">';
										if($this->config->item('folders')) {
											if($obj->flr && array_key_exists($obj->flr, $folders)) {
												$content .= '<a class="mdl-navigation__link" href="'.base_url().'folders/read/'.$folders[$obj->flr].'"><i class="material-icons md-16">folder</i>'.$obj->flr.'</a>';
											}
										}
									$content .= '</div>
								</div>';
								$content .= '<div class="mdl-card__actions mdl-card--border">
									<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="'.base_url().'subscriptions/update/'.$sub_id.'"><i class="material-icons md-18">mode_edit</i></a>
									<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="'.base_url().'subscriptions/delete/'.$sub_id.'"><i class="material-icons md-18">delete</i></a>
								</div>
							</div>';
						}
					}
					$content .= '</section></section></main>';
				} else {
					$this->output->set_status_header(500);
				}
			}
		}
		$this->readerself_library->set_content($content);
	}
	function import_opml($obj, $flr = false) {
		$feeds = array();
		if(isset($obj->outline) == 1) {
			foreach($obj->outline as $outline) {
				if(isset($outline->outline) == 1) {
					//echo $outline->attributes()->title;
					//print_r($outline);
					if($outline->attributes()->title) {
						$flr = strval($outline->attributes()->title);
						$this->folders[] = $flr;
					} else if($outline->attributes()->text) {
						$flr = strval($outline->attributes()->text);
						$this->folders[] = $flr;
					}
					$this->import_opml($outline, $flr);
					//array_merge($feeds, $this->import_opml($outline));
				} else {
					//print_r($outline->attributes()->title);
					$feed = new stdClass();
					foreach($outline->attributes() as $k => $attribute) {
						$feed->{$k} = strval($attribute);
					}
					$feed->flr = $flr;
					$this->feeds[] = $feed;
				}
			}
		}
		return $feeds;
	}
	public function get($mode, $id = FALSE) {
		if(!$this->axipi_session->userdata('mbr_id') && $mode != 'member') {
			redirect(base_url());
		}

		$modes = array('folder', 'nofolder');

		$content = array();

		$is_folder = FALSE;
		if($mode == 'folder') {
			$query = $this->db->query('SELECT flr.* FROM '.$this->db->dbprefix('folders').' AS flr WHERE flr.mbr_id = ? AND flr.flr_id = ? GROUP BY flr.flr_id', array($this->member->mbr_id, $id));
			if($query->num_rows() > 0) {
				$is_folder = $query->row();
			}
		}

		$this->readerself_library->set_template('_json');
		$this->readerself_library->set_content_type('application/json');

		if($this->input->is_ajax_request() && in_array($mode, $modes)) {

			if($mode == 'folder' && $is_folder) {
				$query = $this->db->query('SELECT fed.fed_host, sub.fed_id, sub.sub_id, sub.sub_priority, sub.sub_title, fed.fed_title, sub.sub_direction, fed.fed_direction FROM '.$this->db->dbprefix('subscriptions').' AS sub LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = sub.fed_id WHERE sub.mbr_id = ? AND sub.flr_id = ? GROUP BY fed.fed_id ORDER BY fed.fed_title ASC', array($this->member->mbr_id, $id));
				$content['subscriptions'] = $query->result();

			} else if($mode == 'nofolder') {
				$query = $this->db->query('SELECT fed.fed_host, sub.fed_id, sub.sub_id, sub.sub_priority, sub.sub_title, fed.fed_title, sub.sub_direction, fed.fed_direction FROM '.$this->db->dbprefix('subscriptions').' AS sub LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = sub.fed_id WHERE sub.mbr_id = ? AND sub.flr_id IS NULL GROUP BY fed.fed_id ORDER BY fed.fed_title ASC', array($this->member->mbr_id));
				$content['subscriptions'] = $query->result();

			} else {
				$content['subscriptions'] = array();
			}
		} else {
			$this->output->set_status_header(403);
		}
		$this->readerself_library->set_content($content);
	}
	function search() {
		if(!$this->axipi_session->userdata('mbr_id')) {
			redirect(base_url());
		}

		if($this->input->is_ajax_request()) {
			$this->readerself_library->set_template('_json');
			$this->readerself_library->set_content_type('application/json');
			$content = array();

			if($this->input->post('fed_title')) {
				$query = $this->db->query('SELECT fed.fed_host, sub.sub_id, sub.fed_id, sub.sub_priority, sub.sub_title, fed.fed_title, sub.sub_direction, fed.fed_direction FROM '.$this->db->dbprefix('subscriptions').' AS sub LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = sub.fed_id WHERE sub.mbr_id = ? AND (fed.fed_title LIKE ? OR sub.sub_title LIKE ?) GROUP BY fed.fed_id ORDER BY fed.fed_title ASC', array($this->member->mbr_id, '%'.$this->input->post('fed_title').'%', '%'.$this->input->post('fed_title').'%'));
				$content['subscriptions'] = $query->result();
			} else {
				$content['subscriptions'] = array();
			}
		} else {
			$this->output->set_status_header(403);
		}
		$this->readerself_library->set_content($content);
	}
}

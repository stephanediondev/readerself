<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subscriptions extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function index() {
		if(!$this->session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$filters = array();
		$filters[$this->router->class.'_subscriptions_fed_title'] = array('fed.fed_title', 'like');
		$flt = $this->reader_library->build_filters($filters);
		$flt[] = 'sub.mbr_id = \''.$this->member->mbr_id.'\'';
		$flt[] = 'fed.fed_id IS NOT NULL';
		$results = $this->reader_model->get_subscriptions_total($flt);
		$build_pagination = $this->reader_library->build_pagination($results->count, 20, $this->router->class.'_subscriptions');
		$data = array();
		$data['pagination'] = $build_pagination['output'];
		$data['position'] = $build_pagination['position'];
		$data['subscriptions'] = $this->reader_model->get_subscriptions_rows($flt, $build_pagination['limit'], $build_pagination['start'], 'fed.fed_title ASC');

		$data['errors'] = $this->db->query('SELECT fed.*, sub.sub_id, sub.sub_title FROM '.$this->db->dbprefix('subscriptions').' AS sub LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = sub.fed_id WHERE fed.fed_lasterror IS NOT NULL AND fed.fed_id IS NOT NULL AND sub.mbr_id = ? GROUP BY sub.sub_id ORDER BY fed.fed_lastcrawl DESC LIMIT 0,5', array($this->member->mbr_id))->result();

		$data['last_added'] = $this->db->query('SELECT fed.*, sub.sub_id, sub.sub_title FROM '.$this->db->dbprefix('subscriptions').' AS sub LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = sub.fed_id WHERE fed.fed_id IS NOT NULL AND sub.mbr_id = ? GROUP BY sub.sub_id ORDER BY sub.sub_id DESC LIMIT 0,5', array($this->member->mbr_id))->result();

		$content = $this->load->view('subscriptions_index', $data, TRUE);
		$this->reader_library->set_content($content);
	}

	public function create() {
		if(!$this->session->userdata('mbr_id')) {
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

			$query = $this->db->query('SELECT fed.*, sub.sub_id, IF(sub.sub_id IS NULL, 0, 1) AS subscription FROM '.$this->db->dbprefix('feeds').' AS fed LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = fed.fed_id AND sub.mbr_id = ? WHERE fed.fed_link = ? GROUP BY fed.fed_id', array($this->member->mbr_id, $this->input->post('url')));
			if($query->num_rows() == 0) {
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
					$this->db->set('fed_title', $sp_feed->get_title());
					$this->db->set('fed_url', $sp_feed->get_link());
					$this->db->set('fed_description', $sp_feed->get_description());
					$this->db->set('fed_link', $sp_feed->subscribe_url());
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
					$this->db->set('sub_datecreated', date('Y-m-d H:i:s'));
					$this->db->insert('subscriptions');
					$sub_id = $this->db->insert_id();

					$data['sub_id'] = $sub_id;
					$data['fed_title'] = $sp_feed->get_title();

					$this->reader_library->crawl_items($fed_id, $sp_feed->get_items());
				}
				$sp_feed->__destruct();
				unset($sp_feed);
			} else {
				$fed = $query->row();
				if($fed->subscription == 0) {
					$this->db->set('mbr_id', $this->member->mbr_id);
					$this->db->set('fed_id', $fed->fed_id);
					if($this->config->item('folders')) {
						if($folder) {
							$this->db->set('flr_id', $folder);
						}
					}
					$this->db->set('sub_priority', $this->input->post('priority'));
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
				redirect(base_url().'subscriptions');
			}
		}
		$this->reader_library->set_content($content);
	}

	public function read($sub_id) {
		if(!$this->session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$data = array();
		$data['sub'] = $this->reader_model->get_subscription_row($sub_id);
		if($data['sub']) {

			$data['errors'] = $this->db->query('SELECT fed.*, sub.sub_id, sub.sub_title FROM '.$this->db->dbprefix('subscriptions').' AS sub LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = sub.fed_id WHERE fed.fed_lasterror IS NOT NULL AND fed.fed_id IS NOT NULL AND sub.mbr_id = ? GROUP BY sub.sub_id ORDER BY fed.fed_lastcrawl DESC LIMIT 0,5', array($this->member->mbr_id))->result();

			$data['last_added'] = $this->db->query('SELECT fed.*, sub.sub_id, sub.sub_title FROM '.$this->db->dbprefix('subscriptions').' AS sub LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = sub.fed_id WHERE fed.fed_id IS NOT NULL AND sub.mbr_id = ? GROUP BY sub.sub_id ORDER BY sub.sub_id DESC LIMIT 0,5', array($this->member->mbr_id))->result();

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

			$legend = array();
			$values = array();
			$query = $this->db->query('SELECT itm.itm_author AS ref, COUNT(DISTINCT(hst.itm_id)) AS nb FROM '.$this->db->dbprefix('history').' AS hst LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.itm_id = hst.itm_id LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = itm.fed_id LEFT JOIN '.$this->db->dbprefix('categories').' AS cat ON cat.itm_id = itm.itm_id WHERE itm.itm_author IS NOT NULL AND hst.hst_real = ? AND hst.hst_datecreated >= ? AND hst.mbr_id = ? AND sub.sub_id = ? GROUP BY ref ORDER BY nb DESC LIMIT 0,30', array(1, $date_ref, $this->member->mbr_id, $sub_id));
			if($query->num_rows() > 0) {
				foreach($query->result() as $row) {
					$legend[] = $row->ref;
					$values[] = $row->nb;
				}
			}
			$data['tables'] .= build_table_repartition($this->lang->line('items_read_by_author').'*', $values, $legend);

			$legend = array();
			$values = array();
			$query = $this->db->query('SELECT SUBSTRING(DATE_ADD(hst.hst_datecreated, INTERVAL ? HOUR), 1, 10) AS ref, COUNT(DISTINCT(hst.itm_id)) AS nb FROM '.$this->db->dbprefix('history').' AS hst LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.itm_id = hst.itm_id LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = itm.fed_id WHERE hst.hst_real = ? AND hst.mbr_id = ? AND sub.sub_id = ? GROUP BY ref ORDER BY ref DESC LIMIT 0,30', array($this->session->userdata('timezone'), 1, $this->member->mbr_id, $sub_id));
			if($query->num_rows() > 0) {
				foreach($query->result() as $row) {
					$legend[] = date('F j, Y', strtotime($row->ref));
					$values[] = $row->nb;
				}
			}
			$data['tables'] .= build_table_progression($this->lang->line('items_read_by_day'), $values, $legend);

			$legend = array();
			$values = array();
			$query = $this->db->query('SELECT SUBSTRING(DATE_ADD(hst.hst_datecreated, INTERVAL ? HOUR), 1, 7) AS ref, COUNT(DISTINCT(hst.itm_id)) AS nb FROM '.$this->db->dbprefix('history').' AS hst LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.itm_id = hst.itm_id LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = itm.fed_id WHERE hst.hst_real = ? AND hst.mbr_id = ? AND sub.sub_id = ? GROUP BY ref ORDER BY ref DESC LIMIT 0,12', array($this->session->userdata('timezone'), 1, $this->member->mbr_id, $sub_id));
			if($query->num_rows() > 0) {
				foreach($query->result() as $row) {
					$legend[] = date('F, Y', strtotime($row->ref));
					$values[] = $row->nb;
				}
			}
			$data['tables'] .= build_table_progression($this->lang->line('items_read_by_month'), $values, $legend);

			if($this->config->item('star')) {
				$legend = array();
				$values = array();
				$query = $this->db->query('SELECT SUBSTRING(DATE_ADD(fav.fav_datecreated, INTERVAL ? HOUR), 1, 7) AS ref, COUNT(DISTINCT(fav.itm_id)) AS nb FROM '.$this->db->dbprefix('favorites').' AS fav LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.itm_id = fav.itm_id LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = itm.fed_id WHERE fav.mbr_id = ? AND sub.sub_id = ? GROUP BY ref ORDER BY ref DESC LIMIT 0,12', array($this->session->userdata('timezone'), $this->member->mbr_id, $sub_id));
				if($query->num_rows() > 0) {
					foreach($query->result() as $row) {
						$legend[] = date('F, Y', strtotime($row->ref));
						$values[] = $row->nb;
					}
				}
				$data['tables'] .= build_table_progression($this->lang->line('items_starred_by_month'), $values, $legend);
			}

			$content = $this->load->view('subscriptions_read', $data, TRUE);
			$this->reader_library->set_content($content);
		} else {
			$this->index();
		}
	}

	public function update($sub_id) {
		if(!$this->session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$this->load->library('form_validation');
		$data = array();
		$data['sub'] = $this->reader_model->get_subscription_row($sub_id);
		if($data['sub']) {
			$query = $this->db->query('SELECT flr.* FROM '.$this->db->dbprefix('folders').' AS flr WHERE flr.mbr_id = ? GROUP BY flr.flr_id ORDER BY flr.flr_title ASC', array($this->member->mbr_id));
			$data['folders'] = array();
			$data['folders'][0] = $this->lang->line('no_folder');
			if($query->num_rows() > 0) {
				foreach($query->result() as $flr) {
					$data['folders'][$flr->flr_id] = $flr->flr_title;
				}
			}

			$this->form_validation->set_rules('sub_title', 'lang:sub_title', 'max_length[255]');
			$this->form_validation->set_rules('folder', 'lang:folder', 'required');
			$this->form_validation->set_rules('priority', 'lang:priority', 'numeric');
			if($this->form_validation->run() == FALSE) {
				$content = $this->load->view('subscriptions_update', $data, TRUE);
				$this->reader_library->set_content($content);
			} else {
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
				$this->db->where('sub_id', $sub_id);
				$this->db->update('subscriptions');

				redirect(base_url().'subscriptions/read/'.$sub_id);
			}
		} else {
			$this->index();
		}
	}

	public function delete($sub_id) {
		if(!$this->session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$this->load->library('form_validation');
		$data = array();
		$data['sub'] = $this->reader_model->get_subscription_row($sub_id);
		if($data['sub']) {
			$this->form_validation->set_rules('confirm', 'lang:confirm', 'required');
			if($this->form_validation->run() == FALSE) {
				$content = $this->load->view('subscriptions_delete', $data, TRUE);
				$this->reader_library->set_content($content);
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
		if(!$this->session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$content = array();

		if($this->input->is_ajax_request()) {
			$this->reader_library->set_template('_json');
			$this->reader_library->set_content_type('application/json');

			$sub = $this->reader_model->get_subscription_row($sub_id);
			if($sub) {
				if($sub->sub_priority == 1) {
					$this->db->set('sub_priority', 0);
					$content['status'] = 'not_priority';
				} else {
					$this->db->set('sub_priority', 1);
					$content['status'] = 'priority';
				}
				$this->db->where('sub_id', $sub_id);
				$this->db->where('mbr_id', $this->member->mbr_id);
				$this->db->update('subscriptions');
			}
		} else {
			$this->output->set_status_header(403);
		}
		$this->reader_library->set_content($content);
	}

	public function export() {
		if(!$this->session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$this->reader_library->set_template('_opml');
		$this->reader_library->set_content_type('application/xml');

		header('Content-Disposition: inline; filename="subscriptions-'.date('Y-m-d').'.opml";');

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
		$this->reader_library->set_content($content);
	}

	public function import() {
		if(!$this->session->userdata('mbr_id')) {
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

					$content .= '<nav>
		<ul class="actions">
			<li><a href="'.base_url().'subscriptions"><i class="icon icon-step-backward"></i>'.$this->lang->line('back').'</a></li>
		</ul>
	</nav>
</header>
<main><section><section>';

					if(count($this->folders) > 0) {
						$content_folders = '<article class="cell title"><h2><i class="icon icon-folder-close"></i>'.$this->lang->line('folders').' ('.count($this->folders).')</h2></article>';
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
							$content_folders .= '<article class="cell">
								<ul class="actions">
									<li><a href="'.base_url().'folders/update/'.$folders[$value].'"><i class="icon icon-pencil"></i>'.$this->lang->line('update').'</a></li>
									<li><a href="'.base_url().'folders/delete/'.$folders[$value].'"><i class="icon icon-trash"></i>'.$this->lang->line('delete').'</a></li>
								</ul>
								<h2><a href="'.base_url().'folders/read/'.$folders[$value].'"><i class="icon icon-'.$icon.'"></i>'.$value.'</a></h2>
							</article>';
						}
						if($this->config->item('folders')) {
							$content .= $content_folders;
						}
					}

					if(count($this->feeds) > 0) {
						$content .= '<article class="cell title"><h2><i class="icon icon-rss"></i>'.$this->lang->line('subscriptions').' ('.count($this->feeds).')</h2></article>';
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
								$this->db->set('fed_title', $obj->title);
								$this->db->set('fed_url', $obj->htmlUrl);
								$this->db->set('fed_link', $obj->xmlUrl);
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
							$content .= '<article class="cell">
								<ul class="actions">
									<li><a href="'.base_url().'subscriptions/update/'.$sub_id.'"><i class="icon icon-pencil"></i>'.$this->lang->line('update').'</a></li>
									<li><a href="'.base_url().'subscriptions/delete/'.$sub_id.'"><i class="icon icon-trash"></i>'.$this->lang->line('delete').'</a></li>
								</ul>
								<h2><a href="'.base_url().'subscriptions/read/'.$sub_id.'"><i class="icon icon-'.$icon.'"></i>'.$obj->title.'</a></h2>
								<ul class="item-details">';
								if($this->config->item('folders')) {
									if($obj->flr && array_key_exists($obj->flr, $folders)) {
										$content .= '<li><a href="'.base_url().'folders/read/'.$folders[$obj->flr].'"><i class="icon icon-folder-close"></i>'.$obj->flr.'</a></li>';
									} else {
										$content .= '<li><i class="icon icon-folder-close"></i><em>'.$this->lang->line('no_folder').'</em></li>';
									}
								}
								$content .= '</ul>
							</article>';
						}
					}
					$content .= '</section></section></main>';
				} else {
					$this->output->set_status_header(500);
				}
			}
		}
		$this->reader_library->set_content($content);
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
}

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function index() {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$this->load->library('form_validation');

		$query = $this->db->query('SELECT flr.* FROM '.$this->db->dbprefix('folders').' AS flr WHERE flr.mbr_id = ? GROUP BY flr.flr_id ORDER BY flr.flr_title ASC', array($this->member->mbr_id));

		$data = array();
		$data['folders'] = $query->result();
		$content = $this->load->view('home_index', $data, TRUE);
		$this->reader_library->set_content($content);
	}
	public function timezone() {
		$content = array();

		if($this->input->is_ajax_request()) {
			$this->reader_library->set_template('_json');
			$this->reader_library->set_content_type('application/json');

			$this->session->set_userdata('timezone', $this->input->post('timezone'));
		} else {
			$this->output->set_status_header(403);
		}
		$this->reader_library->set_content($content);
	}
	function subscriptions() {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		if($this->input->is_ajax_request()) {
			$this->reader_library->set_template('_json');
			$this->reader_library->set_content_type('application/json');
			$content = array();

			if($this->input->post('fed_title')) {
				$query = $this->db->query('SELECT sub.sub_id, sub.sub_title, fed.* FROM '.$this->db->dbprefix('subscriptions').' AS sub LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = sub.fed_id WHERE sub.mbr_id = ? AND (fed.fed_title LIKE ? OR sub.sub_title LIKE ?) GROUP BY fed.fed_id ORDER BY fed.fed_title ASC', array($this->member->mbr_id, '%'.$this->input->post('fed_title').'%', '%'.$this->input->post('fed_title').'%'));
				$content['subscriptions'] = $query->result();
			} else {
				$content['subscriptions'] = array();
			}
		} else {
			$this->output->set_status_header(403);
		}
		$this->reader_library->set_content($content);
	}
	public function items($mode, $id = FALSE) {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$modes = array('all', 'starred', 'shared', 'nofolder', 'folder', 'subscription', 'category', 'author', 'search', 'tags');

		$content = array();
		$introduction_title = false;
		$introduction_details = false;

		$is_folder = FALSE;
		if($mode == 'folder') {
			$query = $this->db->query('SELECT flr.* FROM '.$this->db->dbprefix('folders').' AS flr WHERE flr.mbr_id = ? AND flr.flr_id = ? GROUP BY flr.flr_id', array($this->member->mbr_id, $id));
			if($query->num_rows() > 0) {
				$is_folder = $query->row();
			}
		}

		$is_subscription = FALSE;
		if($mode == 'subscription') {
			$query = $this->db->query('SELECT sub.*, fed.fed_url, IF(sub.sub_title IS NOT NULL, sub.sub_title, fed.fed_title) AS fed_title FROM '.$this->db->dbprefix('subscriptions').' AS sub LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = sub.fed_id WHERE sub.mbr_id = ? AND sub.sub_id = ? GROUP BY sub.sub_id', array($this->member->mbr_id, $id));
			if($query->num_rows() > 0) {
				$is_subscription = $query->row();
			}
		}

		$is_author = FALSE;
		if($mode == 'author') {
			$query = $this->db->query('SELECT itm.itm_author FROM '.$this->db->dbprefix('items').' AS itm WHERE itm.itm_id = ? GROUP BY itm.itm_id', array($id));
			if($query->num_rows() > 0) {
				$is_author = $query->row()->itm_author;
			}
		}

		$is_category = FALSE;
		if($mode == 'category') {
			$query = $this->db->query('SELECT cat.cat_title FROM '.$this->db->dbprefix('categories').' AS cat WHERE cat.cat_id = ? GROUP BY cat.cat_id', array($id));
			if($query->num_rows() > 0) {
				$is_category = $query->row()->cat_title;
			}
		}

		$this->reader_library->set_template('_json');
		$this->reader_library->set_content_type('application/json');

		if($this->input->is_ajax_request() && in_array($mode, $modes)) {

			$this->session->set_userdata('items-mode', $mode);
			$this->session->set_userdata('items-id', $id);

			$content['nav'] = array();
			$content['nav']['refresh-items'] = true;
			$content['nav']['mode-items'] = true;
			$content['nav']['display-items'] = true;
			$content['nav']['read_all'] = true;
			$content['nav']['item-up'] = true;
			$content['nav']['item-down'] = true;

			if($mode == 'tags' && $this->config->item('tags')) {
				$content['result_type'] = 'tags';

				$content['nav']['mode-items'] = false;
				$content['nav']['display-items'] = false;
				$content['nav']['read_all'] = false;
				$content['nav']['item-up'] = false;
				$content['nav']['item-down'] = false;

				$date_ref = date('Y-m-d H:i:s', time() - 3600 * 24 * 30);

				$introduction_title = '<i class="icon icon-tags"></i>'.$this->lang->line('tags').'*';
				$content['end'] = '<article class="neutral title">';
				$content['end'] .= '<p>*'.$this->lang->line('last_30_days').'</p>';
				$content['end'] .= '</article>';

				$tags = array();

				$legend = array();
				$values = array();
				$query = $this->db->query('SELECT LOWER(cat.cat_title) AS ref, cat.cat_id AS id, COUNT(DISTINCT(itm.itm_id)) AS count FROM '.$this->db->dbprefix('items').' AS itm LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = itm.fed_id LEFT JOIN '.$this->db->dbprefix('categories').' AS cat ON cat.itm_id = itm.itm_id WHERE cat.cat_id IS NOT NULL AND cat.cat_datecreated >= ? AND sub.mbr_id = ? GROUP BY ref ORDER BY count DESC LIMIT 0,100', array($date_ref, $this->member->mbr_id));
				if($query->num_rows() > 0) {
					$exclude = array('non classé', 'uncategorized', 'actualités : informatique', 'actualités : internet', 'actualités : télécoms', 'actualités : it management');
					$max = false;
					foreach($query->result() as $row) {
						if(!in_array($row->ref, $exclude)) {
							if(!$max) {
								$max = $row->count;
							}
							$tags[$row->ref] = array('count'=>$row->count, 'id'=>$row->id);
						}
					}
				}
				ksort($tags);
				$content['tags'] = '<div id="tags" class="neutral"><p>';
				foreach($tags as $k => $v) {
					$percent = ($v['count'] * 100) / $max;
					$percent = $percent - ($percent % 10);
					$percent = intval($percent) + 100;
					$content['tags'] .= '<a class="category" data-cat_id="'.$v['id'].'" href="'.base_url().'home/items/category/'.$v['id'].'" style="font-size:'.$percent.'%;">'.$k.'</a> ';
				}
				$content['tags'] .= '</p></div>';

			} else {
				$content['result_type'] = 'items';

				$introduction_title = '<i class="icon icon-asterisk"></i>'.$this->lang->line('all_items').' (<span id="intro-load-all-items"></span>)';

				$content['items'] = array();

				$where = array();
				$bindings = array();

				$where[] = 'itm.fed_id IN ( SELECT sub.fed_id FROM subscriptions AS sub WHERE sub.fed_id = itm.fed_id AND sub.mbr_id = ? )';
				$bindings[] = $this->member->mbr_id;

				if($mode == 'starred') {
					$introduction_title = '<i class="icon icon-star"></i>'.$this->lang->line('starred_items').' {<span id="intro-load-starred-items"></span>}';
					$where[] = 'itm.itm_id IN ( SELECT fav.itm_id FROM favorites AS fav WHERE fav.itm_id = itm.itm_id AND fav.mbr_id = ? )';
					$bindings[] = $this->member->mbr_id;

					$content['nav']['mode-items'] = false;
					$content['nav']['read_all'] = false;

				} else if($mode == 'shared') {
					if(!$this->member->token_share) {
						$token_share = sha1(uniqid($this->member->mbr_id, 1).mt_rand());
						$this->db->set('token_share', $token_share);
						$this->db->where('mbr_id', $this->member->mbr_id);
						$this->db->update('members');
						$this->member = $this->reader_model->get($this->session->userdata('logged_member'));
					}
					$introduction_title = '<i class="icon icon-heart"></i>'.$this->lang->line('shared_items').' {<span id="intro-load-shared-items"></span>}';
					$introduction_details = '<ul class="item-details"><li><a target="_blank" href="'.base_url().'share/'.$this->member->token_share.'"><i class="icon icon-rss"></i>'.base_url().'share/'.$this->member->token_share.'</a></li></ul>';
					$where[] = 'itm.itm_id IN ( SELECT shr.itm_id FROM share AS shr WHERE shr.itm_id = itm.itm_id AND shr.mbr_id = ? )';
					$bindings[] = $this->member->mbr_id;

					$content['nav']['mode-items'] = false;
					$content['nav']['read_all'] = false;

				} else {
					if($mode == 'search') {
						$search = urldecode($id);
						$words = explode(' ', $search);
						foreach($words as $word) {
							if(substr($word, 0, 1) == '@') {
								$where[] = 'DATE_ADD(itm.itm_date, INTERVAL ? HOUR) LIKE ?';
								$bindings[] = $this->session->userdata('timezone');
								$bindings[] = substr($word, 1).'%';
							} else {
								$where[] = 'itm.itm_title LIKE ?';
								$bindings[] = '%'.$word.'%';
							}
						}
						$content['nav']['refresh-items'] = false;
						$content['nav']['mode-items'] = false;
						$content['nav']['read_all'] = false;

					} else if($this->input->get('mode-items') == 'unread_only') {
						$where[] = 'itm.itm_id NOT IN ( SELECT hst.itm_id FROM history AS hst WHERE hst.itm_id = itm.itm_id AND hst.mbr_id = ? )';
						$bindings[] = $this->member->mbr_id;
					}
				}

				if($is_folder) {
					$introduction_title = '<i class="icon icon-folder-close"></i>'.$is_folder->flr_title.' (<span id="intro-load-folder-'.$is_folder->flr_id.'-items"></span>)';
					$where[] = 'itm.fed_id IN ( SELECT sub.fed_id FROM subscriptions AS sub WHERE sub.fed_id = itm.fed_id AND sub.flr_id = ? )';
					$bindings[] = $is_folder->flr_id;
				}

				if($is_subscription) {
					$introduction_title = '<i class="icon icon-rss"></i>'.$is_subscription->fed_title.' (<span id="intro-load-sub-'.$is_subscription->sub_id.'-items"></span>)';
					if($is_subscription->fed_url) {
						$introduction_details = '<ul class="item-details"><li><a target="_blank" href="'.$is_subscription->fed_url.'"><i class="icon icon-external-link"></i>'.$is_subscription->fed_url.'</a></li></ul>';
					}
					$where[] = 'itm.fed_id IN ( SELECT sub.fed_id FROM subscriptions AS sub WHERE sub.fed_id = itm.fed_id AND sub.sub_id = ? )';
					$bindings[] = $is_subscription->sub_id;
				}

				if($is_author) {
					$introduction_title = '<i class="icon icon-user"></i>'.$is_author;
					$where[] = 'itm.itm_author = ?';
					$bindings[] = $is_author;
				}

				if($is_category) {
					$introduction_title = '<i class="icon icon-tag"></i>'.$is_category;
					$where[] = 'itm.itm_id IN ( SELECT cat.itm_id FROM categories AS cat WHERE cat.cat_title = ? )';
					$bindings[] = $is_category;
				}

				if($mode == 'nofolder') {
					$introduction_title = '<i class="icon icon-folder-close"></i><em>'.$this->lang->line('no_folder').'</em> (<span id="intro-load-nofolder-items"></span>)';
					$where[] = 'itm.fed_id IN ( SELECT sub.fed_id FROM subscriptions AS sub WHERE sub.fed_id = itm.fed_id AND sub.flr_id IS NULL )';
				}

				if($mode == 'search') {
					$sql = 'SELECT COUNT(DISTINCT(itm.itm_id)) AS global_total
					FROM items AS itm
					WHERE '.implode(' AND ', $where);
					$query = $this->db->query($sql, $bindings);
					$content['total_global'] = intval($query->row()->global_total);
					$introduction_title = '<i class="icon icon-file-text-alt"></i>'.$search.' {<span id="intro-load-search-items">'.$content['total_global'].'</span>}';
				}

				array_unshift($bindings, $this->session->userdata('timezone'));

				$sql = 'SELECT itm.*, DATE_ADD(itm.itm_date, INTERVAL ? HOUR) AS itm_date
				FROM items AS itm
				WHERE '.implode(' AND ', $where).'
				GROUP BY itm.itm_id
				ORDER BY itm.itm_date DESC';
				if($mode == 'starred') {
					$sql .= ' LIMIT '.intval($this->input->post('pagination')).',10';
				} else if($mode == 'shared') {
					$sql .= ' LIMIT '.intval($this->input->post('pagination')).',10';
				} else {
					if($mode == 'search') {
						$sql .= ' LIMIT '.intval($this->input->post('pagination')).',10';
					} else if($this->input->get('mode-items') == 'unread_only') {
						$sql .= ' LIMIT 0,10';
					} else {
						$sql .= ' LIMIT '.intval($this->input->post('pagination')).',10';
					}
				}
				$query = $this->db->query($sql, $bindings);
				$content['total'] = $query->num_rows();

				if($query->num_rows() > 0) {
					foreach($query->result() as $itm) {
						$sql = 'SELECT fed.* FROM feeds AS fed WHERE fed.fed_id = ? GROUP BY fed.fed_id';
						$itm->fed = $this->db->query($sql, array($itm->fed_id))->row();

						$sql = 'SELECT sub.sub_id, sub.sub_title, flr.flr_id, flr.flr_title FROM subscriptions AS sub LEFT JOIN '.$this->db->dbprefix('folders').' AS flr ON flr.flr_id = sub.flr_id WHERE sub.fed_id = ? AND sub.mbr_id = ? GROUP BY sub.sub_id';
						$itm->sub = $this->db->query($sql, array($itm->fed_id, $this->member->mbr_id))->row();

						$itm->categories = false;
						if($this->config->item('tags')) {
							$sql = 'SELECT cat.* FROM categories AS cat WHERE cat.itm_id = ? GROUP BY cat.cat_id';
							$categories = $this->db->query($sql, array($itm->itm_id))->result();
							if($categories) {
								$itm->categories = array();
								foreach($categories as $cat) {
									$itm->categories[] = '<a class="category" data-cat_id="'.$cat->cat_id.'" href="'.base_url().'home/items/category/'.$cat->cat_id.'">'.$cat->cat_title.'</a>';
								}
							}
						}

						$sql = 'SELECT enr.* FROM enclosures AS enr WHERE enr.itm_id = ? GROUP BY enr.enr_id';
						$itm->enclosures = $this->db->query($sql, array($itm->itm_id))->result();

						$sql = 'SELECT hst.* FROM history AS hst WHERE hst.itm_id = ? AND hst.mbr_id = ? GROUP BY hst.hst_id';
						$query = $this->db->query($sql, array($itm->itm_id, $this->member->mbr_id));
						if($query->num_rows > 0) {
							$itm->history = 'read';
						} else {
							$itm->history = 'unread';
						}

						if($this->config->item('star')) {
							$sql = 'SELECT fav.* FROM favorites AS fav WHERE fav.itm_id = ? AND fav.mbr_id = ? GROUP BY fav.fav_id';
							$query = $this->db->query($sql, array($itm->itm_id, $this->member->mbr_id));
							if($query->num_rows > 0) {
								$itm->star = 1;
							} else {
								$itm->star = 0;
							}
						}

						if($this->config->item('share')) {
							$sql = 'SELECT shr.* FROM share AS shr WHERE shr.itm_id = ? AND shr.mbr_id = ? GROUP BY shr.shr_id';
							$query = $this->db->query($sql, array($itm->itm_id, $this->member->mbr_id));
							if($query->num_rows > 0) {
								$itm->share = 1;
							} else {
								$itm->share = 0;
							}
						}

						list($itm->explode_date, $itm->explode_time) = explode(' ', $itm->itm_date);

						//$itm->itm_content = strip_tags($itm->itm_content, '<dt><dd><dl><table><caption><tr><th><td><tbody><thead><h2><h3><h4><h5><h6><strong><em><code><pre><blockquote><p><ul><li><ol><br><del><a><img><figure><figcaption><cite><time><abbr>');

						preg_match_all('/<a[^>]+>/i', $itm->itm_content, $result);
						foreach($result[0] as $flr_a) {
							if(!preg_match('/(target)=("[^"]*")/i', $flr_a, $result)) {
								$itm->itm_content = str_replace($flr_a, str_replace('<a', '<a target="_blank"', $flr_a), $itm->itm_content);
							}
						}

						preg_match_all('/<img[^>]+>/i', $itm->itm_content, $result);
						foreach($result[0] as $flr_img) {
							$attribute_src = false;
							if(preg_match('/(src)=("[^"]*")/i', $flr_img, $result)) {
								$attribute_src = str_replace('"', '', $result[2]);
							}

							$attribute_width = false;
							if(preg_match('/(width)=("[^"]*")/i', $flr_img, $result)) {
								$attribute_width = str_replace('"', '', $result[2]);
							}

							$attribute_height = false;
							if(preg_match('/(height)=("[^"]*")/i', $flr_img, $result)) {
								$attribute_height = str_replace('"', '', $result[2]);
							}

							if($attribute_width == 1 || $attribute_height == 1 || stristr($attribute_src, 'feedsportal.com') || stristr($attribute_src, 'feedburner.com')) {
								$itm->itm_content = str_replace($flr_img, '', $itm->itm_content);
							}
						}

						$content['items'][$itm->itm_id] = array('itm_id' => $itm->itm_id, 'itm_content' => $this->load->view('item', array('itm'=>$itm), TRUE));
					}
				} else {
					$lastcrawl = $this->db->query('SELECT DATE_ADD(crr.crr_datecreated, INTERVAL ? HOUR) AS crr_datecreated FROM '.$this->db->dbprefix('crawler').' AS crr GROUP BY crr.crr_id ORDER BY crr.crr_id DESC LIMIT 0,1', array($this->session->userdata('timezone')))->row();
					if($lastcrawl) {
						$content['end'] = '<article id="last_crawl" class="neutral title">';
						list($date, $time) = explode(' ', $lastcrawl->crr_datecreated);
						$content['end'] .= '<h2><i class="icon icon-truck"></i>'.$this->lang->line('last_crawl').'</h2><ul class="item-details"><li><i class="icon icon-calendar"></i>'.$date.'</li><li><i class="icon icon-time"></i>'.$time.' (<span class="timeago" title="'.$lastcrawl->crr_datecreated.'"></span>)</li></ul>';
						$content['end'] .= '</article>';
					}
				}
			}

			if($introduction_title) {
				$content['begin'] = '<article id="introduction" class="neutral title">';
				$content['begin'] .= '<h2>'.$introduction_title.'</h2>';
				if($introduction_details) {
					$content['begin'] .= $introduction_details;
				}
				$content['begin'] .= '</article>';
			}
		} else {
			$this->output->set_status_header(403);
		}
		$this->reader_library->set_content($content);
	}
	public function star($itm_id) {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$content = array();

		if($this->input->is_ajax_request()) {
			$this->reader_library->set_template('_json');
			$this->reader_library->set_content_type('application/json');

			$query = $this->db->query('SELECT * FROM '.$this->db->dbprefix('favorites').' AS fav WHERE fav.itm_id = ? AND fav.mbr_id = ? GROUP BY fav.fav_id', array($itm_id, $this->member->mbr_id));
			if($query->num_rows() > 0) {
				$this->db->where('itm_id', $itm_id);
				$this->db->where('mbr_id', $this->member->mbr_id);
				$this->db->delete('favorites');
				$content['status'] = 'unstar';
			} else {
				$this->db->set('itm_id', $itm_id);
				$this->db->set('mbr_id', $this->member->mbr_id);
				$this->db->set('fav_datecreated', date('Y-m-d H:i:s'));
				$this->db->insert('favorites');
				$content['status'] = 'star';
			}
		} else {
			$this->output->set_status_header(403);
		}
		$this->reader_library->set_content($content);
	}
	public function share($itm_id) {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$content = array();

		if($this->input->is_ajax_request()) {
			$this->reader_library->set_template('_json');
			$this->reader_library->set_content_type('application/json');

			$query = $this->db->query('SELECT * FROM '.$this->db->dbprefix('share').' AS shr WHERE shr.itm_id = ? AND shr.mbr_id = ? GROUP BY shr.shr_id', array($itm_id, $this->member->mbr_id));
			if($query->num_rows() > 0) {
				$this->db->where('itm_id', $itm_id);
				$this->db->where('mbr_id', $this->member->mbr_id);
				$this->db->delete('share');
				$content['status'] = 'unshare';
			} else {
				$this->db->set('itm_id', $itm_id);
				$this->db->set('mbr_id', $this->member->mbr_id);
				$this->db->set('shr_datecreated', date('Y-m-d H:i:s'));
				$this->db->insert('share');
				$content['status'] = 'share';
			}
		} else {
			$this->output->set_status_header(403);
		}
		$this->reader_library->set_content($content);
	}
	public function history($type, $id = FALSE, $auto = FALSE) {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$types = array('dialog', 'toggle');

		$data = array();

		$content = array();

		if($this->input->is_ajax_request() && in_array($type, $types)) {
			$this->reader_library->set_template('_json');
			$this->reader_library->set_content_type('application/json');

			if($type == 'dialog' && $this->session->userdata('items-mode')) {
				$this->load->library(array('form_validation'));

				$data['title'] = $this->lang->line('all_items');
				$data['icon'] = 'asterisk';

				$sql = 'SELECT COUNT(DISTINCT(itm.itm_id)) AS count
				FROM '.$this->db->dbprefix('subscriptions').' AS sub
				LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.fed_id = sub.fed_id
				LEFT JOIN '.$this->db->dbprefix('history').' AS hst ON hst.itm_id = itm.itm_id AND hst.mbr_id = ?
				WHERE hst.hst_id IS NULL AND sub.mbr_id = ?';
				$query = $this->db->query($sql, array($this->member->mbr_id, $this->member->mbr_id));

				$data['count'] = $query->row()->count;

				$is_folder = FALSE;
				if($this->session->userdata('items-mode') == 'folder') {
					$query = $this->db->query('SELECT flr.* FROM '.$this->db->dbprefix('folders').' AS flr WHERE flr.mbr_id = ? AND flr.flr_id = ? GROUP BY flr.flr_id', array($this->member->mbr_id, $this->session->userdata('items-id')));
					if($query->num_rows() > 0) {
						$is_folder = $query->row();
						$data['title'] = $is_folder->flr_title;
						$data['icon'] = 'folder-close';

						$sql = 'SELECT flr.flr_id, COUNT(DISTINCT(itm.itm_id)) AS count
						FROM '.$this->db->dbprefix('folders').' AS flr
						LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.flr_id = flr.flr_id AND sub.mbr_id = ?
						LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.fed_id = sub.fed_id AND itm.itm_id NOT IN (SELECT hst.itm_id FROM '.$this->db->dbprefix('history').' AS hst WHERE hst.mbr_id = ?)
						WHERE flr.flr_id = ?
						GROUP BY flr.flr_id';
						$query = $this->db->query($sql, array($this->member->mbr_id, $this->member->mbr_id, $is_folder->flr_id));

						$data['count'] = $query->row()->count;
					}
				}

				$is_subscription = FALSE;
				if($this->session->userdata('items-mode') == 'subscription') {
					$query = $this->db->query('SELECT sub.*, IF(sub.sub_title IS NOT NULL, sub.sub_title, fed.fed_title) AS fed_title FROM '.$this->db->dbprefix('subscriptions').' AS sub LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = sub.fed_id WHERE sub.mbr_id = ? AND sub.sub_id = ? GROUP BY sub.sub_id', array($this->member->mbr_id, $this->session->userdata('items-id')));
					if($query->num_rows() > 0) {
						$is_subscription = $query->row();
						$data['title'] = $is_subscription->fed_title;
						$data['icon'] = 'rss';

						$sql = 'SELECT sub.sub_id, COUNT(DISTINCT(itm.itm_id)) AS count
						FROM '.$this->db->dbprefix('subscriptions').' AS sub
						LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.fed_id = sub.fed_id AND itm.itm_id NOT IN (SELECT hst.itm_id FROM '.$this->db->dbprefix('history').' AS hst WHERE hst.mbr_id = ?)
						WHERE sub.mbr_id = ? AND sub.sub_id = ? GROUP BY sub.sub_id';
						$query = $this->db->query($sql, array($this->member->mbr_id, $this->member->mbr_id, $is_subscription->sub_id));

						$data['count'] = $query->row()->count;
					}
				}

				if($this->session->userdata('items-mode') == 'nofolder') {
					$data['title'] = '<em>'.$this->lang->line('no_folder').'</em>';
					$data['icon'] = 'folder-close';
					$sql = 'SELECT COUNT(DISTINCT(itm.itm_id)) AS count
					FROM '.$this->db->dbprefix('subscriptions').' AS sub
					LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.fed_id = sub.fed_id AND itm.itm_id NOT IN (SELECT hst.itm_id FROM '.$this->db->dbprefix('history').' AS hst WHERE hst.mbr_id = ?)
					WHERE sub.flr_id IS NULL AND sub.mbr_id = ?';
					$query = $this->db->query($sql, array($this->member->mbr_id, $this->member->mbr_id));

					$data['count'] = $query->row()->count;
				}

				$this->form_validation->set_rules('age', 'lang:age', 'required');

				if($this->form_validation->run() == FALSE) {
					$content['modal'] = $this->load->view('home_history', $data, TRUE);
				} else {
					$where = array();
					$bindings = array();

					$bindings[] = $this->member->mbr_id;
					$bindings[] = date('Y-m-d H:i:s');

					$where[] = 'itm.fed_id IN ( SELECT sub.fed_id FROM subscriptions AS sub WHERE sub.fed_id = itm.fed_id AND sub.mbr_id = ? )';
					$bindings[] = $this->member->mbr_id;

					$where[] = 'itm.itm_id NOT IN ( SELECT hst.itm_id FROM history AS hst WHERE hst.itm_id = itm.itm_id AND hst.mbr_id = ? )';
					$bindings[] = $this->member->mbr_id;

					if($is_folder) {
						$where[] = 'itm.fed_id IN ( SELECT sub.fed_id FROM subscriptions AS sub WHERE sub.fed_id = itm.fed_id AND sub.flr_id = ? )';
						$bindings[] = $is_folder->flr_id;
					}
					if($is_subscription) {
						$where[] = 'itm.fed_id IN ( SELECT sub.fed_id FROM subscriptions AS sub WHERE sub.fed_id = itm.fed_id AND sub.sub_id = ? )';
						$bindings[] = $is_subscription->sub_id;
					}
					if($this->session->userdata('items-mode') == 'nofolder') {
						$where[] = 'itm.fed_id IN ( SELECT sub.fed_id FROM subscriptions AS sub WHERE sub.fed_id = itm.fed_id AND sub.flr_id IS NULL )';
					}

					if($this->input->post('age') == 'one-day') {
						$where[] = 'DATE_ADD(itm.itm_date, INTERVAL 1 DAY) < ?';
						$bindings[] = date('Y-m-d H:i:s');
					}
					if($this->input->post('age') == 'one-week') {
						$where[] = 'DATE_ADD(itm.itm_date, INTERVAL 1 WEEK) < ?';
						$bindings[] = date('Y-m-d H:i:s');
					}
					if($this->input->post('age') == 'two-weeks') {
						$where[] = 'DATE_ADD(itm.itm_date, INTERVAL 2 WEEK) < ?';
						$bindings[] = date('Y-m-d H:i:s');
					}

					$sql = 'INSERT INTO '.$this->db->dbprefix('history').' (itm_id, mbr_id, hst_real, hst_datecreated)
					SELECT itm.itm_id AS itm_id, ? AS mbr_id, \'0\' AS hst_real, ? AS hst_datecreated
					FROM '.$this->db->dbprefix('items').' AS itm
					WHERE '.implode(' AND ', $where).'
					GROUP BY itm.itm_id';
					$query = $this->db->query($sql, $bindings);

					$content['modal'] = $this->load->view('home_history_confirm', $data, TRUE);
				}
			}

			if($type == 'toggle') {
				$query = $this->db->query('SELECT * FROM '.$this->db->dbprefix('history').' AS hst WHERE hst.itm_id = ? AND hst.mbr_id = ? GROUP BY hst.hst_id', array($id, $this->member->mbr_id));
				if($query->num_rows() > 0) {
					if(!$auto) {
						$this->db->where('itm_id', $id);
						$this->db->where('mbr_id', $this->member->mbr_id);
						$this->db->delete('history');
						$content['status'] = 'unread';
					} else {
						$content['status'] = 'read';
					}
				} else {
					$this->db->set('itm_id', $id);
					$this->db->set('mbr_id', $this->member->mbr_id);
					$this->db->set('hst_datecreated', date('Y-m-d H:i:s'));
					$this->db->insert('history');
					$content['status'] = 'read';
				}
				$content['itm_id'] = $id;
			}
			$content['mode'] = $type;
		} else {
			$this->output->set_status_header(403);
		}
		$this->reader_library->set_content($content);
	}
	public function shortcuts() {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$data = array();

		$content = array();

		if($this->input->is_ajax_request()) {
			$this->reader_library->set_template('_json');
			$this->reader_library->set_content_type('application/json');

			$content['modal'] = $this->load->view('home_shortcuts', $data, TRUE);
		} else {
			$this->output->set_status_header(403);
		}
		$this->reader_library->set_content($content);
	}
}

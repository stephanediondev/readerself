<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Items extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function get($mode, $id = FALSE) {
		if(!$this->session->userdata('mbr_id') && $mode != 'member') {
			redirect(base_url());
		}

		$modes = array('all', 'priority', 'geolocation', 'audio', 'starred', 'shared', 'nofolder', 'folder', 'subscription', 'category', 'author', 'search', 'cloud', 'member');
		$clouds = array('tags', 'authors');

		$content = array();
		$introduction_direction = false;
		$introduction_title = false;
		$introduction_actions = false;
		$introduction_details = false;

		$is_member = FALSE;
		if($mode == 'member') {
			$query = $this->db->query('SELECT mbr.* FROM '.$this->db->dbprefix('members').' AS mbr WHERE mbr.mbr_nickname = ? GROUP BY mbr.mbr_id', array($id));
			if($query->num_rows() > 0) {
				$is_member = $query->row();
			}
		}

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
			$content['nav']['items_refresh'] = true;
			$content['nav']['items_mode'] = true;
			$content['nav']['items_display'] = true;
			$content['nav']['items_read'] = true;
			$content['nav']['item_up'] = true;
			$content['nav']['item_down'] = true;

			if($mode == 'cloud' && in_array($id, $clouds)) {
				$content['result_type'] = 'cloud';

				$content['nav']['items_mode'] = false;
				$content['nav']['items_display'] = false;
				$content['nav']['items_read'] = false;
				$content['nav']['item_up'] = false;
				$content['nav']['item_down'] = false;

				$date_ref = date('Y-m-d H:i:s', time() - 3600 * 24 * 30);

				if($id == 'tags') {
					$introduction_title = '<i class="icon icon-tags"></i>'.$this->lang->line('tags').'*';
				}
				if($id == 'authors') {
					$introduction_title = '<i class="icon icon-pencil"></i>'.$this->lang->line('authors').'*';
				}
				$content['end'] = '<article class="neutral title">';
				$content['end'] .= '<p>*'.$this->lang->line('last_30_days').'</p>';
				$content['end'] .= '</article>';

				$items = array();

				$legend = array();
				$values = array();
				if($id == 'tags') {
					$query = $this->db->query('SELECT LOWER(cat.cat_title) AS ref, cat.cat_id AS id, COUNT(DISTINCT(itm.itm_id)) AS count FROM '.$this->db->dbprefix('items').' AS itm LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = itm.fed_id LEFT JOIN '.$this->db->dbprefix('categories').' AS cat ON cat.itm_id = itm.itm_id WHERE cat.cat_id IS NOT NULL AND cat.cat_datecreated >= ? AND sub.mbr_id = ? GROUP BY ref ORDER BY count DESC LIMIT 0,100', array($date_ref, $this->member->mbr_id));
				}
				if($id == 'authors') {
					$query = $this->db->query('SELECT LOWER(itm.itm_author) AS ref, itm.itm_id AS id, COUNT(DISTINCT(itm.itm_id)) AS count FROM '.$this->db->dbprefix('items').' AS itm LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = itm.fed_id WHERE itm.itm_author IS NOT NULL AND itm.itm_datecreated >= ? AND sub.mbr_id = ? GROUP BY ref ORDER BY count DESC LIMIT 0,100', array($date_ref, $this->member->mbr_id));
				}
				if($query->num_rows() > 0) {
					if($id == 'tags') {
						$exclude = array('non classé', 'uncategorized', 'actualités : informatique', 'actualités : internet', 'actualités : télécoms', 'actualités : it management');
					}
					if($id == 'authors') {
						$exclude = array('webmaster');
					}
					$max = false;
					foreach($query->result() as $row) {
						if(!in_array($row->ref, $exclude)) {
							if(!$max) {
								$max = $row->count;
							}
							$items[$row->ref] = array('count'=>$row->count, 'id'=>$row->id);
						}
					}
					ksort($items);
					$content['cloud'] = '<div id="cloud" class="neutral"><p>';
					foreach($items as $k => $v) {
						$percent = ($v['count'] * 100) / $max;
						$percent = $percent - ($percent % 10);
						$percent = intval($percent) + 100;
						if($id == 'tags') {
							$content['cloud'] .= '<a class="category" data-cat_id="'.$v['id'].'" href="'.base_url().'items/get/category/'.$v['id'].'" style="font-size:'.$percent.'%;">'.$k.'</a> ';
						}
						if($id == 'authors') {
							$content['cloud'] .= '<a class="author" data-itm_id="'.$v['id'].'" href="'.base_url().'items/get/author/'.$v['id'].'" style="font-size:'.$percent.'%;">'.$k.'</a> ';
						}
					}
					$content['cloud'] .= '</p></div>';
				} else {
					$content['cloud'] = '';
				}

			} else {
				$content['result_type'] = 'items';

				$content['items'] = array();

				$where = array();
				$bindings = array();

				if($is_member) {
					$introduction_title = '<i class="icon icon-user"></i>'.$is_member->mbr_nickname;
					$introduction_actions = '<ul class="actions">';
					if($this->config->item('social')) {
						$introduction_actions .= '<li class="hide-phone"><a target="_blank" href="https://www.facebook.com/sharer.php?u='.urlencode(base_url().'member/'.$member->mbr_nickname).'"><i class="icon icon-share"></i>Facebook</a></li>';
						$introduction_actions .= '<li class="hide-phone"><a target="_blank" href="https://plus.google.com/share?url='.urlencode(base_url().'member/'.$member->mbr_nickname).'"><i class="icon icon-share"></i>Google</a></li>';
						$introduction_actions .= '<li class="hide-phone"><a target="_blank" href="https://twitter.com/intent/tweet?source=webclient&amp;text='.urlencode($member->mbr_nickname.' - '.$this->config->item('title').' '.base_url().'member/'.$member->mbr_nickname).'"><i class="icon icon-share"></i>Twitter</a></li>';
					}
					$introduction_actions .= '<li class="hide-phone"><a href="'.base_url().'share/'.$member->token_share.'"><i class="icon icon-rss"></i>RSS</a></li>';
					$introduction_actions .= '</ul>';

					if($is_member->mbr_description) {
						$introduction_details = '<p>'.strip_tags($is_member->mbr_description).'</p>';
					}
					$where[] = 'itm.itm_id IN ( SELECT shr.itm_id FROM share AS shr WHERE shr.itm_id = itm.itm_id AND shr.mbr_id = ? )';
					$bindings[] = $is_member->mbr_id;

					$where[] = 'itm.fed_id IN ( SELECT sub.fed_id FROM subscriptions AS sub WHERE sub.fed_id = itm.fed_id AND sub.mbr_id = ? )';
					$bindings[] = $is_member->mbr_id;

				} else if($mode == 'priority') {
					$introduction_title = '<i class="icon icon-flag"></i>'.$this->lang->line('priority_items').' (<span id="intro-load-priority-items"></span>)';
					$where[] = 'itm.fed_id IN ( SELECT sub.fed_id FROM subscriptions AS sub WHERE sub.fed_id = itm.fed_id AND sub.mbr_id = ? AND sub.sub_priority = ? )';
					$bindings[] = $this->member->mbr_id;
					$bindings[] = 1;

				} else {
					$introduction_title = '<i class="icon icon-asterisk"></i>'.$this->lang->line('all_items').' (<span id="intro-load-all-items"></span>)';
					$where[] = 'itm.fed_id IN ( SELECT sub.fed_id FROM subscriptions AS sub WHERE sub.fed_id = itm.fed_id AND sub.mbr_id = ? )';
					$bindings[] = $this->member->mbr_id;
				}

				if($mode == 'geolocation') {
					$introduction_title = '<i class="icon icon-map-marker"></i>'.$this->lang->line('geolocation_items').' (<span id="intro-load-geolocation-items"></span>)';
					$where[] = 'itm.itm_latitude IS NOT NULL';
					$where[] = 'itm.itm_longitude IS NOT NULL';
				}

				if($mode == 'audio') {
					$introduction_title = '<i class="icon icon-volume-up"></i>'.$this->lang->line('audio_items').' (<span id="intro-load-audio-items"></span>)';
					$where[] = 'enr.enr_type LIKE ?';
					$bindings[] = 'audio/%';
				}

				if($mode == 'starred') {
					$introduction_title = '<i class="icon icon-star"></i>'.$this->lang->line('starred_items').' {<span id="intro-load-starred-items"></span>}';
					$introduction_actions = '<ul class="actions"><li><a href="'.base_url().'starred/import"><i class="icon icon-download-alt"></i>'.$this->lang->line('import').'</a></li></ul>';
					$where[] = 'itm.itm_id IN ( SELECT fav.itm_id FROM favorites AS fav WHERE fav.itm_id = itm.itm_id AND fav.mbr_id = ? )';
					$bindings[] = $this->member->mbr_id;

					$content['nav']['items_mode'] = false;
					$content['nav']['items_read'] = false;

				} else if($mode == 'shared') {
					$introduction_title = '<i class="icon icon-heart"></i>'.$this->lang->line('shared_items').' {<span id="intro-load-shared-items"></span>}';
					if($this->member->mbr_nickname) {
						$introduction_actions = '<ul class="actions"><li class="hide-phone"><a href="'.base_url().'member/'.$this->member->mbr_nickname.'"><i class="icon icon-unlock"></i>'.$this->lang->line('public_profile').'</a></li></ul>';
					} else {
						$introduction_actions = '<ul class="actions"><li class="hide-phone"><a target="_blank" href="'.base_url().'share/'.$this->member->token_share.'"><i class="icon icon-rss"></i>RSS</a></li></ul>';
					}
					$where[] = 'itm.itm_id IN ( SELECT shr.itm_id FROM share AS shr WHERE shr.itm_id = itm.itm_id AND shr.mbr_id = ? )';
					$bindings[] = $this->member->mbr_id;

					$content['nav']['items_mode'] = false;
					$content['nav']['items_read'] = false;

				} else {
					if($mode == 'search') {
						$search = trim(urldecode($id));
						$words = explode(' ', $search);
						$word_or = array();
						foreach($words as $word) {
							if(substr($word, 0, 1) == '@') {
								$where[] = 'DATE_ADD(itm.itm_date, INTERVAL ? HOUR) LIKE ?';
								$bindings[] = $this->session->userdata('timezone');
								$bindings[] = substr($word, 1).'%';
							} else {
								$word_or[] = 'itm.itm_title LIKE ?';
								$bindings[] = '%'.$word.'%';

								$word_or[] = 'itm.itm_author LIKE ?';
								$bindings[] = '%'.$word.'%';

								$word_or[] = 'itm.itm_id IN ( SELECT cat.itm_id FROM categories AS cat WHERE cat.cat_title LIKE ? )';
								$bindings[] = '%'.$word.'%';
							}
						}
						$where[] = '('.implode(' OR ', $word_or).')';
						$content['nav']['items_refresh'] = false;
						$content['nav']['items_mode'] = false;
						$content['nav']['items_read'] = false;

					} else if($this->input->get('items_mode') == 'unread_only') {
						$where[] = 'itm.itm_id NOT IN ( SELECT hst.itm_id FROM history AS hst WHERE hst.itm_id = itm.itm_id AND hst.mbr_id = ? )';
						$bindings[] = $this->member->mbr_id;
					}
				}

				if($is_folder) {
					$introduction_direction = $is_folder->flr_direction;
					$introduction_title = '<i class="icon icon-folder-close"></i>'.$is_folder->flr_title.' (<span id="intro-load-folder-'.$is_folder->flr_id.'-items">0</span>)';
					$where[] = 'itm.fed_id IN ( SELECT sub.fed_id FROM subscriptions AS sub WHERE sub.fed_id = itm.fed_id AND sub.flr_id = ? )';
					$bindings[] = $is_folder->flr_id;
				}

				if($is_subscription) {
					$introduction_direction = $is_subscription->sub_direction;
					$introduction_title = '<i class="icon icon-rss"></i>'.$is_subscription->fed_title.' (<span id="intro-load-sub-'.$is_subscription->sub_id.'-items">0</span>)';
					$introduction_actions = '<ul class="actions"><li><a class="priority" href="'.base_url().'subscriptions/priority/'.$is_subscription->sub_id.'"><span class="priority"';
					if($is_subscription->sub_priority == 0) {
						$introduction_actions .= ' style="display:none;"';
					}
					$introduction_actions .= '><i class="icon icon-flag"></i>'.$this->lang->line('not_priority').'</span><span class="not_priority"';
					if($is_subscription->sub_priority == 1) {
						$introduction_actions .= ' style="display:none;"';
					}
					$introduction_actions .= '><i class="icon icon-flag-alt"></i>'.$this->lang->line('priority').'</span></a></li></ul>';
					if($is_subscription->fed_url) {
						$introduction_details = '<ul class="item-details"><li><a target="_blank" href="'.$is_subscription->fed_url.'"><i class="icon icon-external-link"></i>'.$is_subscription->fed_url.'</a></li></ul>';
					}
					$where[] = 'itm.fed_id IN ( SELECT sub.fed_id FROM subscriptions AS sub WHERE sub.fed_id = itm.fed_id AND sub.sub_id = ? )';
					$bindings[] = $is_subscription->sub_id;
				}

				if($is_author) {
					$introduction_title = '<i class="icon icon-pencil"></i>'.$is_author.' (<span id="intro-load-author-items">0</span>)';
					$where[] = 'itm.itm_author = ?';
					$bindings[] = $is_author;
				}

				if($is_category) {
					$introduction_title = '<i class="icon icon-tag"></i>'.$is_category.' (<span id="intro-load-category-items">0</span>)';
					$where[] = 'itm.itm_id IN ( SELECT cat.itm_id FROM categories AS cat WHERE cat.cat_title = ? )';
					$bindings[] = $is_category;
				}

				if($mode == 'nofolder') {
					$introduction_title = '<i class="icon icon-folder-close"></i><em>'.$this->lang->line('no_folder').'</em> (<span id="intro-load-nofolder-items">0</span>)';
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
				FROM items AS itm ';
				if($mode == 'audio') {
					$sql .= 'LEFT JOIN '.$this->db->dbprefix('enclosures').' AS enr ON enr.itm_id = itm.itm_id ';
				}
				$sql .= 'WHERE '.implode(' AND ', $where).'
				GROUP BY itm.itm_id
				ORDER BY itm.itm_date DESC';
				if($mode == 'starred') {
					$sql .= ' LIMIT '.intval($this->input->post('pagination')).',10';
				} else if($mode == 'shared') {
					$sql .= ' LIMIT '.intval($this->input->post('pagination')).',10';
				} else {
					if($mode == 'search') {
						$sql .= ' LIMIT '.intval($this->input->post('pagination')).',10';
					} else if($this->input->get('items_mode') == 'unread_only') {
						$sql .= ' LIMIT 0,10';
					} else {
						$sql .= ' LIMIT '.intval($this->input->post('pagination')).',10';
					}
				}
				$query = $this->db->query($sql, $bindings);
				$content['total'] = $query->num_rows();

				if($query->num_rows() > 0) {
					foreach($query->result() as $itm) {
						$sql = 'SELECT sub.sub_id, IF(sub.sub_title IS NOT NULL, sub.sub_title, fed.fed_title) AS title, IF(sub.sub_direction IS NOT NULL, sub.sub_direction, fed.fed_direction) AS direction, flr.flr_id, flr.flr_title, flr.flr_direction FROM '.$this->db->dbprefix('subscriptions').' AS sub LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = sub.fed_id LEFT JOIN '.$this->db->dbprefix('folders').' AS flr ON flr.flr_id = sub.flr_id WHERE sub.fed_id = ? AND sub.mbr_id = ? GROUP BY sub.sub_id';
						if($is_member) {
							$itm->sub = $this->db->query($sql, array($itm->fed_id, $is_member->mbr_id))->row();
						} else {
							$itm->sub = $this->db->query($sql, array($itm->fed_id, $this->member->mbr_id))->row();
						}

						$itm->foursquare = false;

						$itm->categories = false;
						if($this->config->item('tags')) {
							$categories = $this->db->query('SELECT cat.* FROM categories AS cat WHERE cat.itm_id = ? GROUP BY cat.cat_id', array($itm->itm_id))->result();
							if($categories) {
								$itm->categories = array();
								foreach($categories as $cat) {
									if(substr($cat->cat_title, 0, 17) == 'foursquare:venue=') {
										$itm->foursquare = substr($cat->cat_title, 17);
									} else {
										if($is_member) {
											$itm->categories[] = $cat->cat_title;
										} else {
											$itm->categories[] = '<a class="category" data-cat_id="'.$cat->cat_id.'" href="'.base_url().'items/get/category/'.$cat->cat_id.'">'.$cat->cat_title.'</a>';
										}
									}
								}
							}
						}

						$sql = 'SELECT enr.* FROM enclosures AS enr WHERE enr.itm_id = ? GROUP BY enr.enr_id ORDER BY enr.enr_type ASC';
						$itm->enclosures = $this->db->query($sql, array($itm->itm_id))->result();

						if(!$is_member) {
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
						}

						list($itm->explode_date, $itm->explode_time) = explode(' ', $itm->itm_date);

						$itm->itm_content = $this->reader_library->prepare_content($itm->itm_content);

						$content['items'][$itm->itm_id] = array('itm_id' => $itm->itm_id, 'itm_content' => $this->load->view('item', array('itm'=>$itm, 'mode'=>$mode), TRUE));
					}
				} else {
					$lastcrawl = $this->db->query('SELECT DATE_ADD(crr.crr_datecreated, INTERVAL ? HOUR) AS crr_datecreated FROM '.$this->db->dbprefix('crawler').' AS crr GROUP BY crr.crr_id ORDER BY crr.crr_id DESC LIMIT 0,1', array($this->session->userdata('timezone')))->row();
					if($lastcrawl && $mode != 'member') {
						$content['end'] = '<article id="last_crawl" class="neutral title">';
						$content['end'] .= '</article>';
					} else if($mode == 'member') {
						$content['end'] = '<article class="neutral title">';
						$content['end'] .= '<p><i class="icon icon-check"></i>'.$this->lang->line('no_more_items').'</p>';
						$content['end'] .= '</article>';
					}
				}
			}

			if($introduction_title) {
				if($introduction_direction) {
					$content['begin'] = '<article dir="'.$introduction_direction.'" id="introduction" class="neutral title">';
				} else {
					$content['begin'] = '<article id="introduction" class="neutral title">';
				}
				if($introduction_actions) {
					$content['begin'] .= $introduction_actions;
				}
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
	public function read() {
		if(!$this->session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$type = 'dialog';

		$data = array();

		$content = array();

		if($this->input->is_ajax_request()) {
			$this->reader_library->set_template('_json');
			$this->reader_library->set_content_type('application/json');

			if($this->session->userdata('items-mode')) {
				$this->load->library(array('form_validation'));

				if($this->session->userdata('items-mode') == 'priority') {
					$data['title'] = $this->lang->line('priority_items');
					$data['icon'] = 'flag';
					$data['count'] = $this->reader_model->count_unread('priority');
				} else {
					$data['title'] = $this->lang->line('all_items');
					$data['icon'] = 'asterisk';
					$data['count'] = $this->reader_model->count_unread('all');
				}

				if($this->session->userdata('items-mode') == 'geolocation') {
					$data['title'] = $this->lang->line('geolocation_items');
					$data['icon'] = 'map-marker';
					$data['count'] = $this->reader_model->count_unread('geolocation');
				}

				if($this->session->userdata('items-mode') == 'audio') {
					$data['title'] = $this->lang->line('audio_items');
					$data['icon'] = 'volume-up';
					$data['count'] = $this->reader_model->count_unread('audio');
				}

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

				$is_author = FALSE;
				if($this->session->userdata('items-mode') == 'author') {
					$query = $this->db->query('SELECT itm.itm_author FROM '.$this->db->dbprefix('items').' AS itm WHERE itm.itm_id = ? GROUP BY itm.itm_id', array($this->session->userdata('items-id')));
					if($query->num_rows() > 0) {
						$is_author = $query->row()->itm_author;
						$data['title'] = $is_author;
						$data['icon'] = 'user';
						$data['count'] = $this->reader_model->count_unread('author', $is_author);
					}
				}

				$is_category = FALSE;
				if($this->session->userdata('items-mode') == 'category') {
					$query = $this->db->query('SELECT cat.cat_title FROM '.$this->db->dbprefix('categories').' AS cat WHERE cat.cat_id = ? GROUP BY cat.cat_id', array($this->session->userdata('items-id')));
					if($query->num_rows() > 0) {
						$is_category = $query->row()->cat_title;
						$data['title'] = $is_category;
						$data['icon'] = 'tag';
						$data['count'] = $this->reader_model->count_unread('category', $is_category);
					}
				}

				if($this->session->userdata('items-mode') == 'nofolder') {
					$data['title'] = '<em>'.$this->lang->line('no_folder').'</em>';
					$data['icon'] = 'folder-close';
					$data['count'] = $this->reader_model->count_unread('nofolder');
				}

				$this->form_validation->set_rules('age', 'lang:age', 'required');

				if($this->form_validation->run() == FALSE) {
					$content['modal'] = $this->load->view('items_read', $data, TRUE);
				} else {
					$where = array();
					$bindings = array();

					$bindings[] = $this->member->mbr_id;
					$bindings[] = date('Y-m-d H:i:s');

					if($this->session->userdata('items-mode') == 'priority') {
						$where[] = 'itm.fed_id IN ( SELECT sub.fed_id FROM subscriptions AS sub WHERE sub.fed_id = itm.fed_id AND sub.mbr_id = ? AND sub.sub_priority = ? )';
						$bindings[] = $this->member->mbr_id;
						$bindings[] = 1;
					} else {
						$where[] = 'itm.fed_id IN ( SELECT sub.fed_id FROM subscriptions AS sub WHERE sub.fed_id = itm.fed_id AND sub.mbr_id = ? )';
						$bindings[] = $this->member->mbr_id;
					}

					if($this->session->userdata('items-mode') == 'geolocation') {
						$where[] = 'itm.itm_latitude IS NOT NULL';
						$where[] = 'itm.itm_longitude IS NOT NULL';
					}

					if($this->session->userdata('items-mode') == 'audio') {
						$where[] = 'enr.enr_type LIKE ?';
						$bindings[] = 'audio/%';
					}

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

					if($is_author) {
						$where[] = 'itm.itm_author = ?';
						$bindings[] = $is_author;
					}

					if($is_category) {
						$where[] = 'itm.itm_id IN ( SELECT cat.itm_id FROM categories AS cat WHERE cat.cat_title = ? )';
						$bindings[] = $is_category;
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
					FROM '.$this->db->dbprefix('items').' AS itm ';
					if($this->session->userdata('items-mode') == 'audio') {
						$sql .= 'LEFT JOIN '.$this->db->dbprefix('enclosures').' AS enr ON enr.itm_id = itm.itm_id ';
					}
					$sql .= 'WHERE '.implode(' AND ', $where).'
					GROUP BY itm.itm_id';
					$query = $this->db->query($sql, $bindings);

					$content['modal'] = $this->load->view('items_read_confirm', $data, TRUE);
				}
			}
			$content['mode'] = $type;
		} else {
			$this->output->set_status_header(403);
		}
		$this->reader_library->set_content($content);
	}
}

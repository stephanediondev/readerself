<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Refresh extends CI_Controller {
	public function client() {
		$this->reader_library->set_template('_json');
		$this->reader_library->set_content_type('application/json');

		if($this->input->is_ajax_request()) {
			$content = array();

			$content['count']['all'] = $this->reader_model->count_unread('all');

			$content['count']['priority'] = $this->reader_model->count_unread('priority');

			if($this->config->item('menu_geolocation_items')) {
				$content['count']['geolocation'] = $this->reader_model->count_unread('geolocation');
			}

			if($this->config->item('folders')) {
				$sql = 'SELECT flr.flr_id, COUNT(DISTINCT(itm.itm_id)) AS count
				FROM '.$this->db->dbprefix('folders').' AS flr
				LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.flr_id = flr.flr_id AND sub.mbr_id = ?
				LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.fed_id = sub.fed_id AND itm.itm_id NOT IN (SELECT hst.itm_id FROM '.$this->db->dbprefix('history').' AS hst WHERE hst.mbr_id = ?)
				GROUP BY flr.flr_id';
				$query = $this->db->query($sql, array($this->member->mbr_id, $this->member->mbr_id));

				if($query->num_rows() > 0) {
					foreach($query->result() as $flr) {
						$content['count']['folder-'.$flr->flr_id] = $flr->count;
					}
				}

				$content['count']['nofolder'] = $this->reader_model->count_unread('nofolder');
			}

			if($this->input->post('subscriptions')) {
				$in = explode(',', $this->input->post('subscriptions'));
				$out = array();
				foreach($in as $sub_id) {
					if(is_numeric($sub_id)) {
						$out[] = $sub_id;
					}
				}
				if(count($out) > 0) {
					$sql = 'SELECT sub.sub_id, COUNT(DISTINCT(itm.itm_id)) AS count
					FROM '.$this->db->dbprefix('subscriptions').' AS sub
					LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.fed_id = sub.fed_id AND itm.itm_id NOT IN (SELECT hst.itm_id FROM '.$this->db->dbprefix('history').' AS hst WHERE hst.mbr_id = ?)
					WHERE sub.mbr_id = ? AND sub.sub_id IN('.implode(',', $out).') GROUP BY sub.sub_id';
					$query = $this->db->query($sql, array($this->member->mbr_id, $this->member->mbr_id));

					if($query->num_rows() > 0) {
						foreach($query->result() as $sub) {
							$content['count']['sub-'.$sub->sub_id] = $sub->count;
						}
					}
				}
			}

			if($this->config->item('star')) {
				$sql = 'SELECT COUNT(DISTINCT(fav.fav_id)) AS count
				FROM '.$this->db->dbprefix('favorites').' AS fav
				WHERE fav.mbr_id = ?';
				$query = $this->db->query($sql, array($this->member->mbr_id, $this->member->mbr_id));

				$content['count']['starred'] = $query->row()->count;
			}

			if($this->config->item('share')) {
				$sql = 'SELECT COUNT(DISTINCT(shr.shr_id)) AS count
				FROM '.$this->db->dbprefix('share').' AS shr
				WHERE shr.mbr_id = ?';
				$query = $this->db->query($sql, array($this->member->mbr_id, $this->member->mbr_id));

				$content['count']['shared'] = $query->row()->count;
			}

			if($this->session->userdata('items-mode') == 'author') {
				$query = $this->db->query('SELECT itm.itm_author FROM '.$this->db->dbprefix('items').' AS itm WHERE itm.itm_id = ? GROUP BY itm.itm_id', array($this->session->userdata('items-id')));
				if($query->num_rows() > 0) {
					$is_author = $query->row()->itm_author;
					$content['count']['author'] = $this->reader_model->count_unread('author', $is_author);
				}
			}

			if($this->config->item('tags')) {
				if($this->session->userdata('items-mode') == 'category') {
					$query = $this->db->query('SELECT cat.cat_title FROM '.$this->db->dbprefix('categories').' AS cat WHERE cat.cat_id = ? GROUP BY cat.cat_id', array($this->session->userdata('items-id')));
					if($query->num_rows() > 0) {
						$is_category = $query->row()->cat_title;
						$content['count']['category'] = $this->reader_model->count_unread('category', $is_category);
					}
				}
			}

			if($this->session->userdata('mbr_id')) {
				$content['is_logged'] = TRUE;
			} else {
				$content['is_logged'] = FALSE;
			}

			if($this->input->post('last_crawl')) {
				$lastcrawl = $this->db->query('SELECT crr.crr_count, crr.crr_time, crr.crr_memory, DATE_ADD(crr.crr_datecreated, INTERVAL ? HOUR) AS crr_datecreated FROM '.$this->db->dbprefix('crawler').' AS crr GROUP BY crr.crr_id ORDER BY crr.crr_id DESC LIMIT 0,1', array($this->session->userdata('timezone')))->row();
				if($lastcrawl) {
					list($date, $time) = explode(' ', $lastcrawl->crr_datecreated);
					$content['last_crawl'] = '<h2><i class="icon icon-truck"></i>'.$this->lang->line('last_crawl').'</h2>';
					$content['last_crawl'] .= '<ul class="item-details">';
					$content['last_crawl'] .= '<li><i class="icon icon-calendar"></i>'.$date.'</li>';
					$content['last_crawl'] .= '<li><i class="icon icon-time"></i>'.$time.' (<span class="timeago" title="'.$lastcrawl->crr_datecreated.'"></span>)</li>';
					$content['last_crawl'] .= '<li class="block"><i class="icon icon-rss"></i>'.intval($lastcrawl->crr_count).' '.mb_strtolower($this->lang->line('subscriptions')).'</li>';
					$content['last_crawl'] .= '<li class="block"><i class="icon icon-rocket"></i>'.intval($lastcrawl->crr_time).' secondes</li>';
					$content['last_crawl'] .= '<li class="block"><i class="icon icon-leaf"></i>'.number_format($lastcrawl->crr_memory, 0, '.', ' ').' bytes</li>';
					$content['last_crawl'] .= '</ul>';
				} else {
					$content['last_crawl'] = false;
				}
			} else {
				$content['last_crawl'] = false;
			}

			$this->reader_library->set_content($content);
		} else {
			$this->output->set_status_header(403);
		}
	}
	public function items() {
		$this->reader_library->set_template('_plain');
		$this->reader_library->set_content_type('text/plain');

		$content = '';

		include_once('thirdparty/simplepie/autoloader.php');
		include_once('thirdparty/simplepie/idn/idna_convert.class.php');

		$query = $this->db->query('SELECT fed.* FROM '.$this->db->dbprefix('feeds').' AS fed WHERE fed.fed_nextcrawl IS NULL OR fed.fed_nextcrawl <= ? GROUP BY fed.fed_id HAVING (SELECT COUNT(DISTINCT(sub.mbr_id)) FROM '.$this->db->dbprefix('subscriptions').' AS sub WHERE sub.fed_id = fed.fed_id) > 0', array(date('Y-m-d H:i:s')));
		if($query->num_rows() > 0) {

			$microtime_start = microtime(1);

			foreach($query->result() as $fed) {
				$sp_feed = new SimplePie();
				$sp_feed->set_feed_url(convert_to_ascii($fed->fed_link));
				$sp_feed->enable_cache(false);
				$sp_feed->set_timeout(5);
				$sp_feed->force_feed(true);
				$sp_feed->init();
				$sp_feed->handle_content_type();

				if($sp_feed->error()) {
					$this->db->set('fed_lasterror', $sp_feed->error());
					$this->db->set('fed_lastcrawl', date('Y-m-d H:i:s'));
					$this->db->where('fed_id', $fed->fed_id);
					$this->db->update('feeds');
				} else {
					$this->reader_library->crawl_items($fed->fed_id, $sp_feed->get_items());

					$lastitem = $this->db->query('SELECT itm.itm_datecreated FROM '.$this->db->dbprefix('items').' AS itm WHERE itm.fed_id = ? GROUP BY itm.itm_id ORDER BY itm.itm_id DESC LIMIT 0,1', array($fed->fed_id))->row();

					$this->db->set('fed_title', $sp_feed->get_title());
					$this->db->set('fed_url', $sp_feed->get_link());
					$this->db->set('fed_link', $sp_feed->subscribe_url());
					if($sp_feed->get_image_url()) {
						$this->db->set('fed_image', $sp_feed->get_image_url());
					}
					$this->db->set('fed_description', $sp_feed->get_description());
					$this->db->set('fed_lasterror', '');
					$this->db->set('fed_lastcrawl', date('Y-m-d H:i:s'));
					if($lastitem) {
						$nextcrawl = '';
						//older than 96 hours, next crawl in 12 hours
						if($lastitem->itm_datecreated < date('Y-m-d H:i:s', time() - 3600 * 24 * 96)) {
							$nextcrawl = date('Y-m-d H:i:s', time() + 3600 * 12);

						//older than 48 hours, next crawl in 6 hours
						} else if($lastitem->itm_datecreated < date('Y-m-d H:i:s', time() - 3600 * 48)) {
							$nextcrawl = date('Y-m-d H:i:s', time() + 3600 * 6);

						//older than 24 hours, next crawl in 3 hours
						} else if($lastitem->itm_datecreated < date('Y-m-d H:i:s', time() - 3600 * 24)) {
							$nextcrawl = date('Y-m-d H:i:s', time() + 3600 * 3);
						}
						$this->db->set('fed_nextcrawl', $nextcrawl);
					}
					$this->db->where('fed_id', $fed->fed_id);
					$this->db->update('feeds');
				}

				$sp_feed->__destruct();
				unset($sp_feed);
			}

			$this->db->set('crr_time', microtime(1) - $microtime_start);
			if(function_exists('memory_get_peak_usage')) {
				$this->db->set('crr_memory', memory_get_peak_usage());
			}
			$this->db->set('crr_count', $query->num_rows());
			$this->db->set('crr_datecreated', date('Y-m-d H:i:s'));
			$this->db->insert('crawler');

			$this->db->query('OPTIMIZE TABLE categories, connections, enclosures, favorites, feeds, folders, history, items, members, share, subscriptions');
		}
		$this->reader_library->set_content($content);
	}
}

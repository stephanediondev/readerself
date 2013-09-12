<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Refresh extends CI_Controller {
	public function client() {
		$this->reader_library->set_template('_json');
		$this->reader_library->set_content_type('application/json');

		if($this->input->is_ajax_request()) {
			$content = array();

			$sql = 'SELECT COUNT(DISTINCT(itm.itm_id)) AS count
			FROM '.$this->db->dbprefix('subscriptions').' AS sub
			LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.fed_id = sub.fed_id
			LEFT JOIN '.$this->db->dbprefix('history').' AS hst ON hst.itm_id = itm.itm_id AND hst.mbr_id = ?
			WHERE hst.hst_id IS NULL AND sub.mbr_id = ?';
			$query = $this->db->query($sql, array($this->member->mbr_id, $this->member->mbr_id));

			$content['count']['all'] = $query->row()->count;

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

				$sql = 'SELECT COUNT(DISTINCT(itm.itm_id)) AS count
				FROM '.$this->db->dbprefix('subscriptions').' AS sub
				LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.fed_id = sub.fed_id AND itm.itm_id NOT IN (SELECT hst.itm_id FROM '.$this->db->dbprefix('history').' AS hst WHERE hst.mbr_id = ?)
				WHERE sub.flr_id IS NULL AND sub.mbr_id = ?';
				$query = $this->db->query($sql, array($this->member->mbr_id, $this->member->mbr_id));

				$content['count']['nofolder'] = $query->row()->count;
			}

			$sql = 'SELECT sub.sub_id, COUNT(DISTINCT(itm.itm_id)) AS count
			FROM '.$this->db->dbprefix('subscriptions').' AS sub
			LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.fed_id = sub.fed_id AND itm.itm_id NOT IN (SELECT hst.itm_id FROM '.$this->db->dbprefix('history').' AS hst WHERE hst.mbr_id = ?)
			WHERE sub.mbr_id = ? GROUP BY sub.sub_id';
			$query = $this->db->query($sql, array($this->member->mbr_id, $this->member->mbr_id));

			if($query->num_rows() > 0) {
				foreach($query->result() as $sub) {
					$content['count']['sub-'.$sub->sub_id] = $sub->count;
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

					$sql = 'SELECT COUNT(DISTINCT(itm.itm_id)) AS count
					FROM '.$this->db->dbprefix('items').' AS itm
					LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = itm.fed_id AND sub.mbr_id = ?
					WHERE itm.itm_id NOT IN (SELECT hst.itm_id FROM '.$this->db->dbprefix('history').' AS hst WHERE hst.mbr_id = ?) AND itm.itm_author = ?';
					$query = $this->db->query($sql, array($this->member->mbr_id, $this->member->mbr_id, $is_author));

					$content['count']['author'] = $query->row()->count;
				}
			}

			if($this->config->item('tags')) {
				if($this->session->userdata('items-mode') == 'category') {
					$query = $this->db->query('SELECT cat.cat_title FROM '.$this->db->dbprefix('categories').' AS cat WHERE cat.cat_id = ? GROUP BY cat.cat_id', array($this->session->userdata('items-id')));
					if($query->num_rows() > 0) {
						$is_category = $query->row()->cat_title;

						$sql = 'SELECT COUNT(DISTINCT(itm.itm_id)) AS count
						FROM '.$this->db->dbprefix('items').' AS itm
						LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = itm.fed_id AND sub.mbr_id = ?
						WHERE itm.itm_id NOT IN (SELECT hst.itm_id FROM '.$this->db->dbprefix('history').' AS hst WHERE hst.mbr_id = ?) AND itm.itm_id IN ( SELECT cat.itm_id FROM categories AS cat WHERE cat.cat_title = ? )';
						$query = $this->db->query($sql, array($this->member->mbr_id, $this->member->mbr_id, $is_category));

						$content['count']['category'] = $query->row()->count;
					}
				}
			}

			if($this->session->userdata('mbr_id')) {
				$content['is_logged'] = TRUE;
			} else {
				$content['is_logged'] = FALSE;
			}

			$lastcrawl = $this->db->query('SELECT DATE_ADD(crr.crr_datecreated, INTERVAL ? HOUR) AS crr_datecreated FROM '.$this->db->dbprefix('crawler').' AS crr GROUP BY crr.crr_id ORDER BY crr.crr_id DESC LIMIT 0,1', array($this->session->userdata('timezone')))->row();
			if($lastcrawl) {
				list($date, $time) = explode(' ', $lastcrawl->crr_datecreated);
				$content['last_crawl'] = '<h2><i class="icon icon-truck"></i>'.$this->lang->line('last_crawl').'</h2><ul class="item-details"><li><i class="icon icon-calendar"></i>'.$date.'</li><li><i class="icon icon-time"></i>'.$time.' (<span class="timeago" title="'.$lastcrawl->crr_datecreated.'"></span>)</li></ul>';
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
			$this->db->set('crr_count', $query->num_rows());
			$this->db->set('crr_datecreated', date('Y-m-d H:i:s'));
			$this->db->insert('crawler');

			$this->db->query('OPTIMIZE TABLE categories, connections, enclosures, favorites, feeds, folders, history, items, members, share, subscriptions');
		}
		$this->reader_library->set_content($content);
	}
}

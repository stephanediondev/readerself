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

			if($this->session->userdata('logged_member')) {
				$content['is_logged'] = TRUE;
			} else {
				$content['is_logged'] = FALSE;
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

		$query = $this->db->query('SELECT fed.* FROM '.$this->db->dbprefix('feeds').' AS fed GROUP BY fed.fed_id HAVING (SELECT COUNT(DISTINCT(sub.mbr_id)) FROM '.$this->db->dbprefix('subscriptions').' AS sub WHERE sub.fed_id = fed.fed_id) > 0');
		if($query->num_rows() > 0) {
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
				} else {
					$this->db->set('fed_title', $sp_feed->get_title());
					$this->db->set('fed_url', $sp_feed->get_link());
					$this->db->set('fed_link', $sp_feed->subscribe_url());
					if($sp_feed->get_image_url()) {
						$this->db->set('fed_image', $sp_feed->get_image_url());
					}
					$this->db->set('fed_description', $sp_feed->get_description());
					$this->db->set('fed_lasterror', '');
				}
				$this->db->where('fed_id', $fed->fed_id);
				$this->db->update('feeds');

				foreach($sp_feed->get_items() as $sp_item) {
					$query = $this->db->query('SELECT * FROM '.$this->db->dbprefix('items').' AS itm WHERE itm.itm_link = ? GROUP BY itm.itm_id', array($sp_item->get_link()));
					if($query->num_rows() == 0) {
						$this->db->set('fed_id', $fed->fed_id);

						if($sp_item->get_title()) {
							$this->db->set('itm_title', $sp_item->get_title());
						} else {
							$this->db->set('itm_title', '-');
						}

						if($author = $sp_item->get_author()) {
							$this->db->set('itm_author', $author->get_name());
						}

						$this->db->set('itm_link', $sp_item->get_link());

						if($sp_item->get_content()) {
							$this->db->set('itm_content', $sp_item->get_content());
						} else {
							$this->db->set('itm_content', '-');
						}

						$sp_itm_date = $sp_item->get_gmdate('Y-m-d H:i:s');
						if($sp_itm_date) {
							$this->db->set('itm_date', $sp_itm_date);
						} else {
							$this->db->set('itm_date', date('Y-m-d H:i:s'));
						}

						$this->db->set('itm_datecreated', date('Y-m-d H:i:s'));

						$this->db->insert('items');

						$itm_id = $this->db->insert_id();

						foreach($sp_item->get_enclosures() as $enclosure) {
							if($enclosure->get_link() && $enclosure->get_type()) {
								$this->db->set('itm_id', $itm_id);
								$this->db->set('enr_link', $enclosure->get_link());
								$this->db->set('enr_type', $enclosure->get_type());
								$this->db->set('enr_length', $enclosure->get_length());
								$this->db->set('enr_datecreated', date('Y-m-d H:i:s'));
								$this->db->insert('enclosures');
							}
						}
					} else {
						break;
					}
					unset($sp_item);
				}
				$sp_feed->__destruct();
				unset($feed);
			}
		}
		$this->reader_library->set_content($content);
	}
}

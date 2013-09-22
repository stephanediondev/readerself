<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reader_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}
	function login($email, $password) {
		$this->session->unset_userdata('mbr_id');
		$query = $this->db->query('SELECT mbr.* FROM '.$this->db->dbprefix('members').' AS mbr WHERE mbr.mbr_email = ? GROUP BY mbr.mbr_id', array($email));
		if($query->num_rows() > 0) {
			$member = $query->row();
			if($this->reader_library->set_salt_password($password) == $member->mbr_password) {
				$this->connect($member->mbr_id);

				return TRUE;
			}
		}
		return FALSE;
	}
	function connect($mbr_id) {
		$token_connection = sha1(uniqid($mbr_id, 1).mt_rand());
		$this->db->set('mbr_id', $mbr_id);
		$this->db->set('token_connection', $token_connection);
		$this->db->set('cnt_ip', $this->input->ip_address());
		$this->db->set('cnt_agent', $this->input->user_agent());
		$this->db->set('cnt_datecreated', date('Y-m-d H:i:s'));
		$this->db->insert('connections');

		$this->session->set_userdata('mbr_id', $mbr_id);
		$this->input->set_cookie('token_connection', $token_connection, 3600 * 24 * 30, NULL, '/', NULL, NULL);
	}
	function logout() {
		if($this->session->userdata('mbr_id') && $this->input->cookie('token_connection')) {
			$this->db->where('token_connection', $this->input->cookie('token_connection'));
			$this->db->where('mbr_id', $this->session->userdata('mbr_id'));
			$this->db->delete('connections');
		}

		$this->session->unset_userdata('mbr_id');
		$this->input->set_cookie('token_connection', NULL, 0, NULL, '/', NULL, NULL);
	}
	function get($mbr_id) {
		$member = false;
		$query = $this->db->query('SELECT mbr.* FROM '.$this->db->dbprefix('members').' AS mbr WHERE mbr.mbr_id = ? GROUP BY mbr.mbr_id', array($mbr_id));
		if($query->num_rows() > 0) {
			$member = $query->row();

			if(!$member->token_share) {
				$member->token_share = sha1(uniqid($member->mbr_id, 1).mt_rand());
				$this->db->set('token_share', $member->token_share);
				$this->db->where('mbr_id', $member->mbr_id);
				$this->db->update('members');
			}

			$query = $this->db->query('SELECT cnt.* FROM '.$this->db->dbprefix('connections').' AS cnt WHERE cnt.mbr_id = ? AND token_connection IS NOT NULL AND token_connection = ? GROUP BY cnt.cnt_id', array($mbr_id, $this->input->cookie('token_connection')));
			if($query->num_rows() > 0) {
				$member->token_connection = $query->row()->token_connection;
			} else {
				$member->token_connection = false;
			}
		}
		return $member;
	}
	function count_members() {
		return $this->db->query('SELECT COUNT(DISTINCT(mbr.mbr_id)) AS count FROM '.$this->db->dbprefix('members').' AS mbr')->row()->count;
	}

	function get_subscriptions_total($flt) {
		$query = $this->db->query('SELECT COUNT(sub.sub_id) AS count FROM '.$this->db->dbprefix('subscriptions').' sub LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = sub.fed_id LEFT JOIN '.$this->db->dbprefix('folders').' AS flr ON flr.flr_id = sub.flr_id WHERE '.implode(' AND ', $flt));
		return $query->row();
	}
	function get_subscriptions_rows($flt, $num, $offset, $order) {
		$date_ref = date('Y-m-d H:i:s', time() - 3600 * 24 * 30);

		$subscriptions = false;
		if($this->session->userdata('timezone')) {
			$timezone = $this->session->userdata('timezone');
		} else {
			$timezone = 0;
		}
		$query = $this->db->query('SELECT fed.*, DATE_ADD(fed.fed_lastcrawl, INTERVAL ? HOUR) AS fed_lastcrawl, DATE_ADD(sub.sub_datecreated, INTERVAL ? HOUR) AS sub_datecreated, sub.sub_id, sub.sub_title, sub.sub_priority, IF(sub.sub_direction IS NOT NULL, sub.sub_direction, fed.fed_direction) AS direction, sub.flr_id, flr.flr_title, (SELECT COUNT(DISTINCT(count_sub.mbr_id)) FROM '.$this->db->dbprefix('subscriptions').' AS count_sub WHERE count_sub.fed_id = sub.fed_id) AS subscribers FROM '.$this->db->dbprefix('subscriptions').' AS sub LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = sub.fed_id LEFT JOIN '.$this->db->dbprefix('folders').' AS flr ON flr.flr_id = sub.flr_id WHERE '.implode(' AND ', $flt).' GROUP BY sub.sub_id ORDER BY '.$order.' LIMIT '.$offset.', '.$num, array($timezone, $timezone));
		if($query->num_rows() > 0) {
			$subscriptions = array();
			foreach($query->result() as $sub) {

				$sub->categories = false;
				if($this->config->item('tags')) {
					$categories = $this->db->query('SELECT cat.cat_title AS ref, COUNT(DISTINCT(itm.itm_id)) AS nb FROM '.$this->db->dbprefix('items').' AS itm LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = itm.fed_id LEFT JOIN '.$this->db->dbprefix('categories').' AS cat ON cat.itm_id = itm.itm_id WHERE cat.cat_id IS NOT NULL AND cat.cat_datecreated >= ? AND sub.mbr_id = ? AND sub.sub_id = ? GROUP BY ref ORDER BY nb DESC LIMIT 0,10', array($date_ref, $this->member->mbr_id, $sub->sub_id))->result();
					if($categories) {
						$sub->categories = array();
						foreach($categories as $cat) {
							$sub->categories[] = $cat->ref;
						}
					}
				}
				$subscriptions[] = $sub;
			}
		}
		return $subscriptions;
	}
	function get_subscription_row($sub_id) {
		if($this->session->userdata('timezone')) {
			$timezone = $this->session->userdata('timezone');
		} else {
			$timezone = 0;
		}
		$query = $this->db->query('SELECT fed.*, DATE_ADD(fed.fed_lastcrawl, INTERVAL ? HOUR) AS fed_lastcrawl, sub.sub_id, sub.sub_title, sub.sub_priority, sub.sub_direction, IF(sub.sub_direction IS NOT NULL, sub.sub_direction, fed.fed_direction) AS direction, sub.flr_id, flr.flr_title, (SELECT COUNT(DISTINCT(count_sub.mbr_id)) FROM '.$this->db->dbprefix('subscriptions').' AS count_sub WHERE count_sub.fed_id = sub.fed_id) AS subscribers FROM '.$this->db->dbprefix('subscriptions').' AS sub LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = sub.fed_id LEFT JOIN '.$this->db->dbprefix('folders').' AS flr ON flr.flr_id = sub.flr_id WHERE sub.mbr_id = ? AND sub.sub_id = ? AND fed.fed_id IS NOT NULL GROUP BY sub.sub_id', array($timezone, $this->member->mbr_id, $sub_id));
		return $query->row();
	}

	function get_folders_total($flt) {
		$query = $this->db->query('SELECT COUNT(flr.flr_id) AS count FROM '.$this->db->dbprefix('folders').' AS flr WHERE '.implode(' AND ', $flt));
		return $query->row();
	}
	function get_folders_rows($flt, $num, $offset, $order) {
		$query = $this->db->query('SELECT flr.*, (SELECT COUNT(DISTINCT(count_sub.sub_id)) FROM '.$this->db->dbprefix('subscriptions').' AS count_sub WHERE count_sub.flr_id = flr.flr_id) AS subscriptions FROM '.$this->db->dbprefix('folders').' AS flr WHERE '.implode(' AND ', $flt).' GROUP BY flr.flr_id ORDER BY '.$order.' LIMIT '.$offset.', '.$num);
		return $query->result();
	}
	function get_flr_row($flr_id) {
		$query = $this->db->query('SELECT flr.*, (SELECT COUNT(DISTINCT(count_sub.sub_id)) FROM '.$this->db->dbprefix('subscriptions').' AS count_sub WHERE count_sub.flr_id = flr.flr_id) AS subscriptions FROM '.$this->db->dbprefix('folders').' AS flr WHERE flr.mbr_id = ? AND flr.flr_id = ? GROUP BY flr.flr_id', array($this->member->mbr_id, $flr_id));
		return $query->row();
	}

	function get_explore_total($flt) {
		$query = $this->db->query('SELECT COUNT(DISTINCT(fed.fed_id)) AS count FROM '.$this->db->dbprefix('feeds').' fed WHERE '.implode(' AND ', $flt).' AND fed.fed_id NOT IN( SELECT sub.fed_id FROM '.$this->db->dbprefix('subscriptions').' AS sub WHERE sub.mbr_id = ?)', array($this->member->mbr_id));
		return $query->row();
	}
	function get_explore_rows($flt, $num, $offset, $order) {
		$date_ref = date('Y-m-d H:i:s', time() - 3600 * 24 * 30);

		$feeds = false;
		$query = $this->db->query('SELECT fed.*, (SELECT COUNT(DISTINCT(count_sub.mbr_id)) FROM '.$this->db->dbprefix('subscriptions').' AS count_sub WHERE count_sub.fed_id = fed.fed_id) AS subscribers FROM '.$this->db->dbprefix('feeds').' AS fed WHERE '.implode(' AND ', $flt).' AND fed.fed_id NOT IN( SELECT sub.fed_id FROM '.$this->db->dbprefix('subscriptions').' AS sub WHERE sub.mbr_id = ?) GROUP BY fed.fed_id ORDER BY '.$order.' LIMIT '.$offset.', '.$num, array($this->member->mbr_id));
		if($query->num_rows() > 0) {
			$feeds = array();
			foreach($query->result() as $fed) {

				$fed->categories = false;
				if($this->config->item('tags')) {
					$categories = $this->db->query('SELECT cat.cat_title AS ref, COUNT(DISTINCT(itm.itm_id)) AS nb FROM '.$this->db->dbprefix('items').' AS itm LEFT JOIN '.$this->db->dbprefix('categories').' AS cat ON cat.itm_id = itm.itm_id WHERE cat.cat_id IS NOT NULL AND cat.cat_datecreated >= ? AND itm.fed_id = ? GROUP BY ref ORDER BY nb DESC LIMIT 0,10', array($date_ref, $fed->fed_id))->result();
					if($categories) {
						$fed->categories = array();
						foreach($categories as $cat) {
							$fed->categories[] = $cat->ref;
						}
					}
				}
				$feeds[] = $fed;
			}
		}
		return $feeds;
	}
	function count_unread($type, $id = false) {
		if($type == 'all') {
			$sql = 'SELECT COUNT(DISTINCT(itm.itm_id)) AS count
			FROM '.$this->db->dbprefix('subscriptions').' AS sub
			LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.fed_id = sub.fed_id
			LEFT JOIN '.$this->db->dbprefix('history').' AS hst ON hst.itm_id = itm.itm_id AND hst.mbr_id = ?
			WHERE hst.hst_id IS NULL AND sub.mbr_id = ?';
			return $this->db->query($sql, array($this->member->mbr_id, $this->member->mbr_id))->row()->count;
		}
		if($type == 'priority') {
			$sql = 'SELECT COUNT(DISTINCT(itm.itm_id)) AS count
			FROM '.$this->db->dbprefix('subscriptions').' AS sub
			LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.fed_id = sub.fed_id
			LEFT JOIN '.$this->db->dbprefix('history').' AS hst ON hst.itm_id = itm.itm_id AND hst.mbr_id = ?
			WHERE hst.hst_id IS NULL AND sub.mbr_id = ? AND sub.sub_priority = ?';
			return $this->db->query($sql, array($this->member->mbr_id, $this->member->mbr_id, 1))->row()->count;
		}
		if($type == 'geolocation') {
			$sql = 'SELECT COUNT(DISTINCT(itm.itm_id)) AS count
			FROM '.$this->db->dbprefix('subscriptions').' AS sub
			LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.fed_id = sub.fed_id
			LEFT JOIN '.$this->db->dbprefix('history').' AS hst ON hst.itm_id = itm.itm_id AND hst.mbr_id = ?
			WHERE hst.hst_id IS NULL AND itm.itm_latitude IS NOT NULL AND itm.itm_longitude IS NOT NULL AND sub.mbr_id = ?';
			return $this->db->query($sql, array($this->member->mbr_id, $this->member->mbr_id))->row()->count;
		}
		if($type == 'audio') {
			$sql = 'SELECT COUNT(DISTINCT(itm.itm_id)) AS count
			FROM '.$this->db->dbprefix('subscriptions').' AS sub
			LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.fed_id = sub.fed_id
			LEFT JOIN '.$this->db->dbprefix('enclosures').' AS enr ON enr.itm_id = itm.itm_id
			LEFT JOIN '.$this->db->dbprefix('history').' AS hst ON hst.itm_id = itm.itm_id AND hst.mbr_id = ?
			WHERE hst.hst_id IS NULL AND sub.mbr_id = ? AND enr.enr_type LIKE ?';
			return $this->db->query($sql, array($this->member->mbr_id, $this->member->mbr_id, 'audio/%'))->row()->count;
		}
		if($type == 'nofolder') {
			$sql = 'SELECT COUNT(DISTINCT(itm.itm_id)) AS count
			FROM '.$this->db->dbprefix('subscriptions').' AS sub
			LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.fed_id = sub.fed_id AND itm.itm_id NOT IN (SELECT hst.itm_id FROM '.$this->db->dbprefix('history').' AS hst WHERE hst.mbr_id = ?)
			WHERE sub.flr_id IS NULL AND sub.mbr_id = ?';
			return $this->db->query($sql, array($this->member->mbr_id, $this->member->mbr_id))->row()->count;
		}
		if($type == 'author') {
			$sql = 'SELECT COUNT(DISTINCT(itm.itm_id)) AS count
			FROM '.$this->db->dbprefix('items').' AS itm
			LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = itm.fed_id
			WHERE sub.mbr_id = ? AND itm.itm_id NOT IN (SELECT hst.itm_id FROM '.$this->db->dbprefix('history').' AS hst WHERE hst.mbr_id = ?) AND itm.itm_author = ?';
			return $this->db->query($sql, array($this->member->mbr_id, $this->member->mbr_id, $id))->row()->count;
		}
		if($type == 'category') {
			$sql = 'SELECT COUNT(DISTINCT(itm.itm_id)) AS count
			FROM '.$this->db->dbprefix('items').' AS itm
			LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = itm.fed_id
			WHERE sub.mbr_id = ? AND itm.itm_id NOT IN (SELECT hst.itm_id FROM '.$this->db->dbprefix('history').' AS hst WHERE hst.mbr_id = ?) AND itm.itm_id IN ( SELECT cat.itm_id FROM categories AS cat WHERE cat.cat_title = ? )';
			return $this->db->query($sql, array($this->member->mbr_id, $this->member->mbr_id, $id))->row()->count;
		}
	}
}

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reader_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}
	function login($email, $password, $remember) {
		$this->session->unset_userdata('logged_member');
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
		$this->session->set_userdata('logged_member', $mbr_id);

		$token_connection = sha1(uniqid($mbr_id, 1).mt_rand());
		$this->db->set('mbr_id', $mbr_id);
		$this->db->set('token_connection', $token_connection);
		$this->db->set('cnt_ip', $this->input->ip_address());
		$this->db->set('cnt_agent', $this->input->user_agent());
		$this->db->set('cnt_datecreated', date('Y-m-d H:i:s'));
		$this->db->insert('connections');

		$this->input->set_cookie('logged_member', $token_connection, 0, NULL, '/', NULL, NULL);
	}
	function logout() {
		if($this->session->userdata('logged_member') && $this->input->cookie('logged_member')) {
			$this->db->set('token_connection', '');
			$this->db->where('token_connection', $this->input->cookie('logged_member'));
			$this->db->where('mbr_id', $this->session->userdata('logged_member'));
			$this->db->update('connections');

			$this->input->set_cookie('logged_member', NULL, 0, NULL, '/', NULL, NULL);
		}

		$this->session->unset_userdata('logged_member');
	}
	function get($mbr_id) {
		$member = FALSE;
		$query = $this->db->query('SELECT mbr.* FROM '.$this->db->dbprefix('members').' AS mbr WHERE mbr.mbr_id = ? GROUP BY mbr.mbr_id', array($mbr_id));
		if($query->num_rows() > 0) {
			$member = $query->row();

			$query = $this->db->query('SELECT cnt.* FROM '.$this->db->dbprefix('connections').' AS cnt WHERE cnt.mbr_id = ? AND token_connection IS NOT NULL AND token_connection = ? GROUP BY cnt.cnt_id', array($mbr_id, $this->input->cookie('logged_member')));
			if($query->num_rows() > 0) {
				$member->token_connection = $query->row()->token_connection;
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
		if($this->session->userdata('timezone')) {
			$timezone = $this->session->userdata('timezone');
		} else {
			$timezone = 0;
		}
		$query = $this->db->query('SELECT fed.*, DATE_ADD(fed.fed_lastcrawl, INTERVAL ? HOUR) AS fed_lastcrawl, DATE_ADD(sub.sub_datecreated, INTERVAL ? HOUR) AS sub_datecreated, sub.sub_id, sub.sub_title, sub.flr_id, flr.flr_title, (SELECT COUNT(DISTINCT(count_sub.mbr_id)) FROM '.$this->db->dbprefix('subscriptions').' AS count_sub WHERE count_sub.fed_id = sub.fed_id) AS subscribers FROM '.$this->db->dbprefix('subscriptions').' AS sub LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = sub.fed_id LEFT JOIN '.$this->db->dbprefix('folders').' AS flr ON flr.flr_id = sub.flr_id WHERE '.implode(' AND ', $flt).' GROUP BY sub.sub_id ORDER BY '.$order.' LIMIT '.$offset.', '.$num, array($timezone, $timezone));
		return $query->result();
	}
	function get_subscription_row($sub_id) {
		if($this->session->userdata('timezone')) {
			$timezone = $this->session->userdata('timezone');
		} else {
			$timezone = 0;
		}
		$query = $this->db->query('SELECT fed.*, DATE_ADD(fed.fed_lastcrawl, INTERVAL ? HOUR) AS fed_lastcrawl, sub.sub_id, sub.sub_title, sub.flr_id, flr.flr_title, (SELECT COUNT(DISTINCT(count_sub.mbr_id)) FROM '.$this->db->dbprefix('subscriptions').' AS count_sub WHERE count_sub.fed_id = sub.fed_id) AS subscribers FROM '.$this->db->dbprefix('subscriptions').' AS sub LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = sub.fed_id LEFT JOIN '.$this->db->dbprefix('folders').' AS flr ON flr.flr_id = sub.flr_id WHERE sub.mbr_id = ? AND sub.sub_id = ? AND fed.fed_id IS NOT NULL GROUP BY sub.sub_id', array($timezone, $this->member->mbr_id, $sub_id));
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
		$query = $this->db->query('SELECT fed.*, (SELECT COUNT(DISTINCT(count_sub.mbr_id)) FROM '.$this->db->dbprefix('subscriptions').' AS count_sub WHERE count_sub.fed_id = fed.fed_id) AS subscribers FROM '.$this->db->dbprefix('feeds').' AS fed WHERE '.implode(' AND ', $flt).' AND fed.fed_id NOT IN( SELECT sub.fed_id FROM '.$this->db->dbprefix('subscriptions').' AS sub WHERE sub.mbr_id = ?) GROUP BY fed.fed_id ORDER BY '.$order.' LIMIT '.$offset.', '.$num, array($this->member->mbr_id));
		return $query->result();
	}
}

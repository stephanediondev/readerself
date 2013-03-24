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
}

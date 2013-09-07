<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function index() {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$this->load->library(array('form_validation'));

		$this->form_validation->set_rules('mbr_email', 'lang:mbr_email', 'required|valid_email|max_length[255]|callback_email');
		$this->form_validation->set_rules('mbr_email_confirm', 'lang:mbr_email_confirm', 'required|valid_email|max_length[255]|matches[mbr_email]');
		$this->form_validation->set_rules('mbr_password', 'lang:mbr_password');
		$this->form_validation->set_rules('mbr_password_confirm', 'lang:mbr_password_confirm', 'matches[mbr_password]');

		if($this->form_validation->run() == FALSE) {
			$data = array();

			include('thirdparty/PhpUserAgent/UserAgentParser.php');

			$data['connections'] = $this->db->query('SELECT cnt.*, DATE_ADD(cnt.cnt_datecreated, INTERVAL ? HOUR) AS cnt_datecreated FROM '.$this->db->dbprefix('connections').' AS cnt WHERE cnt.token_connection IS NOT NULL AND cnt.mbr_id = ? GROUP BY cnt.cnt_id ORDER BY cnt.cnt_id DESC LIMIT 0,30', array($this->session->userdata('timezone'), $this->member->mbr_id))->result();

			$content = $this->load->view('profile_index', $data, TRUE);
			$this->reader_library->set_content($content);
		} else {
			$this->db->set('mbr_email', $this->input->post('mbr_email'));
			if($this->input->post('mbr_password') != '' && $this->input->post('mbr_password_confirm') != '') {
				$this->db->set('mbr_password', $this->reader_library->set_salt_password($this->input->post('mbr_password')));
			}
			$this->db->where('mbr_id', $this->member->mbr_id);
			$this->db->update('members');

			redirect(base_url().'home');
		}
	}
	public function logout_purge() {
		$this->db->set('token_connection', '');
		$this->db->where('mbr_id', $this->member->mbr_id);
		$this->db->where('token_connection !=', $this->member->token_connection);
		$this->db->update('connections');

		redirect(base_url().'profile');
	}
	public function email() {
		if($this->input->post('mbr_email')) {
			$query = $this->db->query('SELECT mbr.* FROM '.$this->db->dbprefix('members').' AS mbr WHERE mbr.mbr_email = ? AND mbr.mbr_email != ? GROUP BY mbr.mbr_id', array($this->input->post('mbr_email'), $this->member->mbr_email));
			if($query->num_rows() > 0) {
				$this->form_validation->set_message('email', $this->lang->line('callback_email'));
				return FALSE;
			} else {
				return TRUE;
			}
		}
	}
}

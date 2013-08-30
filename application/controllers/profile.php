<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function index() {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		if(!$this->member->token_share) {
			$token_share = sha1(uniqid($this->member->mbr_id, 1).mt_rand());
			$this->db->set('token_share', $token_share);
			$this->db->where('mbr_id', $this->member->mbr_id);
			$this->db->update('members');
			$this->member = $this->reader_model->get($this->session->userdata('logged_member'));
		}

		$this->load->library(array('form_validation'));

		$this->form_validation->set_rules('mbr_email', 'lang:mbr_email', 'required|valid_email|max_length[255]|callback_email');
		$this->form_validation->set_rules('mbr_email_confirm', 'lang:mbr_email_confirm', 'required|valid_email|max_length[255]|matches[mbr_email]');
		$this->form_validation->set_rules('mbr_password', 'lang:mbr_password');
		$this->form_validation->set_rules('mbr_password_confirm', 'lang:mbr_password_confirm', 'matches[mbr_password]');

		if($this->form_validation->run() == FALSE) {
			$data = array();
			$content = $this->load->view('profile_index', $data, TRUE);
			$this->reader_library->set_content($content);
		} else {
			$this->db->set('mbr_email', $this->input->post('mbr_email'));
			if($this->input->post('mbr_password') != '' && $this->input->post('mbr_password_confirm') != '') {
				$this->db->set('mbr_password', $this->reader_library->set_salt_password($this->input->post('mbr_password')));
			}
			$this->db->where('mbr_id', $this->member->mbr_id);
			$this->db->update('members');

			$this->session->set_userdata('alert', serialize(array('type'=>'success', 'message'=>'Updated')));

			redirect(base_url().'home');
		}
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

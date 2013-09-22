<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function index() {
		if($this->reader_model->count_members() == 0 && !$this->config->item('ldap')) {
			redirect(base_url().'register');
		}
		if($this->session->userdata('mbr_id')) {
			if($this->input->get('u')) {
				redirect(base_url().'subscriptions/create/?u='.$this->input->get('u'));
			} else {
				redirect(base_url().'home');
			}
		}

		$this->load->library('form_validation');

		$this->form_validation->set_rules('mbr_email', 'lang:mbr_email', 'required|valid_email|callback_email');
		$this->form_validation->set_rules('mbr_password', 'lang:mbr_password', 'required');

		if($this->form_validation->run() == FALSE) {
			$data = array();
			$content = $this->load->view('login_index', $data, TRUE);
			$this->reader_library->set_content($content);
		} else {
			if($this->input->get('u')) {
				redirect(base_url().'subscriptions/create/?u='.$this->input->get('u'));
			} else {
				redirect(base_url().'home');
			}
		}
	}
	public function email() {
		if($this->input->post('mbr_email') && $this->input->post('mbr_password')) {
			if($this->reader_model->login($this->input->post('mbr_email'), $this->input->post('mbr_password'))) {
				return TRUE;
			} else {
				$this->form_validation->set_message('email', $this->lang->line('callback_email'));
				return FALSE;
			}
		}
	}
}

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function index() {
		if($this->readerself_model->count_members() == 0 && !$this->config->item('ldap')) {
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

		$this->form_validation->set_rules('email_or_nickname', 'lang:email_or_nickname', 'required|callback_email_or_nickname');
		$this->form_validation->set_rules('mbr_password', 'lang:mbr_password', 'required');

		if($this->form_validation->run() == FALSE) {
			$data = array();
			$content = $this->load->view('login_index', $data, TRUE);
			$this->readerself_library->set_content($content);
		} else {
			if($this->input->get('u')) {
				redirect(base_url().'subscriptions/create/?u='.$this->input->get('u'));
			} else {
				redirect(base_url().'home');
			}
		}
	}
	public function email_or_nickname() {
		if($this->input->post('email_or_nickname') && $this->input->post('mbr_password')) {
			if($this->readerself_model->login($this->input->post('email_or_nickname'), $this->input->post('mbr_password'))) {
				return TRUE;
			} else {
				$this->form_validation->set_message('email_or_nickname', $this->lang->line('callback_email_or_nickname'));
				return FALSE;
			}
		}
	}
}

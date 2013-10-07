<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function index() {
		if($this->readerself_model->count_members() > 0 && !$this->config->item('register_multi')) {
			redirect(base_url());
		}
		if($this->config->item('register_multi') && $this->config->item('ldap')) {
			redirect(base_url());
		}
		if($this->session->userdata('mbr_id')) {
			redirect(base_url().'home');
		}

		$this->load->library(array('form_validation'));

		$this->form_validation->set_rules('mbr_email', 'lang:mbr_email', 'required|valid_email|max_length[255]|callback_email');
		$this->form_validation->set_rules('mbr_email_confirm', 'lang:mbr_email_confirm', 'required|valid_email|max_length[255]|matches[mbr_email]');
		$this->form_validation->set_rules('mbr_password', 'lang:mbr_password', 'required');
		$this->form_validation->set_rules('mbr_password_confirm', 'lang:mbr_password_confirm', 'required|matches[mbr_password]');

		if($this->form_validation->run() == FALSE) {
			$data = array();
			$content = $this->load->view('register_index', $data, TRUE);
			$this->readerself_library->set_content($content);
		} else {
			$this->db->set('mbr_email', $this->input->post('mbr_email'));
			$this->db->set('mbr_password', $this->readerself_library->set_salt_password($this->input->post('mbr_password')));
			if($this->readerself_model->count_members() == 0) {
				$this->db->set('mbr_administrator', 1);
			}
			$this->db->set('mbr_datecreated', date('Y-m-d H:i:s'));
			$this->db->insert('members');
			$mbr_id = $this->db->insert_id();

			$this->readerself_model->connect($mbr_id);

			redirect(base_url().'home');
		}
	}
	public function email() {
		if($this->input->post('mbr_email')) {
			$query = $this->db->query('SELECT mbr.* FROM '.$this->db->dbprefix('members').' AS mbr WHERE mbr.mbr_email = ? GROUP BY mbr.mbr_id', array($this->input->post('mbr_email')));
			if($query->num_rows() > 0) {
				$this->form_validation->set_message('email', $this->lang->line('callback_email'));
				return FALSE;
			} else {
				return TRUE;
			}
		}
	}
}

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Password extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function index() {
		if($this->reader_model->count_members() == 0) {
			redirect(base_url().'register');
		}
		if($this->session->userdata('logged_member')) {
			redirect(base_url().'home');
		}

		$this->load->library(array('form_validation'));

		$this->form_validation->set_rules('mbr_email', 'lang:mbr_email', 'required|valid_email|max_length[255]|callback_email');

		if($this->form_validation->run() == FALSE) {
			$data = array();
			$content = $this->load->view('password_index', $data, TRUE);
			$this->reader_library->set_content($content);
		} else {
			$query = $this->db->query('SELECT mbr.* FROM '.$this->db->dbprefix('members').' AS mbr WHERE mbr.mbr_email = ? GROUP BY mbr.mbr_id', array($this->input->post('mbr_email')));
			if($query->num_rows() > 0) {
				$member = $query->row();

				$token_password = sha1(uniqid($member->mbr_id, 1).mt_rand());
				$this->db->set('token_password', $token_password);
				$this->db->where('mbr_id', $member->mbr_id);
				$this->db->update('members');

				$to = $member->mbr_email;
				$subject = $this->config->item('title').' / '.$this->lang->line('subject_password');
				$message = base_url().'password/token/'.$token_password;

				$this->load->library('email');
				$this->email->clear();

				$this->email->initialize();
				$this->email->from($this->config->item('sender_email'), $this->config->item('sender_name'));
				$this->email->to($to);
				$this->email->subject($subject);
				$this->email->message($message);
				$this->email->send();

				redirect(base_url());
			}
		}
	}
	public function token($token_password) {
		if($this->session->userdata('logged_user')) {
			redirect(base_url().'home');
		}

		$query = $this->db->query('SELECT mbr.* FROM '.$this->db->dbprefix('members').' AS mbr WHERE mbr.token_password = ? GROUP BY mbr.mbr_id', array($token_password));
		if($query->num_rows() > 0) {
			$member = $query->row();

			$mbr_password = generate_string(6);
			$this->db->set('mbr_password', $this->reader_library->set_salt_password($mbr_password));
			$this->db->set('token_password', '');
			$this->db->where('mbr_id', $member->mbr_id);
			$this->db->update('members');

			$data = array();
			$data['mbr_password'] = $mbr_password;
			$content = $this->load->view('password_token', $data, TRUE);
			$this->reader_library->set_content($content);
		} else {
			redirect(base_url());
		}
	}
	public function email() {
		if($this->input->post('mbr_email')) {
			$query = $this->db->query('SELECT mbr.* FROM '.$this->db->dbprefix('members').' AS mbr WHERE mbr.mbr_email = ? GROUP BY mbr.mbr_id', array($this->input->post('mbr_email')));
			if($query->num_rows() > 0) {
				return TRUE;
			} else {
				$this->form_validation->set_message('email', $this->lang->line('callback_email'));
				return FALSE;
			}
		}
	}
}

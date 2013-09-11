<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reader_hook {
	public function post_controller_constructor() {
		$this->CI =& get_instance();

		$language = false;
		if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) == 1) {
			$language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
		}
		$languages = array('en', 'fr');
		if(!in_array($language, $languages)) {
			$language = 'en';
		}
		$this->CI->config->set_item('language', $language);
		$this->CI->load->language('reader');

		$this->CI->reader_library->set_content_type('text/html');
		$this->CI->reader_library->set_charset('UTF-8');
		$this->CI->reader_library->set_template('_html');

		if($this->CI->session->userdata('mbr_id')) {
			$this->CI->member = $this->CI->reader_model->get($this->CI->session->userdata('mbr_id'));
			if(!$this->CI->member || !$this->CI->input->cookie('token_connection') || $this->CI->input->cookie('token_connection') != $this->CI->member->token_connection) {
				if(!$this->CI->input->is_ajax_request() && !$this->CI->input->is_cli_request()) {
					$this->CI->reader_model->logout();
				}
			}

		} else {
			if($this->CI->input->cookie('token_connection')) {
				$query = $this->CI->db->query('SELECT cnt.* FROM '.$this->CI->db->dbprefix('connections').' AS cnt WHERE cnt.cnt_ip = ? AND cnt.cnt_agent = ? AND token_connection IS NOT NULL AND token_connection = ? GROUP BY cnt.cnt_id', array($this->CI->input->ip_address(), $this->CI->input->user_agent(), $this->CI->input->cookie('token_connection')));
				if($query->num_rows() > 0) {
					$connection = $query->row();
					$this->CI->session->set_userdata('mbr_id', $connection->mbr_id);
					$this->CI->input->set_cookie('token_connection', $this->CI->input->cookie('token_connection'), 3600 * 24 * 30, NULL, '/', NULL, NULL);

					if($this->CI->input->get('u')) {
						redirect(base_url().'subscriptions/create/?u='.$this->CI->input->get('u'));
					} else {
						redirect(base_url().'home');
					}
				}
			} else {
				if(!$this->CI->input->is_ajax_request() && !$this->CI->input->is_cli_request()) {
					$this->CI->reader_model->logout();
				}
			}
		}
	}
	public function post_controller() {
		$this->CI =& get_instance();
		header('content-type: '.$this->CI->reader_library->content_type.'; charset='.$this->CI->reader_library->charset);
		$data = array();
		$data['content'] = $this->CI->reader_library->content;
		$this->CI->load->view($this->CI->reader_library->template, $data, FALSE);
	}
}

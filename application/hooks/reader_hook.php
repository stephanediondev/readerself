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

		if($this->CI->session->userdata('logged_member')) {
			$this->CI->member = $this->CI->reader_model->get($this->CI->session->userdata('logged_member'));
			if(!$this->CI->member || !$this->CI->input->cookie('logged_member') || $this->CI->input->cookie('logged_member') != $this->CI->member->token_connection) {
				if($this->CI->input->is_ajax_request() || $this->CI->input->is_cli_request()) {
				} else {
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

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Readerself_hook {
	public function post_controller_constructor() {
		$this->CI =& get_instance();

		$this->CI->config->load('readerself_config', false, true);

		$language = false;
		if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) == 1) {
			$language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
			if($language == 'zh') {
				$language = 'zh-CN';
			}
		}
		$languages = array('en', 'fr', 'zh-CN');
		if(!in_array($language, $languages)) {
			$language = 'en';
		}
		$this->CI->config->set_item('language', $language);
		$this->CI->load->language('readerself');

		$this->CI->readerself_library->set_content_type('text/html');
		$this->CI->readerself_library->set_charset('UTF-8');
		$this->CI->readerself_library->set_template('_html');

		if(!$this->CI->config->item('salt_password') && $this->CI->router->class != 'setup') {
			redirect(base_url().'setup');
		}

		if($this->CI->config->item('salt_password')) {
			$settings = $this->CI->readerself_model->get_settings_global();
			foreach($settings as $stg) {
				$this->CI->config->set_item($stg->stg_code, $stg->stg_value);
			}
		}

		if($this->CI->session->userdata('mbr_id')) {
			$this->CI->member = $this->CI->readerself_model->get($this->CI->session->userdata('mbr_id'));
			if(!$this->CI->member || !$this->CI->input->cookie('token_connection') || $this->CI->input->cookie('token_connection') != $this->CI->member->token_connection) {
				$this->CI->readerself_model->logout();
			}

		} else {
			if($this->CI->input->cookie('token_connection')) {
				$query = $this->CI->db->query('SELECT cnt.* FROM '.$this->CI->db->dbprefix('connections').' AS cnt WHERE cnt.cnt_ip = ? AND cnt.cnt_agent = ? AND token_connection IS NOT NULL AND token_connection = ? GROUP BY cnt.cnt_id', array($this->CI->input->ip_address(), $this->CI->input->user_agent(), $this->CI->input->cookie('token_connection')));
				if($query->num_rows() > 0) {
					$connection = $query->row();

					$this->CI->session->set_userdata('mbr_id', $connection->mbr_id);
					$this->CI->input->set_cookie('token_connection', $this->CI->input->cookie('token_connection'), 3600 * 24 * 30, NULL, '/', NULL, NULL);

					if($this->CI->router->class == 'extension') {
						$this->CI->member = $this->CI->readerself_model->get($this->CI->session->userdata('mbr_id'));
					} else {
						if($this->CI->input->get('u')) {
							redirect(base_url().'subscriptions/create/?u='.$this->CI->input->get('u'));
						} else {
							redirect(base_url().'home');
						}
					}
				} else {
					$this->CI->readerself_model->logout();
				}
			}
		}
	}
	public function post_controller() {
		$this->CI =& get_instance();
		header('content-type: '.$this->CI->readerself_library->content_type.'; charset='.$this->CI->readerself_library->charset);
		$data = array();
		$data['content'] = $this->CI->readerself_library->content;
		$this->CI->load->view($this->CI->readerself_library->template, $data, FALSE);
	}
}

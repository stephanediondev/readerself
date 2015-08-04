<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function index() {
		if(!$this->session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$data = array();
		$data['settings'] = $this->readerself_model->get_settings_global();

		$this->load->library(array('form_validation'));

		foreach($data['settings'] as $stg) {
			$rules = array();
			if($stg->stg_type == 'email') {
				$rules[] = 'valid_email';
			}
			if($stg->stg_type == 'integer') {
				$rules[] = 'integer';
			}
			$this->form_validation->set_rules($stg->stg_code, 'lang:stg_'.$stg->stg_code, implode('|', $rules));
		}

		if($this->form_validation->run() == FALSE) {
			$content = $this->load->view('settings_index', $data, TRUE);
			$this->readerself_library->set_content($content);

		} else {
			if($this->member->mbr_administrator == 1) {
				foreach($data['settings'] as $stg) {
					$this->db->set('stg_value', $this->input->post($stg->stg_code));
					$this->db->where('stg_id', $stg->stg_id);
					$this->db->update('settings');
				}

				redirect(base_url().'settings');
			}
		}
	}
}

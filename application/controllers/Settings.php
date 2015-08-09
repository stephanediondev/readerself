<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function index() {
		if(!$this->axipi_session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$data = array();
		$data['settings'] = $this->readerself_model->get_settings_not_material();
		$data['settings_material'] = $this->readerself_model->get_settings_material();

		$data['colors'] = array(
			'red' => 'F44336',
			'pink' => 'E91E63',
			'purple' => '9C27B0',
			'deep-purple' => '673AB7',
			'indigo' => '3F51B5',
			'blue' => '2196F3',
			'light-blue' => '03A9F4',
			'cyan' => '00BCD4',
			'teal' => '009688',
			'green' => '4CAF50',
			'light-green' => '8BC34A',
			'lime' => 'CDDC39',
			'yellow' => 'FFEB3B',
			'amber' => 'FFC107',
			'orange' => 'FF9800',
			'deep-orange' => 'FF5722',
			'brown' => '795548',
			'grey' => '9E9E9E',
			'blue-grey' => '607D8B',
			'black' => '000000',
			'white' => 'FFFFFF',
		);
		$data['color_black_text'] = array(
			'light-blue',
			'cyan',
			'green',
			'light-green',
			'lime',
			'yellow',
			'amber',
			'orange',
			'grey',
			'white',
		);

		$this->load->library(array('form_validation'));

		foreach($data['settings'] as $stg) {
			$rules = array();
			if($stg->stg_type == 'email') {
				$rules[] = 'valid_email';
			}
			if($stg->stg_type == 'integer') {
				$rules[] = 'integer';
			}
			if(count($rules) > 0) {
				$this->form_validation->set_rules($stg->stg_code, 'lang:stg_'.$stg->stg_code, implode('|', $rules));
			}
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
				foreach($data['settings_material'] as $stg) {
					$this->db->set('stg_value', $this->input->post($stg->stg_code));
					$this->db->where('stg_id', $stg->stg_id);
					$this->db->update('settings');
				}

				redirect(base_url().'settings');
			}
		}
	}
}

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Members extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function index() {
		if(!$this->session->userdata('mbr_id') || !$this->config->item('members_list')) {
			redirect(base_url());
		}

		$filters = array();
		$filters[$this->router->class.'_members_mbr_nickname'] = array('mbr.mbr_nickname', 'like');
		$flt = $this->readerself_library->build_filters($filters);
		$flt[] = 'mbr.mbr_nickname IS NOT NULL';
		$results = $this->readerself_model->get_members_total($flt);
		$build_pagination = $this->readerself_library->build_pagination($results->count, 20, $this->router->class.'_members');
		$data = array();
		$data['pagination'] = $build_pagination['output'];
		$data['position'] = $build_pagination['position'];
		$data['members'] = $this->readerself_model->get_members_rows($flt, $build_pagination['limit'], $build_pagination['start'], 'mbr.mbr_nickname ASC');

		$content = $this->load->view('members_index', $data, TRUE);
		$this->readerself_library->set_content($content);
	}

	public function follow($mbr_id) {
		if(!$this->session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$content = array();

		if($this->input->is_ajax_request()) {
			$this->readerself_library->set_template('_json');
			$this->readerself_library->set_content_type('application/json');

			$mbr = $this->readerself_model->get_member_row($mbr_id);
			if($mbr) {
			}
		} else {
			$this->output->set_status_header(403);
		}
		$this->readerself_library->set_content($content);
	}
}

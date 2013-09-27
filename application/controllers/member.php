<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member extends CI_Controller {
	public function __construct() {
		parent::__construct();
	}
	public function _remap($method, $params = array()) {
		if(method_exists($this, $method)) {
			return call_user_func_array(array($this, $method), $params);
		} else {
			$this->router->set_method('index');
			$this->index($method);
		}
	}
	public function index($mbr_nickname = false) {
		$data = array();

		$query = $this->db->query('SELECT mbr.* FROM '.$this->db->dbprefix('members').' AS mbr WHERE mbr.mbr_nickname = ? GROUP BY mbr.mbr_id', array($mbr_nickname));
		if($query->num_rows() > 0) {
			$data['member'] = $query->row();

			$content = $this->load->view('member_index', $data, TRUE);
		} else {
			$data['member'] = false;

			$content = $this->load->view('member_error', $data, TRUE);
		}
		$this->readerself_library->set_content($content);
	}
}

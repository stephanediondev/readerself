<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Explore extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function index() {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$data = array();

		$filters = array();
		$filters[$this->router->class.'_explore_fed_title'] = array('fed.fed_title', 'like');
		$flt = $this->reader_library->build_filters($filters);
		$flt[] = 'fed.fed_id IS NOT NULL';
		$columns = array();
		$columns[] = 'fed.fed_title';
		$columns[] = 'fed.fed_description';
		$columns[] = 'fed.fed_url';
		$columns[] = 'subscribers';
		$col = $this->reader_library->build_columns($this->router->class.'_explore', $columns, 'subscribers', 'DESC');
		$results = $this->reader_model->get_explore_total($flt);
		$build_pagination = $this->reader_library->build_pagination($results->count, 20, $this->router->class.'_explore');
		$data = array();
		$data['columns'] = $col;
		$data['pagination'] = $build_pagination['output'];
		$data['position'] = $build_pagination['position'];
		$data['feeds'] = $this->reader_model->get_explore_rows($flt, $build_pagination['limit'], $build_pagination['start'], $this->router->class.'_explore');

		$content = $this->load->view('explore_index', $data, TRUE);
		$this->reader_library->set_content($content);
	}
	public function add($fed_id) {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$query = $this->db->query('SELECT fed.* FROM '.$this->db->dbprefix('feeds').' AS fed WHERE fed.fed_id = ? GROUP BY fed.fed_id', array($fed_id));
		if($query->num_rows() > 0) {
			$query = $this->db->query('SELECT sub.* FROM '.$this->db->dbprefix('subscriptions').' AS sub WHERE sub.fed_id = ? AND sub.mbr_id = ? GROUP BY sub.sub_id', array($fed_id, $this->member->mbr_id));
			if($query->num_rows() == 0) {
				$this->db->set('mbr_id', $this->member->mbr_id);
				$this->db->set('fed_id', $fed_id);
				$this->db->set('sub_datecreated', date('Y-m-d H:i:s'));
				$this->db->insert('subscriptions');
			}
		}
		redirect(base_url().'explore');
	}
}

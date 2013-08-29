<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Export extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function index() {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$this->reader_library->set_template('_opml');
		$this->reader_library->set_content_type('application/xml');

		$subscriptions = array();
		$query = $this->db->query('SELECT fed.*, sub.sub_id, sub.flr_id, flr.flr_title FROM '.$this->db->dbprefix('subscriptions').' AS sub LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = sub.fed_id LEFT JOIN '.$this->db->dbprefix('folders').' AS flr ON flr.flr_id = sub.flr_id WHERE sub.mbr_id = ? AND fed.fed_id IS NOT NULL GROUP BY sub.sub_id ORDER BY flr.flr_title ASC, fed.fed_title ASC', array($this->member->mbr_id));
		if($query->num_rows() > 0) {
			foreach($query->result() as $sub) {
				$subscriptions[$sub->flr_title][] = $sub;
			}
		}

		$data = array();
		$data['subscriptions'] = $subscriptions;

		$content = $this->load->view('export_index', $data, TRUE);
		$this->reader_library->set_content($content);
	}
}

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Feeds extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function index() {
		if(!$this->session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$data = array();

		$filters = array();
		$filters[$this->router->class.'_feeds_fed_title'] = array('fed.fed_title', 'like');
		$flt = $this->reader_library->build_filters($filters);
		$flt[] = 'fed.fed_id IS NOT NULL';
		$results = $this->reader_model->get_feeds_total($flt);
		$build_pagination = $this->reader_library->build_pagination($results->count, 20, $this->router->class.'_feeds');
		$data = array();
		$data['pagination'] = $build_pagination['output'];
		$data['position'] = $build_pagination['position'];
		$data['feeds'] = $this->reader_model->get_feeds_rows($flt, $build_pagination['limit'], $build_pagination['start'], 'subscribers DESC');

		$content = $this->load->view('feeds_index', $data, TRUE);
		$this->reader_library->set_content($content);
	}
	public function add($fed_id) {
		if(!$this->session->userdata('mbr_id')) {
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
		redirect(base_url().'feeds');
	}
	public function delete($fed_id) {
		if(!$this->session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$this->load->library('form_validation');
		$data = array();
		$data['fed'] = $this->reader_model->get_feed_row($fed_id);
		if($data['fed']) {
			if($data['fed']->subscribers == 0) {
				$this->form_validation->set_rules('confirm', 'lang:confirm', 'required');
				if($this->form_validation->run() == FALSE) {
					$content = $this->load->view('feeds_delete', $data, TRUE);
					$this->reader_library->set_content($content);
				} else {
					$query = $this->db->query('DELETE FROM '.$this->db->dbprefix('categories').' WHERE itm_id IN ( SELECT itm.itm_id FROM '.$this->db->dbprefix('items').' AS itm WHERE itm.fed_id = ? GROUP BY itm.itm_id )', array($fed_id));
					$query = $this->db->query('DELETE FROM '.$this->db->dbprefix('enclosures').' WHERE itm_id IN ( SELECT itm.itm_id FROM '.$this->db->dbprefix('items').' AS itm WHERE itm.fed_id = ? GROUP BY itm.itm_id )', array($fed_id));
					$query = $this->db->query('DELETE FROM '.$this->db->dbprefix('favorites').' WHERE itm_id IN ( SELECT itm.itm_id FROM '.$this->db->dbprefix('items').' AS itm WHERE itm.fed_id = ? GROUP BY itm.itm_id )', array($fed_id));
					$query = $this->db->query('DELETE FROM '.$this->db->dbprefix('history').' WHERE itm_id IN ( SELECT itm.itm_id FROM '.$this->db->dbprefix('items').' AS itm WHERE itm.fed_id = ? GROUP BY itm.itm_id )', array($fed_id));
					$query = $this->db->query('DELETE FROM '.$this->db->dbprefix('share').' WHERE itm_id IN ( SELECT itm.itm_id FROM '.$this->db->dbprefix('items').' AS itm WHERE itm.fed_id = ? GROUP BY itm.itm_id )', array($fed_id));

					$this->db->where('fed_id', $fed_id);
					$this->db->delete('items');

					$this->db->where('fed_id', $fed_id);
					$this->db->delete('feeds');

					$this->db->query('OPTIMIZE TABLE categories, enclosures, favorites, history, share, items, feeds');

					redirect(base_url().'feeds');
				}
			} else {
				redirect(base_url().'feeds');
			}
		} else {
			$this->index();
		}
	}
	public function export() {
		if(!$this->session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$this->reader_library->set_template('_opml');
		$this->reader_library->set_content_type('application/xml');

		header('Content-Disposition: inline; filename="feeds-'.date('Y-m-d').'.opml";');

		$feeds = array();
		$query = $this->db->query('SELECT fed.* FROM '.$this->db->dbprefix('feeds').' AS fed WHERE fed.fed_id NOT IN( SELECT sub.fed_id FROM '.$this->db->dbprefix('subscriptions').' AS sub WHERE sub.mbr_id = ?) GROUP BY fed.fed_id', array($this->member->mbr_id));
		if($query->num_rows() > 0) {
			foreach($query->result() as $fed) {
				$feeds[] = $fed;
			}
		}

		$data = array();
		$data['feeds'] = $feeds;

		$content = $this->load->view('feeds_export', $data, TRUE);
		$this->reader_library->set_content($content);
	}
}

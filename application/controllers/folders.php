<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Folders extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function index() {
		if(!$this->session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$data = array();

		$filters = array();
		$filters[$this->router->class.'_folders_flr_title'] = array('flr.flr_title', 'like');
		$flt = $this->reader_library->build_filters($filters);
		$flt[] = 'flr.mbr_id = \''.$this->member->mbr_id.'\'';
		$results = $this->reader_model->get_folders_total($flt);
		$build_pagination = $this->reader_library->build_pagination($results->count, 20, $this->router->class.'_folders');
		$data = array();
		$data['pagination'] = $build_pagination['output'];
		$data['position'] = $build_pagination['position'];
		$data['folders'] = $this->reader_model->get_folders_rows($flt, $build_pagination['limit'], $build_pagination['start'], 'flr.flr_title ASC');

		$content = $this->load->view('folders_index', $data, TRUE);
		$this->reader_library->set_content($content);
	}

	public function create() {
		if(!$this->session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$data = array();
		$this->load->library(array('form_validation'));
		$this->form_validation->set_rules('flr_title', 'lang:flr_title', 'required|max_length[255]');
		if($this->form_validation->run() == FALSE) {
			$content = $this->load->view('folders_create', $data, TRUE);
			$this->reader_library->set_content($content);
		} else {
			$this->db->set('mbr_id', $this->member->mbr_id);
			$this->db->set('flr_title', $this->input->post('flr_title'));
			$this->db->set('flr_datecreated', date('Y-m-d H:i:s'));
			$this->db->insert('folders');
			$flr_id = $this->db->insert_id();

			//$this->read($flr_id);
			redirect(base_url().'folders');
		}
	}

	public function read($flr_id) {
		if(!$this->session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$data = array();
		$data['flr'] = $this->reader_model->get_flr_row($flr_id);
		if($data['flr']) {

			$data['tables'] = '';

			$date_ref = date('Y-m-d H:i:s', time() - 3600 * 24 * 30);

			$legend = array();
			$values = array();
			$query = $this->db->query('SELECT IF(sub.sub_title IS NOT NULL, sub.sub_title, fed.fed_title) AS ref, sub.sub_id AS id, IF(sub.sub_direction IS NOT NULL, sub.sub_direction, fed.fed_direction) AS direction, COUNT(DISTINCT(hst.itm_id)) AS nb FROM '.$this->db->dbprefix('history').' AS hst LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.itm_id = hst.itm_id LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = itm.fed_id LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = fed.fed_id WHERE hst.hst_real = ? AND hst.hst_datecreated >= ? AND hst.mbr_id = ? AND sub.mbr_id = ? AND sub.flr_id = ? GROUP BY id ORDER BY nb DESC LIMIT 0,30', array(1, $date_ref, $this->member->mbr_id, $this->member->mbr_id, $flr_id));
			if($query->num_rows() > 0) {
				foreach($query->result() as $row) {
					if($row->direction) {
						$legend[] = '<a dir="'.$row->direction.'" href="'.base_url().'subscriptions/read/'.$row->id.'">'.$row->ref.'</a>';
					} else {
						$legend[] = '<a href="'.base_url().'subscriptions/read/'.$row->id.'">'.$row->ref.'</a>';
					}
					$values[] = $row->nb;
				}
			}
			$data['tables'] .= build_table_repartition($this->lang->line('items_read_by_subscription').'*', $values, $legend);

			if($this->config->item('tags')) {
				$legend = array();
				$values = array();
				$query = $this->db->query('SELECT cat.cat_title AS ref, COUNT(DISTINCT(hst.itm_id)) AS nb FROM '.$this->db->dbprefix('history').' AS hst LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.itm_id = hst.itm_id LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = itm.fed_id LEFT JOIN '.$this->db->dbprefix('categories').' AS cat ON cat.itm_id = itm.itm_id WHERE cat.cat_id IS NOT NULL AND hst.hst_real = ? AND hst.hst_datecreated >= ? AND hst.mbr_id = ? AND sub.flr_id = ? GROUP BY ref ORDER BY nb DESC LIMIT 0,30', array(1, $date_ref, $this->member->mbr_id, $flr_id));
				if($query->num_rows() > 0) {
					foreach($query->result() as $row) {
						$legend[] = $row->ref;
						$values[] = $row->nb;
					}
				}
				$data['tables'] .= build_table_repartition($this->lang->line('items_read_by_tag').'*', $values, $legend);
			}

			$legend = array();
			$values = array();
			$query = $this->db->query('SELECT SUBSTRING(DATE_ADD(hst.hst_datecreated, INTERVAL ? HOUR), 1, 10) AS ref, COUNT(DISTINCT(hst.itm_id)) AS nb FROM '.$this->db->dbprefix('history').' AS hst LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.itm_id = hst.itm_id LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = itm.fed_id WHERE hst.hst_real = ? AND hst.mbr_id = ? AND sub.flr_id = ? GROUP BY ref ORDER BY ref DESC LIMIT 0,30', array($this->session->userdata('timezone'), 1, $this->member->mbr_id, $flr_id));
			if($query->num_rows() > 0) {
				foreach($query->result() as $row) {
					$legend[] = date('F j, Y', strtotime($row->ref));
					$values[] = $row->nb;
				}
			}
			$data['tables'] .= build_table_progression($this->lang->line('items_read_by_day'), $values, $legend);

			$legend = array();
			$values = array();
			$query = $this->db->query('SELECT SUBSTRING(DATE_ADD(hst.hst_datecreated, INTERVAL ? HOUR), 1, 7) AS ref, COUNT(DISTINCT(hst.itm_id)) AS nb FROM '.$this->db->dbprefix('history').' AS hst LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.itm_id = hst.itm_id LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = itm.fed_id WHERE hst.hst_real = ? AND hst.mbr_id = ? AND sub.flr_id = ? GROUP BY ref ORDER BY ref DESC LIMIT 0,12', array($this->session->userdata('timezone'), 1, $this->member->mbr_id, $flr_id));
			if($query->num_rows() > 0) {
				foreach($query->result() as $row) {
					$legend[] = date('F, Y', strtotime($row->ref));
					$values[] = $row->nb;
				}
			}
			$data['tables'] .= build_table_progression($this->lang->line('items_read_by_month'), $values, $legend);

			if($this->config->item('star')) {
				$legend = array();
				$values = array();
				$query = $this->db->query('SELECT SUBSTRING(DATE_ADD(fav.fav_datecreated, INTERVAL ? HOUR), 1, 7) AS ref, COUNT(DISTINCT(fav.itm_id)) AS nb FROM '.$this->db->dbprefix('favorites').' AS fav LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.itm_id = fav.itm_id LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = itm.fed_id WHERE fav.mbr_id = ? AND sub.flr_id = ? GROUP BY ref ORDER BY ref DESC LIMIT 0,12', array($this->session->userdata('timezone'), $this->member->mbr_id, $flr_id));
				if($query->num_rows() > 0) {
					foreach($query->result() as $row) {
						$legend[] = date('F, Y', strtotime($row->ref));
						$values[] = $row->nb;
					}
				}
				$data['tables'] .= build_table_progression($this->lang->line('items_starred_by_month'), $values, $legend);
			}

			$content = $this->load->view('folders_read', $data, TRUE);
			$this->reader_library->set_content($content);
		} else {
			$this->index();
		}
	}

	public function update($flr_id) {
		if(!$this->session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$this->load->library('form_validation');
		$data = array();
		$data['flr'] = $this->reader_model->get_flr_row($flr_id);
		if($data['flr']) {
			$this->form_validation->set_rules('flr_title', 'lang:flr_title', 'required|max_length[255]');
			if($this->form_validation->run() == FALSE) {
				$content = $this->load->view('folders_update', $data, TRUE);
				$this->reader_library->set_content($content);
			} else {
				$this->db->set('flr_title', $this->input->post('flr_title'));
				$this->db->where('flr_id', $flr_id);
				$this->db->update('folders');

				//$this->read($flr_id);
				redirect(base_url().'folders');
			}
		} else {
			$this->index();
		}
	}

	public function delete($flr_id) {
		if(!$this->session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$this->load->library('form_validation');
		$data = array();
		$data['flr'] = $this->reader_model->get_flr_row($flr_id);
		if($data['flr']) {
			$this->form_validation->set_rules('confirm', 'lang:confirm', 'required');
			if($this->form_validation->run() == FALSE) {
				$content = $this->load->view('folders_delete', $data, TRUE);
				$this->reader_library->set_content($content);
			} else {
				$this->db->set('flr_id', '');
				$this->db->where('flr_id', $flr_id);
				$this->db->where('mbr_id', $this->member->mbr_id);
				$this->db->update('subscriptions');

				$this->db->where('flr_id', $flr_id);
				$this->db->delete('folders');

				redirect(base_url().'folders');
			}
		} else {
			$this->index();
		}
	}
}

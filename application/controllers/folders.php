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
		$flt = $this->readerself_library->build_filters($filters);
		$flt[] = 'flr.mbr_id = \''.$this->member->mbr_id.'\'';
		$results = $this->readerself_model->get_folders_total($flt);
		$build_pagination = $this->readerself_library->build_pagination($results->count, 50, $this->router->class.'_folders');
		$data = array();
		$data['pagination'] = $build_pagination['output'];
		$data['position'] = $build_pagination['position'];
		$data['folders'] = $this->readerself_model->get_folders_rows($flt, $build_pagination['limit'], $build_pagination['start'], 'flr.flr_title ASC');

		$content = $this->load->view('folders_index', $data, TRUE);
		$this->readerself_library->set_content($content);
	}

	public function create() {
		if(!$this->session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$data = array();
		$this->load->library(array('form_validation'));
		$this->form_validation->set_rules('flr_title', 'lang:flr_title', 'required|max_length[255]');
		$this->form_validation->set_rules('direction', 'lang:direction', '');
		if($this->form_validation->run() == FALSE) {
			$content = $this->load->view('folders_create', $data, TRUE);
			$this->readerself_library->set_content($content);
		} else {
			$this->db->set('mbr_id', $this->member->mbr_id);
			$this->db->set('flr_title', $this->input->post('flr_title'));
			$this->db->set('flr_direction', $this->input->post('direction'));
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
		$data['flr'] = $this->readerself_model->get_flr_row($flr_id);
		if($data['flr']) {

			if($this->db->dbdriver == 'mysqli') {
				$substring = 'SUBSTRING';
			} else {
				$substring = 'SUBSTR';
			}

			$data['tables'] = '';

			$date_ref = date('Y-m-d H:i:s', time() - 3600 * 24 * 30);

			if($this->db->dbdriver == 'mysqli') {
				$legend = array();
				$values = array();
				$query = $this->db->query('SELECT fed.fed_host, IF(sub.sub_title IS NOT NULL, sub.sub_title, fed.fed_title) AS ref, sub.sub_id AS id, IF(sub.sub_direction IS NOT NULL, sub.sub_direction, fed.fed_direction) AS direction, COUNT(DISTINCT(hst.itm_id)) AS nb FROM '.$this->db->dbprefix('history').' AS hst LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.itm_id = hst.itm_id LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = itm.fed_id LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = fed.fed_id WHERE hst.hst_real = ? AND hst.hst_datecreated >= ? AND hst.mbr_id = ? AND sub.mbr_id = ? AND sub.flr_id = ? GROUP BY id ORDER BY nb DESC LIMIT 0,30', array(1, $date_ref, $this->member->mbr_id, $this->member->mbr_id, $flr_id));
				if($query->num_rows() > 0) {
					foreach($query->result() as $row) {
						if($row->direction) {
							$legend[] = '<a style="background-image:url(https://www.google.com/s2/favicons?domain='.$row->fed_host.'&amp;alt=feed);" class="favicon" dir="'.$row->direction.'" href="'.base_url().'subscriptions/read/'.$row->id.'">'.$row->ref.'</a>';
						} else {
							$legend[] = '<a style="background-image:url(https://www.google.com/s2/favicons?domain='.$row->fed_host.'&amp;alt=feed);" class="favicon" href="'.base_url().'subscriptions/read/'.$row->id.'">'.$row->ref.'</a>';
						}
						$values[] = $row->nb;
					}
				}
				$data['tables'] .= build_table_repartition($this->lang->line('items_read_by_subscription').'*', $values, $legend);
			}

			if($this->config->item('tags')) {
				$legend = array();
				$values = array();
				$query = $this->db->query('SELECT cat.cat_title AS ref, COUNT(DISTINCT(hst.itm_id)) AS nb FROM '.$this->db->dbprefix('history').' AS hst LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.itm_id = hst.itm_id LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = itm.fed_id LEFT JOIN '.$this->db->dbprefix('categories').' AS cat ON cat.itm_id = itm.itm_id WHERE cat.cat_id IS NOT NULL AND hst.hst_real = ? AND hst.hst_datecreated >= ? AND hst.mbr_id = ? AND sub.flr_id = ? GROUP BY ref ORDER BY nb DESC LIMIT 0,30', array(1, $date_ref, $this->member->mbr_id, $flr_id));
				if($query->num_rows() > 0) {
					foreach($query->result() as $row) {
						$legend[] = '<i class="icon icon-tag"></i>'.$row->ref;
						$values[] = $row->nb;
					}
				}
				$data['tables'] .= build_table_repartition($this->lang->line('items_read_by_tag').'*', $values, $legend);
			}

			$legend = array();
			$values = array();
			$query = $this->db->query('SELECT '.$substring.'(hst.hst_datecreated, 1, 10) AS ref, COUNT(DISTINCT(hst.itm_id)) AS nb FROM '.$this->db->dbprefix('history').' AS hst LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.itm_id = hst.itm_id LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = itm.fed_id WHERE hst.hst_real = ? AND hst.mbr_id = ? AND sub.flr_id = ? GROUP BY ref ORDER BY ref DESC LIMIT 0,30', array(1, $this->member->mbr_id, $flr_id));
			if($query->num_rows() > 0) {
				foreach($query->result() as $row) {
					$legend[] = '<i class="icon icon-calendar"></i>'.$this->readerself_library->timezone_datetime($row->ref, 'F j, Y');
					$values[] = $row->nb;
				}
			}
			$data['tables'] .= build_table_progression($this->lang->line('items_read_by_day'), $values, $legend);

			$legend = array();
			$values = array();
			$query = $this->db->query('SELECT '.$substring.'(hst.hst_datecreated, 1, 7) AS ref, COUNT(DISTINCT(hst.itm_id)) AS nb FROM '.$this->db->dbprefix('history').' AS hst LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.itm_id = hst.itm_id LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = itm.fed_id WHERE hst.hst_real = ? AND hst.mbr_id = ? AND sub.flr_id = ? GROUP BY ref ORDER BY ref DESC LIMIT 0,12', array(1, $this->member->mbr_id, $flr_id));
			if($query->num_rows() > 0) {
				foreach($query->result() as $row) {
					$legend[] = '<i class="icon icon-calendar"></i>'.$this->readerself_library->timezone_datetime($row->ref, 'F, Y');
					$values[] = $row->nb;
				}
			}
			$data['tables'] .= build_table_progression($this->lang->line('items_read_by_month'), $values, $legend);

			if($this->db->dbdriver == 'mysqli') {
				$days = array(7=>'Sunday', 1=>'Monday', 2=>'Tuesday', 3=>'Wednesday', 4=>'Thursday', 5=>'Friday', 6=>'Saturday');
				$legend = array();
				$values = array();
				$query = $this->db->query('SELECT IF(DATE_FORMAT(DATE_ADD(hst.hst_datecreated, INTERVAL ? HOUR), \'%w\') = 0, 7, DATE_FORMAT(DATE_ADD(hst.hst_datecreated, INTERVAL ? HOUR), \'%w\')) AS ref, COUNT(DISTINCT(hst.itm_id)) AS nb FROM '.$this->db->dbprefix('history').' AS hst LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.itm_id = hst.itm_id LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = itm.fed_id WHERE hst.hst_real = ? AND hst.hst_datecreated >= ? AND hst.mbr_id = ? AND sub.flr_id = ? GROUP BY ref ORDER BY ref ASC', array($this->session->userdata('timezone'), $this->session->userdata('timezone'), 1, $date_ref, $this->member->mbr_id, $flr_id));
				if($query->num_rows() > 0) {
					foreach($query->result() as $row) {
						$temp[$row->ref] = $row->nb;
					}
				}
				foreach($days as $i => $v) {
						$legend[] = '<i class="icon icon-calendar"></i>'.$v;
					if(isset($temp[$i]) == 1) {
						$values[] = $temp[$i];
					} else {
						$values[] = 0;
					}
				}
				$data['tables'] .= build_table_repartition($this->lang->line('items_read_by_day_week').'*', $values, $legend);
			}

			$content = $this->load->view('folders_read', $data, TRUE);
			$this->readerself_library->set_content($content);
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
		$data['flr'] = $this->readerself_model->get_flr_row($flr_id);
		if($data['flr']) {
			$this->form_validation->set_rules('flr_title', 'lang:flr_title', 'required|max_length[255]');
			$this->form_validation->set_rules('direction', 'lang:direction', '');
			if($this->form_validation->run() == FALSE) {
				$content = $this->load->view('folders_update', $data, TRUE);
				$this->readerself_library->set_content($content);
			} else {
				$this->db->set('flr_title', $this->input->post('flr_title'));
				$this->db->set('flr_direction', $this->input->post('direction'));
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
		$data['flr'] = $this->readerself_model->get_flr_row($flr_id);
		if($data['flr']) {
			$this->form_validation->set_rules('confirm', 'lang:confirm', 'required');
			if($this->form_validation->run() == FALSE) {
				$content = $this->load->view('folders_delete', $data, TRUE);
				$this->readerself_library->set_content($content);
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

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

		$data['errors'] = $this->db->query('SELECT COUNT(DISTINCT(fed.fed_id)) AS count FROM '.$this->db->dbprefix('feeds').' AS fed WHERE fed.fed_lasterror IS NOT NULL AND fed.fed_id NOT IN ( SELECT sub.fed_id FROM '.$this->db->dbprefix('subscriptions').' AS sub WHERE sub.fed_id = fed.fed_id AND sub.mbr_id = ? )', array($this->member->mbr_id))->row()->count;

		$data['last_added'] = $this->db->query('SELECT fed.*, fed.fed_direction AS direction FROM '.$this->db->dbprefix('feeds').' AS fed WHERE fed.fed_id NOT IN ( SELECT sub.fed_id FROM '.$this->db->dbprefix('subscriptions').' AS sub WHERE sub.fed_id = fed.fed_id AND sub.mbr_id = ? ) GROUP BY fed.fed_id ORDER BY fed.fed_id DESC LIMIT 0,5', array($this->member->mbr_id))->result();

		$filters = array();
		$filters[$this->router->class.'_feeds_fed_title'] = array('fed.fed_title', 'like');
		if($data['errors'] > 0) {
			$filters[$this->router->class.'_feeds_fed_lasterror'] = array('fed.fed_lasterror', 'notnull');
		}
		$flt = $this->readerself_library->build_filters($filters);
		$flt[] = 'fed.fed_id IS NOT NULL';
		$results = $this->readerself_model->get_feeds_total($flt);
		$build_pagination = $this->readerself_library->build_pagination($results->count, 20, $this->router->class.'_feeds');
		$data['pagination'] = $build_pagination['output'];
		$data['position'] = $build_pagination['position'];
		$data['feeds'] = $this->readerself_model->get_feeds_rows($flt, $build_pagination['limit'], $build_pagination['start'], 'subscribers DESC');

		$content = $this->load->view('feeds_index', $data, TRUE);
		$this->readerself_library->set_content($content);
	}
	public function subscribe($fed_id) {
		if(!$this->session->userdata('mbr_id')) {
			redirect(base_url().'?u='.$this->input->get('u'));
		}

		if($sub = $this->readerself_model->get_subscription_row_by_feed($fed_id)) {
			redirect(base_url().'subscriptions/read/'.$sub->sub_id);
		}

		$this->load->library(array('form_validation'));
		$data = array();

		$data['fed'] = $this->readerself_model->get_feed_row($fed_id);
		if($data['fed']) {
			if($this->config->item('folders')) {
				$query = $this->db->query('SELECT flr.* FROM '.$this->db->dbprefix('folders').' AS flr WHERE flr.mbr_id = ? GROUP BY flr.flr_id ORDER BY flr.flr_title ASC', array($this->member->mbr_id));
				$data['folders'] = array();
				$data['folders'][0] = $this->lang->line('no_folder');
				if($query->num_rows() > 0) {
					foreach($query->result() as $flr) {
						$data['folders'][$flr->flr_id] = $flr->flr_title;
					}
				}
			}

			if($this->config->item('folders')) {
				$this->form_validation->set_rules('folder', 'lang:folder', 'required');
			}
			$this->form_validation->set_rules('priority', 'lang:priority', 'numeric');
			$this->form_validation->set_rules('direction', 'lang:direction', '');

			if($this->form_validation->run() == FALSE) {
				$content = $this->load->view('feeds_subscribe', $data, TRUE);
				$this->readerself_library->set_content($content);
			} else {
				if($this->config->item('folders')) {
					if($this->input->post('folder')) {
						$query = $this->db->query('SELECT flr.* FROM '.$this->db->dbprefix('folders').' AS flr WHERE flr.mbr_id = ? AND flr.flr_id = ? GROUP BY flr.flr_id', array($this->member->mbr_id, $this->input->post('folder')));
						if($query->num_rows() > 0) {
							$this->db->set('flr_id', $this->input->post('folder'));
						}
					}
				}

				$this->db->set('mbr_id', $this->member->mbr_id);
				$this->db->set('fed_id', $fed_id);
				$this->db->set('sub_priority', $this->input->post('priority'));
				$this->db->set('sub_direction', $this->input->post('direction'));
				$this->db->set('sub_datecreated', date('Y-m-d H:i:s'));
				$this->db->insert('subscriptions');

				redirect(base_url().'feeds');
			}
		} else {
			$this->index();
		}
	}
	public function read($fed_id) {
		if(!$this->session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$data = array();
		$data['fed'] = $this->readerself_model->get_feed_row($fed_id);
		if($data['fed']) {

			$data['last_added'] = $this->db->query('SELECT fed.*, fed.fed_direction AS direction FROM '.$this->db->dbprefix('feeds').' AS fed WHERE fed.fed_id NOT IN ( SELECT sub.fed_id FROM '.$this->db->dbprefix('subscriptions').' AS sub WHERE sub.fed_id = fed.fed_id AND sub.mbr_id = ? ) GROUP BY fed.fed_id ORDER BY fed.fed_id DESC LIMIT 0,5', array($this->member->mbr_id))->result();

			$data['tables'] = '';

			$date_ref = date('Y-m-d H:i:s', time() - 3600 * 24 * 30);

			if($this->config->item('tags')) {
				$legend = array();
				$values = array();
				$query = $this->db->query('SELECT cat.cat_title AS ref, COUNT(DISTINCT(itm.itm_id)) AS nb FROM '.$this->db->dbprefix('items').' AS itm LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = itm.fed_id LEFT JOIN '.$this->db->dbprefix('categories').' AS cat ON cat.itm_id = itm.itm_id WHERE cat.cat_id IS NOT NULL AND itm.itm_date >= ? AND fed.fed_id = ? GROUP BY ref ORDER BY nb DESC LIMIT 0,30', array($date_ref, $fed_id));
				if($query->num_rows() > 0) {
					foreach($query->result() as $row) {
						$legend[] = '<i class="icon icon-tag"></i>'.$row->ref;
						$values[] = $row->nb;
					}
				}
				$data['tables'] .= build_table_repartition($this->lang->line('items_posted_by_tag').'*', $values, $legend);
			}

			$legend = array();
			$values = array();
			$query = $this->db->query('SELECT itm.itm_author AS ref, COUNT(DISTINCT(itm.itm_id)) AS nb FROM '.$this->db->dbprefix('items').' AS itm LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = itm.fed_id LEFT JOIN '.$this->db->dbprefix('categories').' AS cat ON cat.itm_id = itm.itm_id WHERE itm.itm_author IS NOT NULL AND itm.itm_date >= ? AND fed.fed_id = ? GROUP BY ref ORDER BY nb DESC LIMIT 0,30', array($date_ref, $fed_id));
			if($query->num_rows() > 0) {
				foreach($query->result() as $row) {
					$legend[] = '<i class="icon icon-pencil"></i>'.$row->ref;
					$values[] = $row->nb;
				}
			}
			$data['tables'] .= build_table_repartition($this->lang->line('items_posted_by_author').'*', $values, $legend);

			$legend = array();
			$values = array();
			$query = $this->db->query('SELECT SUBSTRING(DATE_ADD(itm.itm_date, INTERVAL ? HOUR), 1, 10) AS ref, COUNT(DISTINCT(itm.itm_id)) AS nb FROM '.$this->db->dbprefix('items').' AS itm LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = itm.fed_id WHERE fed.fed_id = ? GROUP BY ref ORDER BY ref DESC LIMIT 0,30', array($this->session->userdata('timezone'), $fed_id));
			if($query->num_rows() > 0) {
				foreach($query->result() as $row) {
					$legend[] = '<i class="icon icon-calendar"></i>'.date('F j, Y', strtotime($row->ref));
					$values[] = $row->nb;
				}
			}
			$data['tables'] .= build_table_progression($this->lang->line('items_posted_by_day'), $values, $legend);

			$legend = array();
			$values = array();
			$query = $this->db->query('SELECT SUBSTRING(DATE_ADD(itm.itm_date, INTERVAL ? HOUR), 1, 7) AS ref, COUNT(DISTINCT(itm.itm_id)) AS nb FROM '.$this->db->dbprefix('items').' AS itm LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = itm.fed_id WHERE fed.fed_id = ? GROUP BY ref ORDER BY ref DESC LIMIT 0,12', array($this->session->userdata('timezone'), $fed_id));
			if($query->num_rows() > 0) {
				foreach($query->result() as $row) {
					$legend[] = '<i class="icon icon-calendar"></i>'.date('F, Y', strtotime($row->ref));
					$values[] = $row->nb;
				}
			}
			$data['tables'] .= build_table_progression($this->lang->line('items_posted_by_month'), $values, $legend);

			$days = array(7=>'Sunday', 1=>'Monday', 2=>'Tuesday', 3=>'Wednesday', 4=>'Thursday', 5=>'Friday', 6=>'Saturday');
			$legend = array();
			$values = array();
			$query = $this->db->query('SELECT IF(DATE_FORMAT(DATE_ADD(itm.itm_date, INTERVAL ? HOUR), \'%w\') = 0, 7, DATE_FORMAT(DATE_ADD(itm.itm_date, INTERVAL ? HOUR), \'%w\')) AS ref, COUNT(DISTINCT(itm.itm_id)) AS nb FROM '.$this->db->dbprefix('items').' AS itm LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = itm.fed_id WHERE itm.itm_date >= ? AND fed.fed_id = ? GROUP BY ref ORDER BY ref ASC', array($this->session->userdata('timezone'), $this->session->userdata('timezone'), $date_ref, $fed_id));
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
			$data['tables'] .= build_table_repartition($this->lang->line('items_posted_by_day_week').'*', $values, $legend);

			$content = $this->load->view('feeds_read', $data, TRUE);
			$this->readerself_library->set_content($content);
		} else {
			$this->index();
		}
	}
	public function update($fed_id) {
		if(!$this->session->userdata('mbr_id') || $this->member->mbr_administrator == 0) {
			redirect(base_url());
		}

		$this->load->library('form_validation');
		$data = array();
		$data['fed'] = $this->readerself_model->get_feed_row($fed_id);
		if($data['fed']) {
			$this->form_validation->set_rules('fed_link', 'lang:url', 'required|max_length[255]');
			$this->form_validation->set_rules('direction', 'lang:direction', '');
			if($this->form_validation->run() == FALSE) {
				$content = $this->load->view('feeds_update', $data, TRUE);
				$this->readerself_library->set_content($content);
			} else {
				$this->db->set('fed_link', $this->input->post('fed_link'));
				$this->db->set('fed_direction', $this->input->post('direction'));
				$this->db->where('fed_id', $fed_id);
				$this->db->update('feeds');

				redirect(base_url().'feeds');
			}
		} else {
			$this->index();
		}
	}
	public function delete($fed_id) {
		if(!$this->session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$this->load->library('form_validation');
		$data = array();
		$data['fed'] = $this->readerself_model->get_feed_row($fed_id);
		if($data['fed']) {
			if($data['fed']->subscribers == 0) {
				$this->form_validation->set_rules('confirm', 'lang:confirm', 'required');
				if($this->form_validation->run() == FALSE) {
					$content = $this->load->view('feeds_delete', $data, TRUE);
					$this->readerself_library->set_content($content);
				} else {
					//$query = $this->db->query('DELETE FROM '.$this->db->dbprefix('categories').' WHERE itm_id IN ( SELECT itm.itm_id FROM '.$this->db->dbprefix('items').' AS itm WHERE itm.fed_id = ? GROUP BY itm.itm_id )', array($fed_id));
					//$query = $this->db->query('DELETE FROM '.$this->db->dbprefix('enclosures').' WHERE itm_id IN ( SELECT itm.itm_id FROM '.$this->db->dbprefix('items').' AS itm WHERE itm.fed_id = ? GROUP BY itm.itm_id )', array($fed_id));
					//$query = $this->db->query('DELETE FROM '.$this->db->dbprefix('favorites').' WHERE itm_id IN ( SELECT itm.itm_id FROM '.$this->db->dbprefix('items').' AS itm WHERE itm.fed_id = ? GROUP BY itm.itm_id )', array($fed_id));
					//$query = $this->db->query('DELETE FROM '.$this->db->dbprefix('history').' WHERE itm_id IN ( SELECT itm.itm_id FROM '.$this->db->dbprefix('items').' AS itm WHERE itm.fed_id = ? GROUP BY itm.itm_id )', array($fed_id));
					//$query = $this->db->query('DELETE FROM '.$this->db->dbprefix('share').' WHERE itm_id IN ( SELECT itm.itm_id FROM '.$this->db->dbprefix('items').' AS itm WHERE itm.fed_id = ? GROUP BY itm.itm_id )', array($fed_id));

					$this->db->where('fed_id', $fed_id);
					$this->db->delete('items');

					$this->db->where('fed_id', $fed_id);
					$this->db->delete('feeds');

					//$this->db->query('OPTIMIZE TABLE categories, enclosures, favorites, history, share, items, feeds');

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

		$this->readerself_library->set_template('_opml');
		$this->readerself_library->set_content_type('application/xml');

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
		$this->readerself_library->set_content($content);
	}
}

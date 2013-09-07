<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subscriptions extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function index() {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$filters = array();
		$filters[$this->router->class.'_subscriptions_fed_title'] = array('fed.fed_title', 'like');
		$flt = $this->reader_library->build_filters($filters);
		$flt[] = 'sub.mbr_id = \''.$this->member->mbr_id.'\'';
		$flt[] = 'fed.fed_id IS NOT NULL';
		$columns = array();
		$columns[] = 'fed.fed_title';
		$columns[] = 'fed.fed_description';
		$columns[] = 'fed.fed_url';
		$columns[] = 'subscribers';
		if($this->config->item('folders')) {
			$columns[] = 'flr.flr_title';
		}
		$columns[] = 'fed.fed_lastcrawl';
		$columns[] = 'sub.sub_datecreated';
		$col = $this->reader_library->build_columns($this->router->class.'_subscriptions', $columns, 'fed.fed_title', 'ASC');
		$results = $this->reader_model->get_subscriptions_total($flt);
		$build_pagination = $this->reader_library->build_pagination($results->count, 20, $this->router->class.'_subscriptions');
		$data = array();
		$data['columns'] = $col;
		$data['pagination'] = $build_pagination['output'];
		$data['position'] = $build_pagination['position'];
		$data['subscriptions'] = $this->reader_model->get_subscriptions_rows($flt, $build_pagination['limit'], $build_pagination['start'], $this->router->class.'_subscriptions');

		$content = $this->load->view('subscriptions_index', $data, TRUE);
		$this->reader_library->set_content($content);
	}

	public function create() {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$data = array();

		$content = array();

		$this->load->library(array('form_validation'));

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

		$this->form_validation->set_rules('url', 'lang:url_feed', 'required');
		if($this->config->item('folders')) {
			$this->form_validation->set_rules('folder', 'lang:folder', 'required');
		}

		$data['error'] = false;

		if($this->form_validation->run() == FALSE) {
			$content = $this->load->view('subscriptions_create', $data, TRUE);
		} else {
			if($this->config->item('folders')) {
				$folder = false;
				if($this->input->post('folder')) {
					$query = $this->db->query('SELECT flr.* FROM '.$this->db->dbprefix('folders').' AS flr WHERE flr.mbr_id = ? AND flr.flr_id = ? GROUP BY flr.flr_id', array($this->member->mbr_id, $this->input->post('folder')));
					if($query->num_rows() > 0) {
						$folder = $this->input->post('folder');
					}
				}
			}

			$query = $this->db->query('SELECT fed.*, sub.sub_id, IF(sub.sub_id IS NULL, 0, 1) AS subscription FROM '.$this->db->dbprefix('feeds').' AS fed LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = fed.fed_id AND sub.mbr_id = ? WHERE fed.fed_link = ? GROUP BY fed.fed_id', array($this->member->mbr_id, $this->input->post('url')));
			if($query->num_rows() == 0) {
				include_once('thirdparty/simplepie/autoloader.php');
				include_once('thirdparty/simplepie/idn/idna_convert.class.php');

				$sp_feed = new SimplePie();
				$sp_feed->set_feed_url(convert_to_ascii($this->input->post('url')));
				$sp_feed->enable_cache(false);
				$sp_feed->set_timeout(60);
				$sp_feed->force_feed(true);
				$sp_feed->init();
				$sp_feed->handle_content_type();

				if($sp_feed->error()) {
					$data['error'] = $sp_feed->error();

				} else {
					$this->db->set('fed_title', $sp_feed->get_title());
					$this->db->set('fed_url', $sp_feed->get_link());
					$this->db->set('fed_description', $sp_feed->get_description());
					$this->db->set('fed_link', $sp_feed->subscribe_url());
					$this->db->set('fed_lastcrawl', date('Y-m-d H:i:s'));
					$this->db->set('fed_datecreated', date('Y-m-d H:i:s'));
					$this->db->insert('feeds');
					$fed_id = $this->db->insert_id();

					$this->db->set('mbr_id', $this->member->mbr_id);
					$this->db->set('fed_id', $fed_id);
					if($this->config->item('folders')) {
						if($folder) {
							$this->db->set('flr_id', $folder);
						}
					}
					$this->db->set('sub_datecreated', date('Y-m-d H:i:s'));
					$this->db->insert('subscriptions');
					$sub_id = $this->db->insert_id();

					$data['sub_id'] = $sub_id;
					$data['fed_title'] = $sp_feed->get_title();

					$this->reader_library->crawl_items($fed_id, $sp_feed->get_items());
				}
				$sp_feed->__destruct();
				unset($sp_feed);
			} else {
				$fed = $query->row();
				if($fed->subscription == 0) {
					$this->db->set('mbr_id', $this->member->mbr_id);
					$this->db->set('fed_id', $fed->fed_id);
					if($this->config->item('folders')) {
						if($folder) {
							$this->db->set('flr_id', $folder);
						}
					}
					$this->db->set('sub_datecreated', date('Y-m-d H:i:s'));
					$this->db->insert('subscriptions');
					$sub_id = $this->db->insert_id();
				} else {
					$sub_id = $fed->sub_id;
				}
				$data['sub_id'] = $sub_id;
				$data['fed_title'] = $fed->fed_title;
			}
			if($data['error']) {
				$content = $this->load->view('subscriptions_create', $data, TRUE);
			} else {
				redirect(base_url().'subscriptions');
			}
		}
		$this->reader_library->set_content($content);
	}

	public function read($sub_id) {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$data = array();
		$data['sub'] = $this->reader_model->get_subscription_row($sub_id);
		if($data['sub']) {

			$data['tables'] = '';

			$date_ref = date('Y-m-d H:i:s', time() - 3600 * 24 * 30);

			if($this->config->item('tags')) {
				$legend = array();
				$values = array();
				$query = $this->db->query('SELECT cat.cat_title AS ref, COUNT(DISTINCT(hst.itm_id)) AS nb FROM '.$this->db->dbprefix('history').' AS hst LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.itm_id = hst.itm_id LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = itm.fed_id LEFT JOIN '.$this->db->dbprefix('categories').' AS cat ON cat.itm_id = itm.itm_id WHERE cat.cat_id IS NOT NULL AND hst.hst_real = ? AND hst.hst_datecreated >= ? AND hst.mbr_id = ? AND sub.sub_id = ? GROUP BY ref ORDER BY nb DESC LIMIT 0,30', array(1, $date_ref, $this->member->mbr_id, $sub_id));
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
			$query = $this->db->query('SELECT itm.itm_author AS ref, COUNT(DISTINCT(hst.itm_id)) AS nb FROM '.$this->db->dbprefix('history').' AS hst LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.itm_id = hst.itm_id LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = itm.fed_id LEFT JOIN '.$this->db->dbprefix('categories').' AS cat ON cat.itm_id = itm.itm_id WHERE itm.itm_author IS NOT NULL AND hst.hst_real = ? AND hst.hst_datecreated >= ? AND hst.mbr_id = ? AND sub.sub_id = ? GROUP BY ref ORDER BY nb DESC LIMIT 0,30', array(1, $date_ref, $this->member->mbr_id, $sub_id));
			if($query->num_rows() > 0) {
				foreach($query->result() as $row) {
					$legend[] = $row->ref;
					$values[] = $row->nb;
				}
			}
			$data['tables'] .= build_table_repartition($this->lang->line('items_read_by_author').'*', $values, $legend);

			$legend = array();
			$values = array();
			$query = $this->db->query('SELECT SUBSTRING(DATE_ADD(hst.hst_datecreated, INTERVAL ? HOUR), 1, 10) AS ref, COUNT(DISTINCT(hst.itm_id)) AS nb FROM '.$this->db->dbprefix('history').' AS hst LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.itm_id = hst.itm_id LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = itm.fed_id WHERE hst.hst_real = ? AND hst.mbr_id = ? AND sub.sub_id = ? GROUP BY ref ORDER BY ref DESC LIMIT 0,30', array($this->session->userdata('timezone'), 1, $this->member->mbr_id, $sub_id));
			if($query->num_rows() > 0) {
				foreach($query->result() as $row) {
					$legend[] = date('F j, Y', strtotime($row->ref));
					$values[] = $row->nb;
				}
			}
			$data['tables'] .= build_table_progression($this->lang->line('items_read_by_day'), $values, $legend);

			$legend = array();
			$values = array();
			$query = $this->db->query('SELECT SUBSTRING(DATE_ADD(hst.hst_datecreated, INTERVAL ? HOUR), 1, 7) AS ref, COUNT(DISTINCT(hst.itm_id)) AS nb FROM '.$this->db->dbprefix('history').' AS hst LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.itm_id = hst.itm_id LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = itm.fed_id WHERE hst.hst_real = ? AND hst.mbr_id = ? AND sub.sub_id = ? GROUP BY ref ORDER BY ref DESC LIMIT 0,12', array($this->session->userdata('timezone'), 1, $this->member->mbr_id, $sub_id));
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
				$query = $this->db->query('SELECT SUBSTRING(DATE_ADD(fav.fav_datecreated, INTERVAL ? HOUR), 1, 7) AS ref, COUNT(DISTINCT(fav.itm_id)) AS nb FROM '.$this->db->dbprefix('favorites').' AS fav LEFT JOIN '.$this->db->dbprefix('items').' AS itm ON itm.itm_id = fav.itm_id LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = itm.fed_id WHERE fav.mbr_id = ? AND sub.sub_id = ? GROUP BY ref ORDER BY ref DESC LIMIT 0,12', array($this->session->userdata('timezone'), $this->member->mbr_id, $sub_id));
				if($query->num_rows() > 0) {
					foreach($query->result() as $row) {
						$legend[] = date('F, Y', strtotime($row->ref));
						$values[] = $row->nb;
					}
				}
				$data['tables'] .= build_table_progression($this->lang->line('items_starred_by_month'), $values, $legend);
			}

			$content = $this->load->view('subscriptions_read', $data, TRUE);
			$this->reader_library->set_content($content);
		} else {
			$this->index();
		}
	}

	public function update($sub_id) {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$this->load->library('form_validation');
		$data = array();
		$data['sub'] = $this->reader_model->get_subscription_row($sub_id);
		if($data['sub']) {
			$query = $this->db->query('SELECT flr.* FROM '.$this->db->dbprefix('folders').' AS flr WHERE flr.mbr_id = ? GROUP BY flr.flr_id ORDER BY flr.flr_title ASC', array($this->member->mbr_id));
			$data['folders'] = array();
			$data['folders'][0] = $this->lang->line('no_folder');
			if($query->num_rows() > 0) {
				foreach($query->result() as $flr) {
					$data['folders'][$flr->flr_id] = $flr->flr_title;
				}
			}

			$this->form_validation->set_rules('sub_title', 'lang:sub_title', 'max_length[255]');
			$this->form_validation->set_rules('folder', 'lang:folder', 'required');
			if($this->form_validation->run() == FALSE) {
				$content = $this->load->view('subscriptions_update', $data, TRUE);
				$this->reader_library->set_content($content);
			} else {
				$this->db->set('sub_title', $this->input->post('sub_title'));
				if($this->input->post('folder') == 0) {
					$this->db->set('flr_id', '');
				} else {
					$query = $this->db->query('SELECT flr.* FROM '.$this->db->dbprefix('folders').' AS flr WHERE flr.mbr_id = ? AND flr.flr_id = ? GROUP BY flr.flr_id', array($this->member->mbr_id, $this->input->post('folder')));
					if($query->num_rows() > 0) {
						$this->db->set('flr_id', $this->input->post('folder'));
					}
				}
				$this->db->where('sub_id', $sub_id);
				$this->db->update('subscriptions');

				redirect(base_url().'subscriptions/read/'.$sub_id);
			}
		} else {
			$this->index();
		}
	}

	public function delete($sub_id) {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$this->load->library('form_validation');
		$data = array();
		$data['sub'] = $this->reader_model->get_subscription_row($sub_id);
		if($data['sub']) {
			$this->form_validation->set_rules('confirm', 'lang:confirm', 'required');
			if($this->form_validation->run() == FALSE) {
				$content = $this->load->view('subscriptions_delete', $data, TRUE);
				$this->reader_library->set_content($content);
			} else {
				$this->db->where('sub_id', $sub_id);
				$this->db->delete('subscriptions');

				redirect(base_url().'subscriptions');
			}
		} else {
			$this->index();
		}
	}
}

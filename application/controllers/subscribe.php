<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subscribe extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function index() {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$data = array();

		$content = array();

		if($this->input->is_ajax_request()) {
			$this->reader_library->set_template('_json');
			$this->reader_library->set_content_type('application/json');

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
				$content['modal'] = $this->load->view('subscribe_index', $data, TRUE);
			} else {
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
							if($this->input->post('folder') == 0) {
								$this->db->set('flr_id', '');
							} else {
								$query = $this->db->query('SELECT flr.* FROM '.$this->db->dbprefix('folders').' AS flr WHERE flr.mbr_id = ? AND flr.flr_id = ? GROUP BY flr.flr_id', array($this->member->mbr_id, $this->input->post('folder')));
								if($query->num_rows() > 0) {
									$this->db->set('flr_id', $this->input->post('folder'));
								}
							}
						}
						$this->db->set('sub_datecreated', date('Y-m-d H:i:s'));
						$this->db->insert('subscriptions');
						$sub_id = $this->db->insert_id();

						$data['sub_id'] = $sub_id;
						$data['fed_title'] = $sp_feed->get_title();

						foreach($sp_feed->get_items() as $sp_item) {
							$query = $this->db->query('SELECT * FROM '.$this->db->dbprefix('items').' AS itm WHERE itm.itm_link = ? GROUP BY itm.itm_id', array($sp_item->get_link()));
							if($query->num_rows() == 0) {
								$this->db->set('fed_id', $fed_id);

								if($sp_item->get_title()) {
									$this->db->set('itm_title', $sp_item->get_title());
								} else {
									$this->db->set('itm_title', '-');
								}

								if($author = $sp_item->get_author()) {
									$this->db->set('itm_author', $author->get_name());
								}

								$this->db->set('itm_link', $sp_item->get_link());

								if($sp_item->get_content()) {
									$this->db->set('itm_content', $sp_item->get_content());
								} else {
									$this->db->set('itm_content', '-');
								}

								$sp_itm_date = $sp_item->get_gmdate('Y-m-d H:i:s');
								if($sp_itm_date) {
									$this->db->set('itm_date', $sp_itm_date);
								} else {
									$this->db->set('itm_date', date('Y-m-d H:i:s'));
								}

								$this->db->set('itm_datecreated', date('Y-m-d H:i:s'));

								$this->db->insert('items');

								$itm_id = $this->db->insert_id();

								foreach($sp_item->get_categories() as $category) {
									if($category->get_label()) {
										$this->db->set('itm_id', $itm_id);
										$this->db->set('cat_title', $category->get_label());
										$this->db->set('cat_datecreated', date('Y-m-d H:i:s'));
										$this->db->insert('categories');
									}
								}

								foreach($sp_item->get_enclosures() as $enclosure) {
									if($enclosure->get_link() && $enclosure->get_type() && $enclosure->get_length()) {
										$this->db->set('itm_id', $itm_id);
										$this->db->set('enr_link', $enclosure->get_link());
										$this->db->set('enr_type', $enclosure->get_type());
										$this->db->set('enr_length', $enclosure->get_length());
										$this->db->set('enr_datecreated', date('Y-m-d H:i:s'));
										$this->db->insert('enclosures');
									}
								}

							} else {
								break;
							}
							unset($sp_item);
						}
					}
					$sp_feed->__destruct();
					unset($feed);
				} else {
					$fed = $query->row();
					if($fed->subscription == 0) {
						$this->db->set('mbr_id', $this->member->mbr_id);
						$this->db->set('fed_id', $fed->fed_id);
						if($this->config->item('folders')) {
							if($this->input->post('folder') == 0) {
								$this->db->set('flr_id', '');
							} else {
								$query = $this->db->query('SELECT flr.* FROM '.$this->db->dbprefix('folders').' AS flr WHERE flr.mbr_id = ? AND flr.flr_id = ? GROUP BY flr.flr_id', array($this->member->mbr_id, $this->input->post('folder')));
								if($query->num_rows() > 0) {
									$this->db->set('flr_id', $this->input->post('folder'));
								}
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
					$content['modal'] = $this->load->view('subscribe_index', $data, TRUE);
				} else {
					$content['modal'] = $this->load->view('subscribe_confirm', $data, TRUE);
				}
			}
		} else {
			$this->output->set_status_header(403);
		}
		$this->reader_library->set_content($content);
	}
}

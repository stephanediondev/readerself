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

			$this->form_validation->set_rules('url', 'lang:url', 'required');

			if($this->form_validation->run() == FALSE) {
			} else {
				$query = $this->db->query('SELECT fed.*, sub.sub_id, IF(sub.sub_id IS NULL, 0, 1) AS subscription FROM '.$this->db->dbprefix('feeds').' AS fed LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = fed.fed_id AND sub.mbr_id = ? WHERE fed.fed_link = ? GROUP BY fed.fed_id', array($this->member->mbr_id, $this->input->post('url')));
				if($query->num_rows() == 0) {
					include_once('thirdparty/simplepie/autoloader.php');
					include_once('thirdparty/simplepie/idn/idna_convert.class.php');

					$sp_feed = new SimplePie();
					$sp_feed->set_feed_url($this->input->post('url'));
					$sp_feed->enable_cache(false);
					$sp_feed->set_timeout(60);
					$sp_feed->force_feed(true);
					$sp_feed->init();
					$sp_feed->handle_content_type();

					if($sp_feed->error()) {
						$data['alert'] = array('type'=>'error', 'message'=>$sp_feed->error());

					} else {
						$this->db->set('fed_title', $sp_feed->get_title());
						$this->db->set('fed_url', $sp_feed->get_link());
						$this->db->set('fed_description', $sp_feed->get_description());
						$this->db->set('fed_link', $sp_feed->subscribe_url());
						$this->db->set('fed_datecreated', date('Y-m-d H:i:s'));
						$this->db->insert('feeds');
						$fed_id = $this->db->insert_id();

						$this->db->set('mbr_id', $this->member->mbr_id);
						$this->db->set('fed_id', $fed_id);
						$this->db->set('sub_datecreated', date('Y-m-d H:i:s'));
						$this->db->insert('subscriptions');
						$sub_id = $this->db->insert_id();

						$data['alert'] = array('type'=>'success', 'message'=>'Added');
						$content['result_subscribe'] = array('sub_id'=>$sub_id, 'fed_title'=>$sp_feed->get_title());

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
						$this->db->set('sub_datecreated', date('Y-m-d H:i:s'));
						$this->db->insert('subscriptions');
						$sub_id = $this->db->insert_id();
					} else {
						$sub_id = $fed->sub_id;
					}
					$data['alert'] = array('type'=>'success', 'message'=>'Added');
					$content['result_subscribe'] = array('sub_id'=>$sub_id, 'fed_title'=>$fed->fed_title);
				}
			}
			$content['modal'] = $this->load->view('subscribe_index', $data, TRUE);
		} else {
			$this->output->set_status_header(403);
		}
		$this->reader_library->set_content($content);
	}
}

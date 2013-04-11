<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function index() {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$this->load->library('form_validation');

		$query = $this->db->query('SELECT tag.* FROM '.$this->db->dbprefix('tags').' AS tag WHERE tag.mbr_id = ? GROUP BY tag.tag_id HAVING (SELECT COUNT(DISTINCT(count_sub.sub_id)) FROM '.$this->db->dbprefix('subscriptions').' AS count_sub WHERE count_sub.tag_id = tag.tag_id) > 0 ORDER BY tag.tag_title ASC', array($this->member->mbr_id));

		$data = array();
		$data['tags'] = $query->result();
		$content = $this->load->view('home_index', $data, TRUE);
		$this->reader_library->set_content($content);
	}
	public function timezone() {
		$content = array();

		if($this->input->is_ajax_request()) {
			$this->reader_library->set_template('_json');
			$this->reader_library->set_content_type('application/json');

			$this->session->set_userdata('timezone', $this->input->post('timezone'));
		} else {
			$this->output->set_status_header(403);
		}
		$this->reader_library->set_content($content);
	}
	function subscriptions() {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		if($this->input->is_ajax_request()) {
			$this->reader_library->set_template('_json');
			$this->reader_library->set_content_type('application/json');
			$content = array();

			if($this->input->post('fed_title')) {
				$query = $this->db->query('SELECT sub.sub_id, fed.* FROM '.$this->db->dbprefix('subscriptions').' AS sub LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = sub.fed_id WHERE sub.mbr_id = ? AND fed.fed_title LIKE ? GROUP BY fed.fed_id ORDER BY fed.fed_title ASC', array($this->member->mbr_id, '%'.$this->input->post('fed_title').'%'));
				$content['subscriptions'] = $query->result();
			} else {
				$content['subscriptions'] = array();
			}
		} else {
			$this->output->set_status_header(403);
		}
		$this->reader_library->set_content($content);
	}
	public function items($mode, $id = FALSE) {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$modes = array('all', 'starred', 'notag', 'tag', 'sub');

		$content = array();

		$is_tag = FALSE;
		if($mode == 'tag') {
			$query = $this->db->query('SELECT tag.* FROM '.$this->db->dbprefix('tags').' AS tag WHERE tag.mbr_id = ? AND tag.tag_id = ? GROUP BY tag.tag_id', array($this->member->mbr_id, $id));
			if($query->num_rows() > 0) {
				$is_tag = $id;
			}
		}

		$is_sub = FALSE;
		if($mode == 'sub') {
			$query = $this->db->query('SELECT sub.* FROM '.$this->db->dbprefix('subscriptions').' AS sub WHERE sub.mbr_id = ? AND sub.sub_id = ? GROUP BY sub.sub_id', array($this->member->mbr_id, $id));
			if($query->num_rows() > 0) {
				$is_sub = $id;
			}
		}

		if($this->input->is_ajax_request() && in_array($mode, $modes)) {
			$this->reader_library->set_template('_json');
			$this->reader_library->set_content_type('application/json');

			$this->session->set_userdata('items-mode', $mode);
			$this->session->set_userdata('items-id', $id);

			$content['items'] = array();

			$where = array();
			$bindings = array();

			if($this->session->userdata('timezone')) {
				$bindings[] = $this->session->userdata('timezone');
			} else {
				$bindings[] = 0;
			}
			$bindings[] = $this->member->mbr_id;
			$bindings[] = $this->member->mbr_id;
			$bindings[] = $this->member->mbr_id;

			if($mode == 'starred') {
				$where[] = 'fav.fav_id IS NOT NULL';
			} else {
				$where[] = 'hst.hst_id IS NULL';
			}

			if($is_tag) {
				$where[] = 'sub.tag_id = ?';
				$bindings[] = $is_tag;
			}

			if($is_sub) {
				$where[] = 'sub.sub_id = ?';
				$bindings[] = $is_sub;
			}

			if($mode == 'notag') {
				$where[] = 'sub.tag_id IS NULL';
			}

			$where[] = 'sub.mbr_id = ?';
			$bindings[] = $this->member->mbr_id;

			$sql = 'SELECT sub.sub_id, tag.tag_id, tag.tag_title, itm.*, fed.*, DATE_ADD(itm.itm_date, INTERVAL ? HOUR) AS itm_date, IF(fav.fav_id IS NULL, 0, 1) AS star, IF(hst.hst_id IS NULL, \'unread\', \'read\') AS history
			FROM '.$this->db->dbprefix('items').' AS itm
			LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = itm.fed_id AND sub.mbr_id = ?
			LEFT JOIN '.$this->db->dbprefix('tags').' AS tag ON tag.tag_id = sub.tag_id
			LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = itm.fed_id
			LEFT JOIN '.$this->db->dbprefix('favorites').' AS fav ON fav.itm_id = itm.itm_id AND fav.mbr_id = ?
			LEFT JOIN '.$this->db->dbprefix('history').' AS hst ON hst.itm_id = itm.itm_id AND hst.mbr_id = ?
			WHERE '.implode(' AND ', $where).'
			GROUP BY itm.itm_id
			ORDER BY itm.itm_date DESC
			LIMIT 0,10';
			$query = $this->db->query($sql, $bindings);
			$content['total'] = $query->num_rows();
			if($query->num_rows() > 0) {
				foreach($query->result() as $itm) {
					list($itm->explode_date, $itm->explode_time) = explode(' ', $itm->itm_date);

					//$itm->itm_content = strip_tags($itm->itm_content, '<dt><dd><dl><table><caption><tr><th><td><tbody><thead><h2><h3><h4><h5><h6><strong><em><code><pre><blockquote><p><ul><li><ol><br><del><a><img><figure><figcaption><cite><time><abbr>');

					preg_match_all('/<a[^>]+>/i', $itm->itm_content, $result);
					foreach($result[0] as $tag_a) {
						if(!preg_match('/(target)=("[^"]*")/i', $tag_a, $result)) {
							$itm->itm_content = str_replace($tag_a, str_replace('<a', '<a target="_blank"', $tag_a), $itm->itm_content);
						}
					}

					preg_match_all('/<img[^>]+>/i', $itm->itm_content, $result);
					foreach($result[0] as $tag_img) {
						$attribute_src = false;
						if(preg_match('/(src)=("[^"]*")/i', $tag_img, $result)) {
							$attribute_src = str_replace('"', '', $result[2]);
						}

						$attribute_width = false;
						if(preg_match('/(width)=("[^"]*")/i', $tag_img, $result)) {
							$attribute_width = str_replace('"', '', $result[2]);
						}

						$attribute_height = false;
						if(preg_match('/(height)=("[^"]*")/i', $tag_img, $result)) {
							$attribute_height = str_replace('"', '', $result[2]);
						}

						if($attribute_width == 1 || $attribute_height == 1 || stristr($attribute_src, 'feedsportal.com') || stristr($attribute_src, 'feedburner.com')) {
							$itm->itm_content = str_replace($tag_img, '', $itm->itm_content);
						}
					}

					$content['items'][$itm->itm_id] = array('itm_id' => $itm->itm_id, 'itm_content' => $this->load->view('item', array('itm'=>$itm), TRUE));
				}
			} else {
				if($mode == 'starred') {
					$content['noitems'] = '<div class="alert alert-info alert-block">No starred items</div>';
				} else {
					$content['noitems'] = '<div class="alert alert-info alert-block">No unread items</div>';
				}
			}
		} else {
			$this->output->set_status_header(403);
		}
		$this->reader_library->set_content($content);
	}
	public function star($itm_id) {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$content = array();

		if($this->input->is_ajax_request()) {
			$this->reader_library->set_template('_json');
			$this->reader_library->set_content_type('application/json');

			$query = $this->db->query('SELECT * FROM '.$this->db->dbprefix('favorites').' AS fav WHERE fav.itm_id = ? AND fav.mbr_id = ? GROUP BY fav.fav_id', array($itm_id, $this->member->mbr_id));
			if($query->num_rows() > 0) {
				$this->db->where('itm_id', $itm_id);
				$this->db->where('mbr_id', $this->member->mbr_id);
				$this->db->delete('favorites');
				$content['status'] = 'unstar';
			} else {
				$this->db->set('itm_id', $itm_id);
				$this->db->set('mbr_id', $this->member->mbr_id);
				$this->db->set('fav_datecreated', date('Y-m-d H:i:s'));
				$this->db->insert('favorites');
				$content['status'] = 'star';
			}
		} else {
			$this->output->set_status_header(403);
		}
		$this->reader_library->set_content($content);
	}
	public function history($mode, $id, $auto = FALSE) {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$modes = array('dialog', 'toggle');

		$data = array();

		$content = array();

		if($this->input->is_ajax_request() && in_array($mode, $modes)) {
			$this->reader_library->set_template('_json');
			$this->reader_library->set_content_type('application/json');

			if($mode == 'dialog' && $this->session->userdata('items-mode')) {
				$this->load->library(array('form_validation'));

				$this->form_validation->set_rules('age', 'lang:age', 'required');

				if($this->form_validation->run() == FALSE) {
					$content['modal'] = $this->load->view('home_history', $data, TRUE);
				} else {

					$is_tag = FALSE;
					if($this->session->userdata('items-mode') == 'tag') {
						$query = $this->db->query('SELECT tag.* FROM '.$this->db->dbprefix('tags').' AS tag WHERE tag.mbr_id = ? AND tag.tag_id = ? GROUP BY tag.tag_id', array($this->member->mbr_id, $this->session->userdata('items-id')));
						if($query->num_rows() > 0) {
							$is_tag = $this->session->userdata('items-id');
						}
					}

					$is_sub = FALSE;
					if($this->session->userdata('items-mode') == 'sub') {
						$query = $this->db->query('SELECT sub.* FROM '.$this->db->dbprefix('subscriptions').' AS sub WHERE sub.mbr_id = ? AND sub.sub_id = ? GROUP BY sub.sub_id', array($this->member->mbr_id, $this->session->userdata('items-id')));
						if($query->num_rows() > 0) {
							$is_sub = $this->session->userdata('items-id');
						}
					}

					$where = array();
					$bindings = array();

					$where[] = 'hst.hst_id IS NULL';

					$bindings[] = $this->member->mbr_id;
					$bindings[] = date('Y-m-d H:i:s');
					$bindings[] = $this->member->mbr_id;
					$bindings[] = $this->member->mbr_id;
					$bindings[] = $this->member->mbr_id;

					if($this->session->userdata('items-mode') == 'starred') {
						$where[] = 'fav.fav_id IS NOT NULL';
					}
					if($is_tag) {
						$where[] = 'sub.tag_id = ?';
						$bindings[] = $is_tag;
					}
					if($is_sub) {
						$where[] = 'sub.sub_id = ?';
						$bindings[] = $is_sub;
					}
					if($this->session->userdata('items-mode') == 'notag') {
						$where[] = 'sub.tag_id IS NULL';
					}

					$where[] = 'sub.mbr_id = ?';
					$bindings[] = $this->member->mbr_id;

					if($this->input->post('age') == 'one-day') {
						$where[] = 'DATE_ADD(itm.itm_date, INTERVAL 1 DAY) < ?';
						$bindings[] = date('Y-m-d H:i:s');
					}
					if($this->input->post('age') == 'one-week') {
						$where[] = 'DATE_ADD(itm.itm_date, INTERVAL 1 WEEK) < ?';
						$bindings[] = date('Y-m-d H:i:s');
					}
					if($this->input->post('age') == 'two-weeks') {
						$where[] = 'DATE_ADD(itm.itm_date, INTERVAL 2 WEEK) < ?';
						$bindings[] = date('Y-m-d H:i:s');
					}

					$sql = 'INSERT INTO '.$this->db->dbprefix('history').' (itm_id, mbr_id, hst_datecreated)
					SELECT itm.itm_id AS itm_id, ? AS mbr_id, ? AS hst_datecreated
					FROM '.$this->db->dbprefix('items').' AS itm
					LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = itm.fed_id AND sub.mbr_id = ?
					LEFT JOIN '.$this->db->dbprefix('tags').' AS tag ON tag.tag_id = sub.tag_id
					LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = itm.fed_id
					LEFT JOIN '.$this->db->dbprefix('favorites').' AS fav ON fav.itm_id = itm.itm_id AND fav.mbr_id = ?
					LEFT JOIN '.$this->db->dbprefix('history').' AS hst ON hst.itm_id = itm.itm_id AND hst.mbr_id = ?
					WHERE '.implode(' AND ', $where).'
					GROUP BY itm.itm_id';
					$query = $this->db->query($sql, $bindings);
					$content['alert'] = array('type'=>'success', 'message'=>$this->db->affected_rows().' items marked as read');

					$content['modal'] = $this->load->view('home_history_confirm', $data, TRUE);
				}
			}

			if($mode == 'toggle') {
				$query = $this->db->query('SELECT * FROM '.$this->db->dbprefix('history').' AS hst WHERE hst.itm_id = ? AND hst.mbr_id = ? GROUP BY hst.hst_id', array($id, $this->member->mbr_id));
				if($query->num_rows() > 0) {
					if(!$auto) {
						$this->db->where('itm_id', $id);
						$this->db->where('mbr_id', $this->member->mbr_id);
						$this->db->delete('history');
						$content['status'] = 'unread';
					} else {
						$content['status'] = 'read';
					}
				} else {
					$this->db->set('itm_id', $id);
					$this->db->set('mbr_id', $this->member->mbr_id);
					$this->db->set('hst_datecreated', date('Y-m-d H:i:s'));
					$this->db->insert('history');
					$content['status'] = 'read';
				}
				$content['itm_id'] = $id;
			}
			$content['mode'] = $mode;
		} else {
			$this->output->set_status_header(403);
		}
		$this->reader_library->set_content($content);
	}
	public function shortcuts() {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$data = array();

		$content = array();

		if($this->input->is_ajax_request()) {
			$this->reader_library->set_template('_json');
			$this->reader_library->set_content_type('application/json');

			$content['modal'] = $this->load->view('home_shortcuts', $data, TRUE);
		} else {
			$this->output->set_status_header(403);
		}
		$this->reader_library->set_content($content);
	}
}

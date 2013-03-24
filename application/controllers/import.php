<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Import extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function index() {
		if(!$this->session->userdata('logged_member')) {
			redirect(base_url());
		}

		$this->load->library('form_validation');

		$this->form_validation->set_rules('hidden', 'lang:hidden');

		if($this->form_validation->run() == FALSE) {
			$data = array();
			$content = $this->load->view('import_index', $data, TRUE);
		} else {
			$content = '';
			if(isset($_FILES['file']) == 1 && $_FILES['file']['error'] == 0) {
				$obj = simplexml_load_file($_FILES['file']['tmp_name']);
				if($obj) {
					$this->tags = array();
					$this->feeds = array();
					$this->import_opml($obj->body);

					$content .= '<div class="container-fluid">';

					if(count($this->tags) > 0) {
						$content .= '<h2>Tags ('.count($this->tags).')</h2>';
						$content .= '<table class="table table-condensed table-hover">';
						$content .= '<thead>';
						$content .= '<tr><th>&nbsp;</th><th>Title</th></tr>';
						$content .= '</thead>';
						$content .= '<tbody>';
						$tags = array();
						foreach($this->tags as $value) {
							$query = $this->db->query('SELECT tag.* FROM '.$this->db->dbprefix('tags').' AS tag WHERE tag.tag_title = ? AND tag.mbr_id = ? GROUP BY tag.tag_id', array($value, $this->member->mbr_id));
							if($query->num_rows() == 0) {
								$this->db->set('mbr_id', $this->member->mbr_id);
								$this->db->set('tag_title', $value);
								$this->db->set('tag_datecreated', date('Y-m-d H:i:s'));
								$this->db->insert('tags');
								$tag_id = $this->db->insert_id();
								$tags[$value] = $tag_id;
								$content .= '<tr><td><span class="label label-success">Added</span></td><td>'.$value.'</td></tr>';
							} else {
								$tag = $query->row();
								$tags[$value] = $tag->tag_id;
								$content .= '<tr><td><span class="label label-warning">Found</span></td><td>'.$value.'</td></tr>';
							}
						}
						$content .= '</tbody>';
						$content .= '</table>';
					}

					if(count($this->feeds) > 0) {
						$content .= '<h2>Subscriptions ('.count($this->feeds).')</h2>';
						$content .= '<table class="table table-condensed table-hover">';
						$content .= '<thead>';
						$content .= '<tr><th>&nbsp;</th><th>Title</th><th>URL</th></tr>';
						$content .= '</thead>';
						$content .= '<tbody>';
						foreach($this->feeds as $obj) {
							$query = $this->db->query('SELECT fed.*, IF(sub.sub_id IS NULL, 0, 1) AS subscription FROM '.$this->db->dbprefix('feeds').' AS fed LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = fed.fed_id AND sub.mbr_id = ? WHERE fed.fed_link = ? GROUP BY fed.fed_id', array($this->member->mbr_id, $obj->xmlUrl));
							if($query->num_rows() == 0) {
								$this->db->set('fed_title', $obj->title);
								$this->db->set('fed_url', $obj->htmlUrl);
								$this->db->set('fed_link', $obj->xmlUrl);
								$this->db->set('fed_datecreated', date('Y-m-d H:i:s'));
								$this->db->insert('feeds');
								$fed_id = $this->db->insert_id();

								$this->db->set('mbr_id', $this->member->mbr_id);
								$this->db->set('fed_id', $fed_id);
								$this->db->set('sub_datecreated', date('Y-m-d H:i:s'));
								$this->db->insert('subscriptions');
								$sub_id = $this->db->insert_id();

								if($obj->tag && array_key_exists($obj->tag, $tags)) {
									$this->db->set('sub_id', $sub_id);
									$this->db->set('tag_id', $tags[$obj->tag]);
									$this->db->set('sub_tag_datecreated', date('Y-m-d H:i:s'));
									$this->db->insert('subscriptions_tags');
								}
								$content .= '<tr><td><span class="label label-success">Added</span></td><td>'.$obj->title.'</td><td>'.$obj->xmlUrl.'</td></tr>';
							} else {
								$fed = $query->row();
								if($fed->subscription == 0) {
									$this->db->set('mbr_id', $this->member->mbr_id);
									$this->db->set('fed_id', $fed->fed_id);
									$this->db->set('sub_datecreated', date('Y-m-d H:i:s'));
									$this->db->insert('subscriptions');
									$sub_id = $this->db->insert_id();

									if($obj->tag && array_key_exists($obj->tag, $tags)) {
										$this->db->set('sub_id', $sub_id);
										$this->db->set('tag_id', $tags[$obj->tag]);
										$this->db->set('sub_tag_datecreated', date('Y-m-d H:i:s'));
										$this->db->insert('subscriptions_tags');
									}
									$content .= '<tr><td><span class="label label-success">Added</span></td><td>'.$obj->title.'</td><td>'.$obj->xmlUrl.'</td></tr>';
								} else {
									$content .= '<tr><td><span class="label label-warning">Found</span></td><td>'.$obj->title.'</td><td>'.$obj->xmlUrl.'</td></tr>';
								}
							}
						}
					}
					$content .= '</tbody>';
					$content .= '</table>';
				} else {
					$this->output->set_status_header(500);
				}
			}
		}
		$this->reader_library->set_content($content);
	}
	function import_opml($obj, $tag = false) {
		$feeds = array();
		if(isset($obj->outline) == 1) {
			foreach($obj->outline as $outline) {
				if(isset($outline->outline) == 1) {
					//echo $outline->attributes()->title;
					//print_r($outline);
					$tag = strval($outline->attributes()->title);
					$this->tags[] = $tag;
					$this->import_opml($outline, $tag);
					//array_merge($feeds, $this->import_opml($outline));
				} else {
					//print_r($outline->attributes()->title);
					$feed = new stdClass();
					foreach($outline->attributes() as $k => $attribute) {
						$feed->{$k} = strval($attribute);
					}
					$feed->tag = $tag;
					$this->feeds[] = $feed;
				}
			}
		}
		return $feeds;
	}
}

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
					$this->folders = array();
					$this->feeds = array();
					$this->import_opml($obj->body);

					$content .= '<div id="content">';

					if(count($this->folders) > 0) {
						$content_folders = '<h1><i class="icon icon-folder-close"></i>'.$this->lang->line('folders').' ('.count($this->folders).')</h1>';
						$content_folders .= '<table>';
						$content_folders .= '<thead>';
						$content_folders .= '<tr><th>&nbsp;</th><th>'.$this->lang->line('title').'</th></tr>';
						$content_folders .= '</thead>';
						$content_folders .= '<tbody>';
						$folders = array();
						foreach($this->folders as $value) {
							$query = $this->db->query('SELECT flr.* FROM '.$this->db->dbprefix('folders').' AS flr WHERE flr.flr_title = ? AND flr.mbr_id = ? GROUP BY flr.flr_id', array($value, $this->member->mbr_id));
							if($query->num_rows() == 0) {
								$this->db->set('mbr_id', $this->member->mbr_id);
								$this->db->set('flr_title', $value);
								$this->db->set('flr_datecreated', date('Y-m-d H:i:s'));
								$this->db->insert('folders');
								$flr_id = $this->db->insert_id();
								$folders[$value] = $flr_id;
								$content_folders .= '<tr><td><i class="icon icon-plus"></i></td><td>'.$value.'</td></tr>';
							} else {
								$flr = $query->row();
								$folders[$value] = $flr->flr_id;
								$content_folders .= '<tr><td><i class="icon icon-repeat"></i></td><td>'.$value.'</td></tr>';
							}
						}
						$content_folders .= '</tbody>';
						$content_folders .= '</table>';
						if($this->config->item('folders')) {
							$content .= $content_folders;
						}
					}

					if(count($this->feeds) > 0) {
						$content .= '<h1><i class="icon icon-rss"></i>'.$this->lang->line('subscriptions').' ('.count($this->feeds).')</h1>';
						$content .= '<table>';
						$content .= '<thead>';
						$content .= '<tr><th>&nbsp;</th><th>'.$this->lang->line('title').'</th><th>'.$this->lang->line('url').'</th>';
						if($this->config->item('folders')) {
							$content .= '<th>'.$this->lang->line('folder').'</th></tr>';
						}
						$content .= '</thead>';
						$content .= '<tbody>';
						foreach($this->feeds as $obj) {
							if(!$obj->title && isset($obj->text) == 1) {
								$obj->title = $obj->text;
							}
							if(!$obj->xmlUrl && isset($obj->url) == 1) {
								$obj->xmlUrl = $obj->url;
							}
							if(!$obj->htmlUrl && isset($obj->url) == 1) {
								$obj->htmlUrl = $obj->url;
							}

							$content .= '<tr>';

							$query = $this->db->query('SELECT fed.*, sub.sub_id FROM '.$this->db->dbprefix('feeds').' AS fed LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = fed.fed_id AND sub.mbr_id = ? WHERE fed.fed_link = ? GROUP BY fed.fed_id', array($this->member->mbr_id, $obj->xmlUrl));
							if($query->num_rows() == 0) {
								$this->db->set('fed_title', $obj->title);
								$this->db->set('fed_url', $obj->htmlUrl);
								$this->db->set('fed_link', $obj->xmlUrl);
								$this->db->set('fed_datecreated', date('Y-m-d H:i:s'));
								$this->db->insert('feeds');
								$fed_id = $this->db->insert_id();

								$this->db->set('mbr_id', $this->member->mbr_id);
								$this->db->set('fed_id', $fed_id);
								if($obj->flr && array_key_exists($obj->flr, $folders)) {
									$this->db->set('flr_id', $folders[$obj->flr]);
								}
								$this->db->set('sub_datecreated', date('Y-m-d H:i:s'));
								$this->db->insert('subscriptions');
								$sub_id = $this->db->insert_id();

								$content .= '<td><i class="icon icon-plus"></i></td><td>'.$obj->title.'</td><td>'.$obj->xmlUrl.'</td>';
							} else {
								$fed = $query->row();
								if($fed->sub_id) {
									if($obj->flr && array_key_exists($obj->flr, $folders)) {
										$this->db->set('flr_id', $folders[$obj->flr]);
										$this->db->where('mbr_id', $this->member->mbr_id);
										$this->db->where('sub_id', $fed->sub_id);
										$this->db->update('subscriptions');
									}

									$content .= '<td><i class="icon icon-repeat"></i></td><td>'.$obj->title.'</td><td>'.$obj->xmlUrl.'</td>';
								} else {
									$this->db->set('mbr_id', $this->member->mbr_id);
									$this->db->set('fed_id', $fed->fed_id);
									if($obj->flr && array_key_exists($obj->flr, $folders)) {
										$this->db->set('flr_id', $folders[$obj->flr]);
									}
									$this->db->set('sub_datecreated', date('Y-m-d H:i:s'));
									$this->db->insert('subscriptions');
									$sub_id = $this->db->insert_id();

									$content .= '<td><i class="icon icon-plus"></i></td><td>'.$obj->title.'</td><td>'.$obj->xmlUrl.'</td>';
								}
							}
							if($this->config->item('folders')) {
								if($obj->flr && array_key_exists($obj->flr, $folders)) {
									$content .= '<td>'.$obj->flr.'</td>';
								} else {
									$content .= '<td><em>'.$this->lang->line('no_folder').'</em></td>';
								}
							}
							$content .= '</tr>';
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
	function import_opml($obj, $flr = false) {
		$feeds = array();
		if(isset($obj->outline) == 1) {
			foreach($obj->outline as $outline) {
				if(isset($outline->outline) == 1) {
					//echo $outline->attributes()->title;
					//print_r($outline);
					if($outline->attributes()->title) {
						$flr = strval($outline->attributes()->title);
						$this->folders[] = $flr;
					} else if($outline->attributes()->text) {
						$flr = strval($outline->attributes()->text);
						$this->folders[] = $flr;
					}
					$this->import_opml($outline, $flr);
					//array_merge($feeds, $this->import_opml($outline));
				} else {
					//print_r($outline->attributes()->title);
					$feed = new stdClass();
					foreach($outline->attributes() as $k => $attribute) {
						$feed->{$k} = strval($attribute);
					}
					$feed->flr = $flr;
					$this->feeds[] = $feed;
				}
			}
		}
		return $feeds;
	}
}

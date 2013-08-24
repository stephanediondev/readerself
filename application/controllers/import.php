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

					$content .= '<div id="content">';

					if(count($this->tags) > 0) {
						$content_tags = '<h1><i class="icon icon-folder-close"></i>'.$this->lang->line('tags').' ('.count($this->tags).')</h1>';
						$content_tags .= '<table>';
						$content_tags .= '<thead>';
						$content_tags .= '<tr><th>&nbsp;</th><th>'.$this->lang->line('title').'</th></tr>';
						$content_tags .= '</thead>';
						$content_tags .= '<tbody>';
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
								$content_tags .= '<tr><td><span class="label label-success">'.$this->lang->line('added').'</span></td><td>'.$value.'</td></tr>';
							} else {
								$tag = $query->row();
								$tags[$value] = $tag->tag_id;
								$content_tags .= '<tr><td><span class="label label-warning">'.$this->lang->line('found').'</span></td><td>'.$value.'</td></tr>';
							}
						}
						$content_tags .= '</tbody>';
						$content_tags .= '</table>';
						if($this->config->item('tags')) {
							$content .= $content_tags;
						}
					}

					if(count($this->feeds) > 0) {
						$content .= '<h1><i class="icon icon-rss"></i>'.$this->lang->line('subscriptions').' ('.count($this->feeds).')</h1>';
						$content .= '<table>';
						$content .= '<thead>';
						$content .= '<tr><th>&nbsp;</th><th>'.$this->lang->line('title').'</th><th>'.$this->lang->line('url').'</th>';
						if($this->config->item('tags')) {
							$content .= '<th>'.$this->lang->line('tag').'</th></tr>';
						}
						$content .= '</thead>';
						$content .= '<tbody>';
						foreach($this->feeds as $obj) {
							if(!$obj->title && $obj->text) {
								$obj->title = $obj->text;
							}
							if(!$obj->xmlUrl && $obj->url) {
								$obj->xmlUrl = $obj->url;
							}
							if(!$obj->htmlUrl && $obj->url) {
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
								if($obj->tag && array_key_exists($obj->tag, $tags)) {
									$this->db->set('tag_id', $tags[$obj->tag]);
								}
								$this->db->set('sub_datecreated', date('Y-m-d H:i:s'));
								$this->db->insert('subscriptions');
								$sub_id = $this->db->insert_id();

								$content .= '<td>'.$this->lang->line('added').'</td><td>'.$obj->title.'</td><td>'.$obj->xmlUrl.'</td>';
							} else {
								$fed = $query->row();
								if($fed->sub_id) {
									if($obj->tag && array_key_exists($obj->tag, $tags)) {
										$this->db->set('tag_id', $tags[$obj->tag]);
										$this->db->where('mbr_id', $this->member->mbr_id);
										$this->db->where('sub_id', $fed->sub_id);
										$this->db->update('subscriptions');
									}

									$content .= '<td>'.$this->lang->line('found').'</td><td>'.$obj->title.'</td><td>'.$obj->xmlUrl.'</td>';
								} else {
									$this->db->set('mbr_id', $this->member->mbr_id);
									$this->db->set('fed_id', $fed->fed_id);
									if($obj->tag && array_key_exists($obj->tag, $tags)) {
										$this->db->set('tag_id', $tags[$obj->tag]);
									}
									$this->db->set('sub_datecreated', date('Y-m-d H:i:s'));
									$this->db->insert('subscriptions');
									$sub_id = $this->db->insert_id();

									$content .= '<td>'.$this->lang->line('added').'</td><td>'.$obj->title.'</td><td>'.$obj->xmlUrl.'</td>';
								}
							}
							if($this->config->item('tags')) {
								if($obj->tag && array_key_exists($obj->tag, $tags)) {
									$content .= '<td>'.$obj->tag.'</td>';
								} else {
									$content .= '<td>'.$this->lang->line('no_tag').'</td>';
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
	function import_opml($obj, $tag = false) {
		$feeds = array();
		if(isset($obj->outline) == 1) {
			foreach($obj->outline as $outline) {
				if(isset($outline->outline) == 1) {
					//echo $outline->attributes()->title;
					//print_r($outline);
					if($outline->attributes()->title) {
						$tag = strval($outline->attributes()->title);
						$this->tags[] = $tag;
					} else if($outline->attributes()->text) {
						$tag = strval($outline->attributes()->text);
						$this->tags[] = $tag;
					}
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

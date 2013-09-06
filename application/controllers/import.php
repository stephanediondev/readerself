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

					$content .= '<div id="actions-main">
	<ul class="actions">
		<li><a href="'.base_url().'subscriptions"><i class="icon icon-step-backward"></i>'.$this->lang->line('back').'</a></li>
	</ul>
</div>
<main><section><section>';

					if(count($this->folders) > 0) {
						$content_folders = '<article class="cell title"><h2><i class="icon icon-folder-close"></i>'.$this->lang->line('folders').' ('.count($this->folders).')</h2></article>';
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
								$icon = 'plus';
							} else {
								$flr = $query->row();
								$folders[$value] = $flr->flr_id;
								$icon = 'repeat';
							}
							$content_folders .= '<article class="cell">
								<ul class="actions">
									<li><a href="'.base_url().'folders/update/'.$folders[$value].'"><i class="icon icon-pencil"></i>'.$this->lang->line('update').'</a></li>
									<li><a href="'.base_url().'folders/delete/'.$folders[$value].'"><i class="icon icon-trash"></i>'.$this->lang->line('delete').'</a></li>
								</ul>
								<h2><a href="'.base_url().'folders/read/'.$folders[$value].'"><i class="icon icon-'.$icon.'"></i>'.$value.'</a></h2>
							</article>';
						}
						if($this->config->item('folders')) {
							$content .= $content_folders;
						}
					}

					if(count($this->feeds) > 0) {
						$content .= '<article class="cell title"><h2><i class="icon icon-rss"></i>'.$this->lang->line('subscriptions').' ('.count($this->feeds).')</h2></article>';
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

								$icon = 'plus';
							} else {
								$fed = $query->row();
								if($fed->sub_id) {
									if($obj->flr && array_key_exists($obj->flr, $folders)) {
										$this->db->set('flr_id', $folders[$obj->flr]);
										$this->db->where('mbr_id', $this->member->mbr_id);
										$this->db->where('sub_id', $fed->sub_id);
										$this->db->update('subscriptions');
									}
									$sub_id = $fed->sub_id;

									$icon = 'repeat';
								} else {
									$this->db->set('mbr_id', $this->member->mbr_id);
									$this->db->set('fed_id', $fed->fed_id);
									if($obj->flr && array_key_exists($obj->flr, $folders)) {
										$this->db->set('flr_id', $folders[$obj->flr]);
									}
									$this->db->set('sub_datecreated', date('Y-m-d H:i:s'));
									$this->db->insert('subscriptions');
									$sub_id = $this->db->insert_id();

									$icon = 'plus';
								}
							}
							$content .= '<article class="cell">
								<ul class="actions">
									<li><a href="'.base_url().'subscriptions/update/'.$sub_id.'"><i class="icon icon-pencil"></i>'.$this->lang->line('update').'</a></li>
									<li><a href="'.base_url().'subscriptions/delete/'.$sub_id.'"><i class="icon icon-trash"></i>'.$this->lang->line('delete').'</a></li>
								</ul>
								<h2><a href="'.base_url().'subscriptions/read/'.$sub_id.'"><i class="icon icon-'.$icon.'"></i>'.$obj->title.'</a></h2>
								<ul class="item-details">';
								if($this->config->item('folders')) {
									if($obj->flr && array_key_exists($obj->flr, $folders)) {
										$content .= '<li><a href="'.base_url().'folders/read/'.$folders[$obj->flr].'"><i class="icon icon-folder-close"></i>'.$obj->flr.'</a></li>';
									} else {
										$content .= '<li><i class="icon icon-folder-close"></i><em>'.$this->lang->line('no_folder').'</em></li>';
									}
								}
								$content .= '</ul>
							</article>';
						}
					}
					$content .= '</section></section></main>';
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

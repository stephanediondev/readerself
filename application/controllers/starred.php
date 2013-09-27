<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Starred extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function import() {
		if(!$this->session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$this->load->library('form_validation');

		$this->form_validation->set_rules('hidden', 'lang:hidden');

		if($this->form_validation->run() == FALSE) {
			$data = array();
			$content = $this->load->view('starred_import', $data, TRUE);
		} else {
			$starred_items = 0;
			$content = '';
			$content .= '</header>
<main><section><section>';
			if(isset($_FILES['file']) == 1 && $_FILES['file']['error'] == 0) {
				$json = json_decode(file_get_contents($_FILES['file']['tmp_name']));
				foreach($json->items as $item) {
					if(isset($item->alternate) == 1 && isset($item->origin) == 1) {
						if(isset($item->origin->streamId) == 1) {
							$fed_link = substr($item->origin->streamId, strpos($item->origin->streamId, 'http'));

							$query = $this->db->query('SELECT fed.*, sub.sub_id, IF(sub.sub_id IS NULL, 0, 1) AS subscription FROM '.$this->db->dbprefix('feeds').' AS fed LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = fed.fed_id AND sub.mbr_id = ? WHERE fed.fed_link = ? GROUP BY fed.fed_id', array($this->member->mbr_id, $fed_link));
							if($query->num_rows() == 0) {
								$this->db->set('fed_title', $item->origin->title);
								$this->db->set('fed_url', $item->origin->htmlUrl);
								$this->db->set('fed_link', $fed_link);
								$this->db->set('fed_datecreated', date('Y-m-d H:i:s'));
								$this->db->insert('feeds');
								$fed_id = $this->db->insert_id();

								$this->db->set('mbr_id', $this->member->mbr_id);
								$this->db->set('fed_id', $fed_id);
								$this->db->set('sub_datecreated', date('Y-m-d H:i:s'));
								$this->db->insert('subscriptions');
								$sub_id = $this->db->insert_id();

							} else {
								$fed = $query->row();
								$fed_id = $fed->fed_id;
								if($fed->subscription == 0) {
									$this->db->set('mbr_id', $this->member->mbr_id);
									$this->db->set('fed_id', $fed_id);
									$this->db->set('sub_datecreated', date('Y-m-d H:i:s'));
									$this->db->insert('subscriptions');
									$sub_id = $this->db->insert_id();
								} else {
									$sub_id = $fed->sub_id;
								}
							}

							if(isset($item->alternate[0]->href) == 1) {
								$itm_link = $item->alternate[0]->href;
								$query = $this->db->query('SELECT * FROM '.$this->db->dbprefix('items').' AS itm WHERE itm.itm_link = ? GROUP BY itm.itm_id', array($itm_link));
								if($query->num_rows() == 0) {
									$this->db->set('fed_id', $fed_id);
									if(isset($item->title) == 1) {
										$this->db->set('itm_title', $item->title);
									} else {
										$this->db->set('itm_title', '-');
									}
									if(isset($item->author) == 1) {
										$this->db->set('itm_author', $item->author);
									}
									$this->db->set('itm_link', $itm_link);
									if(isset($item->content->content) == 1) {
										$this->db->set('itm_content', $item->content->content);
									} else if(isset($item->summary->content) == 1) {
										$this->db->set('itm_content', $item->summary->content);
									} else {
										$this->db->set('itm_content', '-');
									}
									$this->db->set('itm_date', date('Y-m-d H:i:s', $item->published));
									$this->db->set('itm_datecreated', date('Y-m-d H:i:s'));
									$this->db->insert('items');
									$itm_id = $this->db->insert_id();

									foreach($item->categories as $category) {
										if(!stristr($category, 'state/com.google')) {
											if(strstr($category, ',')) {
												$categories = explode(',', $category);
												foreach($categories as $category_split) {
													$category_split = trim( strip_tags( html_entity_decode( $category_split ) ) );
													$this->db->set('itm_id', $itm_id);
													$this->db->set('cat_title', $category_split);
													$this->db->set('cat_datecreated', date('Y-m-d H:i:s'));
													$this->db->insert('categories');
												}
											} else {
												$this->db->set('itm_id', $itm_id);
												$this->db->set('cat_title', trim( strip_tags( html_entity_decode( $category ) ) ) );
												$this->db->set('cat_datecreated', date('Y-m-d H:i:s'));
												$this->db->insert('categories');
											}
										}
									}
								} else {
									$itm_id = $query->row()->itm_id;
								}

								$starred_items++;

								$query = $this->db->query('SELECT * FROM '.$this->db->dbprefix('favorites').' AS fav WHERE fav.itm_id = ? AND fav.mbr_id = ? GROUP BY fav.fav_id', array($itm_id, $this->member->mbr_id));
								if($query->num_rows() == 0) {
									$this->db->set('itm_id', $itm_id);
									$this->db->set('mbr_id', $this->member->mbr_id);
									$this->db->set('fav_datecreated', date('Y-m-d H:i:s'));
									$this->db->insert('favorites');
								}
							}
						}
					}
				}
			}
			$content .= '<article class="title"><h2><i class="icon icon-star"></i>'.$this->lang->line('starred_items').' ('.$starred_items.')</h2></article>';
			$content .= '</section></section></main>';
		}
		$this->readerself_library->set_content($content);
	}
}

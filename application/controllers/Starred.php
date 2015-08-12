<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Starred extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function import() {
		if(!$this->axipi_session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$data = array();

		$this->load->library('form_validation');

		$this->form_validation->set_rules('hidden', 'lang:hidden');

		if($this->form_validation->run() == FALSE) {
			$content = $this->load->view('starred_import', $data, TRUE);
		} else {
			$data['starred_items'] = 0;
			if(isset($_FILES['file']) == 1 && $_FILES['file']['error'] == 0) {
				$json = json_decode(file_get_contents($_FILES['file']['tmp_name']));
				foreach($json->items as $item) {
					if(isset($item->alternate) == 1 && isset($item->origin) == 1) {
						if(isset($item->origin->streamId) == 1) {
							$fed_link = substr($item->origin->streamId, strpos($item->origin->streamId, 'http'));

							$fed = $this->db->query('SELECT fed.*, sub.sub_id FROM '.$this->db->dbprefix('feeds').' AS fed LEFT JOIN '.$this->db->dbprefix('subscriptions').' AS sub ON sub.fed_id = fed.fed_id AND sub.mbr_id = ? WHERE fed.fed_link = ? GROUP BY fed.fed_id', array($this->member->mbr_id, $fed_link))->row();
							if(!$fed) {
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
								$fed_id = $fed->fed_id;
								if(!$fed->sub_id) {
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
								$itm = $this->db->query('SELECT * FROM '.$this->db->dbprefix('items').' AS itm WHERE itm.itm_link = ? GROUP BY itm.itm_id', array($itm_link))->row();
								if(!$itm) {
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

									$categories_to_insert = array();
									foreach($item->categories as $category) {
										if(!stristr($category, 'state/com.google')) {
											if(strstr($category, ',')) {
												$categories = explode(',', $category);
												foreach($categories as $category_split) {
													$category_split = trim( strip_tags( html_entity_decode( $category_split ) ) );
													if($category_split != '') {
														$categories_to_insert[] = $category_split;
													}
												}
											} else {
												if($category_split != '') {
													$categories_to_insert[] = $category_split;
												}
											}
										}
									}
									foreach($categories_to_insert as $category) {
										$tag_id = $this->CI->readerself_library->convert_category_title($category);
										$this->CI->db->set('tag_id', $tag_id);
										$this->CI->db->set('itm_id', $itm_id);
										$this->CI->db->set('tag_itm_datecreated', date('Y-m-d H:i:s'));
										$this->CI->db->insert('tags_items');
									}
								} else {
									$itm_id = $itm->itm_id;
								}

								$data['starred_items']++;

								$fav = $this->db->query('SELECT * FROM '.$this->db->dbprefix('favorites').' AS fav WHERE fav.itm_id = ? AND fav.mbr_id = ? GROUP BY fav.fav_id', array($itm_id, $this->member->mbr_id))->row();
								if(!$fav) {
									$this->db->set('itm_id', $itm_id);
									$this->db->set('mbr_id', $this->member->mbr_id);
									$this->db->set('fav_datecreated', date('Y-m-d H:i:s'));
									$this->db->insert('favorites');
								}

								$hst = $this->db->query('SELECT * FROM '.$this->db->dbprefix('history').' AS hst WHERE hst.itm_id = ? AND hst.mbr_id = ? GROUP BY hst.hst_id', array($itm_id, $this->member->mbr_id))->row();
								if(!$hst) {
									$this->db->set('itm_id', $itm_id);
									$this->db->set('mbr_id', $this->member->mbr_id);
									$this->db->set('hst_datecreated', date('Y-m-d H:i:s'));
									$this->db->insert('history');
								}
							}
						}
					}
				}
			}
			$content = $this->load->view('starred_import_done', $data, TRUE);
		}
		$this->readerself_library->set_content($content);
	}
	public function export() {
		if(!$this->axipi_session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$this->readerself_library->set_template('_json');
		$this->readerself_library->set_content_type('application/json');

		$content = array();

		$where = array();
		$bindings = array();

		$where[] = 'itm.itm_id IN ( SELECT fav.itm_id FROM '.$this->db->dbprefix('favorites').' AS fav WHERE fav.itm_id = itm.itm_id AND fav.mbr_id = ? )';
		$bindings[] = $this->member->mbr_id;

		$content['id'] = 'user/'.$this->member->mbr_id.'/state/com.google/starred';
		$content['title'] = $this->lang->line('shared_items');
		if($this->member->mbr_nickname) {
			$content['author'] = $this->member->mbr_nickname;
		}
		$content['items'] = array();

		$sql = 'SELECT itm.* FROM '.$this->db->dbprefix('items').' AS itm WHERE '.implode(' AND ', $where).' GROUP BY itm.itm_id';
		$query = $this->db->query($sql, $bindings);
		if($query->num_rows() > 0) {
			foreach($query->result() as $itm) {
				$item = array();
				$item['id'] ='item/'.$itm->itm_id;
				$item['crawlTimeMsec'] = date('U', strtotime($itm->itm_datecreated));
				//"timestampUsec" : "1364414065401654",

				$item['categories'] = array();
				$sql = 'SELECT hst.* FROM '.$this->db->dbprefix('history').' AS hst WHERE hst.itm_id = ? AND hst.mbr_id = ? GROUP BY hst.hst_id';
				$query = $this->db->query($sql, array($itm->itm_id, $this->member->mbr_id));
				if($query->num_rows() > 0) {
					$item['categories'][] = 'user/'.$this->member->mbr_id.'/state/com.google/read';
				} else {
					$item['categories'][] = 'user/'.$this->member->mbr_id.'/state/com.google/unread';
				}
				$item['categories'][] = 'user/'.$this->member->mbr_id.'/state/com.google/starred';

				$categories = $this->db->query('SELECT tag.* FROM '.$this->db->dbprefix('tags').' AS tag LEFT JOIN '.$this->db->dbprefix('tags_items').' AS tag_itm ON tag_itm.tag_id = tag.tag_id WHERE tag_itm.itm_id = ? GROUP BY tag.tag_id', array($itm->itm_id))->result();
				if($categories) {
					$itm->categories = array();
					foreach($categories as $cat) {
						$item['categories'][] = $cat->tag_title;
					}
				}

				$item['title'] = $itm->itm_title;
				$item['published'] = date('U', strtotime($itm->itm_date));
				$item['updated'] = date('U', strtotime($itm->itm_date));
				$item['alternate'] = array();
				$item['alternate'][] = array('href' => $itm->itm_link, 'type' => 'text/html');
				$item['summary'] = array('direction' => 'ltr', 'content' => $itm->itm_content);
				if($itm->itm_author) {
					$item['author'] = $itm->itm_author;
				}
				$item['comments'] = array();
				$item['annotations'] = array();

				$sql = 'SELECT fed.* FROM '.$this->db->dbprefix('feeds').' AS fed WHERE fed.fed_id = ? GROUP BY fed.fed_id';
				$feed = $this->db->query($sql, array($itm->fed_id))->row();

				$item['origin'] = array('streamId' => 'feed/'.$feed->fed_link, 'title' => $feed->fed_title, 'htmlUrl' => $feed->fed_url);

				$content['items'][] = $item;
			}
		}

		$this->readerself_library->set_content($content);

		header('Pragma: Public');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Content-Type: application/json');
		header('Content-Disposition: attachment; filename="starred-'.date('Y-m-d').'.json";');
		header('Content-Transfer-Encoding: binary'); 
		session_write_close();
	}
}

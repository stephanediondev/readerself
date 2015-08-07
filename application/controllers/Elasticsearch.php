<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Elasticsearch extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function index() {
		if(!$this->config->item('elasticsearch/enabled')) {
			redirect(base_url());
		}

		$this->load->library('elasticsearch_library');

		$query = $this->db->query('SELECT itm.* FROM '.$this->db->dbprefix('items').' AS itm LEFT JOIN '.$this->db->dbprefix('elasticsearch_items').' AS elastic ON elastic.itm_id = itm.itm_id WHERE elastic.id IS NULL GROUP BY itm.itm_id');

		if($query->num_rows() > 0) {
			while($item = $query->_fetch_object()) {

				$index = $this->config->item('elasticsearch/index');
				$type = 'item';
				$id = $item->itm_id;

				$body = array(
					'id' => $item->itm_id,
					'feed' => $item->fed_id,
					'title' => strip_tags($item->itm_title),
					'link' => $item->itm_link,
					'author' => $item->itm_author,
					'content' => strip_tags($item->itm_content),
					'date' => $item->itm_date,
				);
				$path = '/'.$index.'/'.$type.'/'.$id;
				$this->elasticsearch_library->put($path, $body);

				$this->db->set('itm_id', $item->itm_id);
				$this->db->set('datecreated', date('Y-m-d H:i:s'));
				$this->db->insert('elasticsearch_items');
			}
		}

		redirect(base_url().'elasticsearch/form');
	}
	public function form() {
		if(!$this->config->item('elasticsearch/enabled')) {
			redirect(base_url());
		}

		$this->load->library('elasticsearch_library');

		$data = array();

		$data['to_index'] = $this->db->query('SELECT COUNT(DISTINCT(itm.itm_id)) AS count FROM '.$this->db->dbprefix('items').' AS itm LEFT JOIN '.$this->db->dbprefix('elasticsearch_items').' AS elastic ON elastic.itm_id = itm.itm_id WHERE elastic.id IS NULL')->row()->count;

		if($this->input->get('q')) {
			$index = $this->config->item('elasticsearch/index');
			$size = 20;
			$from = $this->input->get('from', 0); 
			$sort = '_score:desc';
			$body = array();
			$body['query'] = array(
				'query_string' => array(
					'fields' => array('title', 'content'),
					'query' => $this->input->get('q'),
				),
			);
			$body['highlight'] = array(
				//'encoder' => 'html',
				'pre_tags' => array('<strong>'),
				'post_tags' => array('</strong>'),
				'fields' => array(
					'title' => array(
						'fragment_size' => 255,
						'number_of_fragments' => 1,
					),
					'content' => array(
						'fragment_size' => 500,
						'number_of_fragments' => 1,
					),
				),
			);

			/*if($this->input->get('date_from') && $this->input->get('date_to')) {
				$body['filter'] = array(
					'range' => array(
						'date' => array(
							'gte' => $this->input->get('date_from'),
							'lte' => $this->input->get('date_to'),
							'format' => 'YYYY-MM-DD',
						),
					),
				);
			}*/

			$data['hits'] = $this->elasticsearch_library->get('/'.$index.'/_search?size='.intval($size).'&type=item&from='.intval($from).'&sort='.$sort.'&track_scores', $body)->hits;

			$data['pagination'] = array();
			if($data['hits']->total > $size) {
				$total = $data['hits']->total - 1;
				$page = 1;
				for($i=0;$i<=$total;$i = $i + $size) {
					$data['pagination'][$page] = $i;
					$page++;
				}
				$data['current_from'] = intval($from);
			}
		}

		$content = $this->load->view('elasticsearch_form', $data, TRUE);
		$this->readerself_library->set_content($content);
	}
}

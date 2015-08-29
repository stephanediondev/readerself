<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Elasticsearch extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function index() {
		if(!$this->config->item('elasticsearch/enabled')) {
			redirect(base_url());
		}

		//https://www.elastic.co/guide/en/elasticsearch/guide/current/sorting-collations.html
		//$this->db->query('DELETE FROM elasticsearch_items');

		$this->load->library('elasticsearch_library');

		$index = $this->config->item('elasticsearch/index');
		$type = 'item';

		$path = '/'.$index;
		$head_http_status = $this->elasticsearch_library->head($path);
		if($head_http_status == 404) {
			$path = '/'.$index;
			$status_index = $this->elasticsearch_library->put($path);
		}

		$path = '/'.$index.'/_close';
		$this->elasticsearch_library->post($path);

		$body = array(
			'settings' => array(
				'index' => array(
					'analysis' => array(
						'analyzer' => array(
							'case_insensitive_sort' => array(
								'filter' => array(
									'lowercase',
									'asciifolding',
								),
								'tokenizer' => 'keyword',
							),
						),
					),
				),
			),
		);
		$path = '/'.$index.'/_settings';
		$this->elasticsearch_library->put($path, $body);

		$path = '/'.$index.'/_open';
		$this->elasticsearch_library->post($path);

		$body = array(
			$type => array(
				'properties' => array( 
					'title' => array( 
						'type' => 'string',
						'fields' => array(
							'sort' => array( 
								'type' => 'string',
								'analyzer' => 'case_insensitive_sort',
							),
						),
					),
					'date' => array( 
						'type' => 'string',
						'fields' => array(
							'sort' => array( 
								'type' => 'string',
								'analyzer' => 'case_insensitive_sort',
							),
						),
					),
				),
			),
		);
		$path = '/'.$index.'/_mapping/'.$type;
		$this->elasticsearch_library->put($path, $body);

		$query = $this->db->query('SELECT itm.*, fed.* FROM '.$this->db->dbprefix('items').' AS itm LEFT JOIN '.$this->db->dbprefix('feeds').' AS fed ON fed.fed_id = itm.fed_id LEFT JOIN '.$this->db->dbprefix('elasticsearch_items').' AS elastic ON elastic.itm_id = itm.itm_id WHERE elastic.id IS NULL GROUP BY itm.itm_id');

		if($query->num_rows() > 0) {
			while($item = $query->_fetch_object()) {

				$id = $item->itm_id;

				$body = array(
					'id' => $item->itm_id,
					'feed' => array(
						'id' => $item->fed_id,
						'title' => $item->fed_title,
						'url' => $item->fed_url,
						'link' => $item->fed_link,
						'host' => $item->fed_host,
					),
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
		$data['sort_field'] = array('date.sort' => 'Date', '_score' => 'Score', 'title.sort' => 'Title');
		$data['sort_direction'] = array('asc' => 'Asc.', 'desc' => 'Desc.',);

		if($this->input->get('q')) {
			if(!array_key_exists($this->input->get('sort_field'), $data['sort_field'])) {
				$sort_field = '_score';
			} else {
				$sort_field = $this->input->get('sort_field');
			}
			if(!array_key_exists($this->input->get('sort_direction'), $data['sort_direction'])) {
				$sort_direction = 'desc';
			} else {
				$sort_direction = $this->input->get('sort_direction');
			}

			$index = $this->config->item('elasticsearch/index');
			$size = 20;
			$from = $this->input->get('from', 0); 
			$body = array();
			$body['sort'] = array(
				$sort_field => array(
					'order' => $sort_direction,
					),
			);
			$body['query'] = array(
				'query_string' => array(
					'fields' => array('feed.title', 'title', 'content'),
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
						'date.sort' => array(
							'gte' => $this->input->get('date_from'),
							'lte' => $this->input->get('date_to'),
							'format' => 'YYYY-MM-DD',
						),
					),
				);
			}*/

			$data['hits'] = $this->elasticsearch_library->get('/'.$index.'/_search?size='.intval($size).'&type=item&from='.intval($from), $body)->hits;

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

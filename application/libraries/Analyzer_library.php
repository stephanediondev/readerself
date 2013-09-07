<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Analyzer_library {
	public function __construct() {
		$this->headers = array();
		$this->metas = array();
	}
	function start($url) {
		$this->headers = get_headers($url, 1);

		if(isset($this->headers['Location']) == 1) {
			$url = $this->headers['Location'];
			$origin_status = $this->headers[0];
			$this->headers = get_headers($url, 1);
			$this->headers[0] = $this->headers[0].' ('.$origin_status.')';
		}

		$opts = array('http'=>array('header'=>'User-Agent: '.$_SERVER['HTTP_USER_AGENT']."\r\n"));

		$context = stream_context_create($opts);

		$this->content = file_get_contents($url, false, $context);
		$this->content = str_replace("\t", '', $this->content);
		$this->content2 = str_replace("\r\n", '', $this->content);
		$this->content2 = str_replace("\n", '', $this->content2);
		$this->content2 = str_replace("\r", '', $this->content2);

		$this->title();
		$this->metas();
		$this->charset();
	}
	function charset() {
		$this->charset = '';
		$this->charset_server = '';
		$this->charset_client = '';
		if(isset($this->headers['Content-Type']) == 1 && stristr($this->headers['Content-Type'], 'charset')) {
			$this->contentType = $this->headers['Content-Type'];
			$this->charset = strtolower(substr($this->headers['Content-Type'], strpos($this->headers['Content-Type'], '=')+1));
			$this->charset_server = $this->charset;
		}
		if(isset($this->meta_charset) == 1 && stristr($this->meta_charset, 'charset')) {
			$this->charset = strtolower(substr($this->meta_charset, strpos($this->meta_charset, '=')+1));
			$this->charset_client = $this->charset;
		}
	}
	function encoding($data) {
		if($this->charset != 'utf-8' && $this->charset != '') {
			$data = utf8_encode($data);
		}
		return $data;
	}
	function title() {
		$this->title = '';
		$pattern = "|<title(.*)>(.*)<\/title>|U";
		$matches = array();
		preg_match_all($pattern, $this->content2, $matches, PREG_SET_ORDER);
		foreach($matches as $match) {
			$this->title = trim($match[2]);
		}
	}
	function metas() {
		$pattern = "|<[lL][iI][nN][kK](.*)[hH][rR][eE][fF]=\"(.*)\"(.*)>|U";
		$matches = array();
		preg_match_all($pattern, $this->content2, $matches, PREG_SET_ORDER);
		foreach($matches as $match) {
			$type = '';
			$title = '';

			$pattern = "|(.*)[tT][yY][pP][eE]=[\"'](.*)[\"'](.*)|U";
			$matches_sub = array();
			preg_match_all($pattern, $match[1], $matches_sub, PREG_SET_ORDER);
			foreach($matches_sub as $match_sub) {
				$type = $match_sub[2];
			}
			$pattern = "|(.*)[tT][yY][pP][eE]=[\"'](.*)[\"'](.*)|U";
			$matches_sub = array();
			preg_match_all($pattern, $match[3], $matches_sub, PREG_SET_ORDER);
			foreach($matches_sub as $match_sub) {
				$type = $match_sub[2];
			}

			$pattern = "|(.*)[tT][iI][tT][lL][eE]=[\"'](.*)[\"'](.*)|U";
			$matches_sub = array();
			preg_match_all($pattern, $match[1], $matches_sub, PREG_SET_ORDER);
			foreach($matches_sub as $match_sub) {
				$title = $match_sub[2];
			}
			$pattern = "|(.*)[tT][iI][tT][lL][eE]=[\"'](.*)[\"'](.*)|U";
			$matches_sub = array();
			preg_match_all($pattern, $match[3], $matches_sub, PREG_SET_ORDER);
			foreach($matches_sub as $match_sub) {
				$title = $match_sub[2];
			}
			if($type == 'application/rss+xml' || $type == 'application/atom+xml') {
				$this->metas[] = array('href'=>$match[2], 'type'=>$type, 'title'=>$title);
			}
		}

		$pattern = "|<[mM][eE][tT][aA](.*)[cC][oO][nN][tT][eE][nN][tT]=\"(.*)\"(.*)>|U";
		$matches = array();
		preg_match_all($pattern, $this->content2, $matches, PREG_SET_ORDER);
		foreach($matches as $match) {
			$name = '';
			$httpequiv = '';

			$pattern = "|(.*)[nN][aA][mM][eE]=[\"'](.*)[\"'](.*)|U";
			$matches_sub = array();
			preg_match_all($pattern, $match[1], $matches_sub, PREG_SET_ORDER);
			foreach($matches_sub as $match_sub) {
				$name = $match_sub[2];
			}
			$pattern = "|(.*)[nN][aA][mM][eE]=\"(.*)\"(.*)|U";
			$matches_sub = array();
			preg_match_all($pattern, $match[3], $matches_sub, PREG_SET_ORDER);
			foreach($matches_sub as $match_sub) {
				$name = $match_sub[2];
			}

			$pattern = "|(.*)[hH][tT][tT][pP]-[eE][qQ][uU][iI][vV]=[\"'](.*)[\"'](.*)|U";
			$matches_sub = array();
			preg_match_all($pattern, $match[1], $matches_sub, PREG_SET_ORDER);
			foreach($matches_sub as $match_sub) {
				$httpequiv = $match_sub[2];
			}
			$pattern = "|(.*)[hH][tT][tT][pP]-[eE][qQ][uU][iI][vV]=\"(.*)\"(.*)|U";
			$matches_sub = array();
			preg_match_all($pattern, $match[3], $matches_sub, PREG_SET_ORDER);
			foreach($matches_sub as $match_sub) {
				$httpequiv = $match_sub[2];
			}

			$pattern = "|(.*)[pP][rR][oO][pP][eE][rR][tT][yY]=[\"'](.*)[\"'](.*)|U";
			$matches_sub = array();
			preg_match_all($pattern, $match[1], $matches_sub, PREG_SET_ORDER);
			foreach($matches_sub as $match_sub) {
				$httpequiv = $match_sub[2];
			}
			$pattern = "|(.*)[pP][rR][oO][pP][eE][rR][tT][yY]=\"(.*)\"(.*)|U";
			$matches_sub = array();
			preg_match_all($pattern, $match[3], $matches_sub, PREG_SET_ORDER);
			foreach($matches_sub as $match_sub) {
				$httpequiv = $match_sub[2];
			}

			if(strtolower($httpequiv) == 'content-type') {
				$this->meta_charset = $match[2];
			}
		
			//$this->metas[] = array('href'=>$match[2], 'type'=>$name, 'title'=>$httpequiv);
		}
	}
}

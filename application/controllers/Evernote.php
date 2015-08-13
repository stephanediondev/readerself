<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'thirdparty/evernote/Evernote/Client.php';
require_once 'thirdparty/evernote/packages/Types/Types_types.php';
require_once 'thirdparty/evernote/packages/Errors/Errors_types.php';
require_once 'thirdparty/evernote/packages/Limits/Limits_constants.php';

use EDAM\Error\EDAMSystemException, EDAM\Error\EDAMUserException, EDAM\Error\EDAMErrorCode, EDAM\Error\EDAMNotFoundException, EDAM\Types\Data, EDAM\Types\Note, EDAM\Types\NoteAttributes, EDAM\Types\Resource, EDAM\Types\ResourceAttributes;
use Evernote\Client;

class Evernote extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function index() {
		if(!$this->axipi_session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$data = array();

		$content = $this->load->view('evernote_index', $data, TRUE);
		$this->readerself_library->set_content($content);
	}
	public function authorize() {
		if(!$this->axipi_session->userdata('mbr_id')) {
			redirect(base_url());
		}

		if($this->getTemporaryCredentials()) {
			redirect($this->getAuthorizationUrl());
		}
	}
	public function callback() {
		if($this->handleCallback()) {
			$oauth_token = $this->getTokenCredentials();
			if($oauth_token) {
				$this->readerself_model->set_token('evernote', $this->member->mbr_id, $oauth_token);
			}
		}

		if($this->axipi_session->userdata('mbr_id')) {
			redirect(base_url().'evernote');
		}
	}
	/*function list_notebooks() {
		try {
			$client = new Client(array(
				'token' => $this->readerself_model->get_token('evernote', $this->member->mbr_id),
				'sandbox' => $this->config->item('evernote/sandbox')
			));

			$notebooks = $client->getNoteStore()->listNotebooks();
			$result = array();
			if (!empty($notebooks)) {
				foreach ($notebooks as $notebook) {
					$result[] = $notebook->name;
				}
			}
			print_r($result);
			return TRUE;
		} catch (EDAMSystemException $e) {
			if (isset(EDAMErrorCode::$__names[$e->errorCode])) {
				$lastError = EDAMErrorCode::$__names[$e->errorCode].': '.$e->parameter;
			} else {
				$lastError = $e->getCode().': '.$e->getMessage();
			}
		} catch (EDAMUserException $e) {
			if (isset(EDAMErrorCode::$__names[$e->errorCode])) {
				$lastError = EDAMErrorCode::$__names[$e->errorCode].': '.$e->parameter;
			} else {
				$lastError = $e->getCode().': '.$e->getMessage();
			}
		} catch (EDAMNotFoundException $e) {
			if (isset(EDAMErrorCode::$__names[$e->errorCode])) {
				$lastError = EDAMErrorCode::$__names[$e->errorCode].': '.$e->parameter;
			} else {
				$lastError = $e->getCode().': '.$e->getMessage();
			}
		} catch (Exception $e) {
			$lastError = $e->getMessage();
		}
	}*/
	function create($itm_id) {
		if(!$this->axipi_session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$this->readerself_library->set_template('_json');
		$this->readerself_library->set_content_type('application/json');

		$data = array();
		$content = array();
		$content['itm_id'] = $itm_id;

		$data['itm'] = $this->db->query('SELECT * FROM '.$this->db->dbprefix('items').' AS itm WHERE itm.itm_id = ? AND itm.fed_id IN ( SELECT sub.fed_id FROM '.$this->db->dbprefix('subscriptions').' AS sub WHERE sub.fed_id = itm.fed_id AND sub.mbr_id = ? ) GROUP BY itm.itm_id', array($itm_id, $this->member->mbr_id))->row();
		if($data['itm'] && $this->input->is_ajax_request()) {
			$token = $this->readerself_model->get_token('evernote', $this->member->mbr_id);
			if($token) {
				try {
					$client = new Client(array(
						'token' => $this->readerself_model->get_token('evernote', $this->member->mbr_id),
						'sandbox' => $this->config->item('evernote/sandbox')
					));
					$noteStore = $client->getNoteStore();

					$noteAttributes = new NoteAttributes();
					$noteAttributes->source = 'api';
					$noteAttributes->sourceURL = $data['itm']->itm_link;
					$noteAttributes->sourceApplication = $this->config->item('title');
					if($data['itm']->itm_latitude) {
						$noteAttributes->latitude = $data['itm']->itm_latitude;
					}
					if($data['itm']->itm_longitude) {
						$noteAttributes->longitude = $data['itm']->itm_longitude;
					}

					$note = new Note();
					$note->title = $data['itm']->itm_title;
					$note->attributes = $noteAttributes;

					/*if($this->config->item('readability_parser_key')) {
						$url = 'https://www.readability.com/api/content/v1/parser?url='.urlencode($data['itm']->itm_link).'&token='.$this->config->item('readability_parser_key');
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_URL, $url);
						$result = curl_exec($ch);
						curl_close($ch);
						$result = json_decode($result);
						$data['itm']->itm_content = $result->content;
					}*/

					$options = array('output-xhtml' => true, 'clean' => true, 'wrap-php' => true, 'doctype' => 'omit', 'show-body-only' => true, 'drop-proprietary-attributes' => true);
					$tidy = new tidy();
					$tidy->parseString($data['itm']->itm_content, $options, 'utf8');
					$tidy->cleanRepair();
					//strip_tags($tidy, '<img><p><br><br/><em><strong><h1><h2><h3><h4><h5><h6>')

					$note->content = '<?xml version="1.0" encoding="UTF-8"?><!DOCTYPE en-note SYSTEM "http://xml.evernote.com/pub/enml2.dtd"><en-note>'.$tidy.'</en-note>';

					// When note titles are user-generated, it's important to validate them
					$len = strlen($note->title);
					$min = $GLOBALS['EDAM_Limits_Limits_CONSTANTS']['EDAM_NOTE_TITLE_LEN_MIN'];
					$max = $GLOBALS['EDAM_Limits_Limits_CONSTANTS']['EDAM_NOTE_TITLE_LEN_MAX'];
					$pattern = '#' . $GLOBALS['EDAM_Limits_Limits_CONSTANTS']['EDAM_NOTE_TITLE_REGEX'] . '#'; // Add PCRE delimiters
					if ($len < $min || $len > $max || !preg_match($pattern, $note->title)) {
						//print "\nInvalid note title: " . $note->title . '\n\n';
						//exit(1);
					}
					try {
						$createdNote = $noteStore->createNote($note);
						$data['status'] = 'note_added';
					} catch (EDAMUserException $e) {
						$data['status'] = 'error';
						$data['message'] = $e->parameter;
						$content['modal'] = $this->load->view('evernote_create', $data, TRUE);
						$this->readerself_library->set_content($content);
					} catch (EDAMNotFoundException $e) {
						$data['status'] = 'error';
						$data['message'] = $e->getMessage();
						$content['modal'] = $this->load->view('evernote_create', $data, TRUE);
						$this->readerself_library->set_content($content);
					}
				} catch (EDAMSystemException $e) {
					$data['status'] = 'error';
					if(isset(EDAMErrorCode::$__names[$e->errorCode])) {
						$data['message'] = EDAMErrorCode::$__names[$e->errorCode].': '.$e->parameter;
					} else {
						$data['message'] = $e->getCode().': '.$e->getMessage();
					}
					$content['modal'] = $this->load->view('evernote_create', $data, TRUE);
					$this->readerself_library->set_content($content);
				} catch (EDAMUserException $e) {
					$data['status'] = 'error';
					if(isset(EDAMErrorCode::$__names[$e->errorCode])) {
						$data['message'] = EDAMErrorCode::$__names[$e->errorCode].': '.$e->parameter;
					} else {
						$data['message'] = $e->getCode().': '.$e->getMessage();
					}
					$content['modal'] = $this->load->view('evernote_create', $data, TRUE);
					$this->readerself_library->set_content($content);
				} catch (EDAMNotFoundException $e) {
					$data['status'] = 'error';
					if(isset(EDAMErrorCode::$__names[$e->errorCode])) {
						$data['message'] = EDAMErrorCode::$__names[$e->errorCode].': '.$e->parameter;
					} else {
						$data['message'] = $e->getCode().': '.$e->getMessage();
					}
					$content['modal'] = $this->load->view('evernote_create', $data, TRUE);
					$this->readerself_library->set_content($content);
				} catch (Exception $e) {
					$data['status'] = 'error';
					$data['message'] = $e->getMessage();
					$content['modal'] = $this->load->view('evernote_create', $data, TRUE);
					$this->readerself_library->set_content($content);
				}
			} else {
				$data['status'] = 'no_token';
			}
		} else {
			$this->output->set_status_header(403);
		}
		$content['modal'] = $this->load->view('evernote_create', $data, TRUE);
		$this->readerself_library->set_content($content);
	}
	function getTemporaryCredentials() {
		try {
			$client = new Client(array(
				'consumerKey' => $this->config->item('evernote/consumer_key'),
				'consumerSecret' => $this->config->item('evernote/consumer_secret'),
				'sandbox' => $this->config->item('evernote/sandbox')
			));
			$requestTokenInfo = $client->getRequestToken($this->getCallbackUrl());
			if($requestTokenInfo) {
				$_SESSION['requestToken'] = $requestTokenInfo['oauth_token'];
				$_SESSION['requestTokenSecret'] = $requestTokenInfo['oauth_token_secret'];
				return TRUE;
			} else {
				$lastError = 'Failed to obtain temporary credentials.';
			}
		} catch (OAuthException $e) {
			$lastError = 'Error obtaining temporary credentials: ' . $e->getMessage();
		}
		return FALSE;
	}
	function handleCallback() {
		if(isset($_GET['oauth_verifier'])) {
			$_SESSION['oauthVerifier'] = $_GET['oauth_verifier'];
			return TRUE;
		} else {
			$lastError = 'Content owner did not authorize the temporary credentials';
			return FALSE;
		}
	}
	function getTokenCredentials() {
		if(isset($_SESSION['accessToken'])) {
			$lastError = 'Temporary credentials may only be exchanged for token credentials once';
			return FALSE;
		}
		try {
			$client = new Client(array(
				'consumerKey' => $this->config->item('evernote/consumer_key'),
				'consumerSecret' => $this->config->item('evernote/consumer_secret'),
				'sandbox' => $this->config->item('evernote/sandbox')
			));
			$accessTokenInfo = $client->getAccessToken($_SESSION['requestToken'], $_SESSION['requestTokenSecret'], $_SESSION['oauthVerifier']);
			if ($accessTokenInfo) {
				return $accessTokenInfo['oauth_token'];
			} else {
				$lastError = 'Failed to obtain token credentials.';
			}
		} catch (OAuthException $e) {
			$lastError = 'Error obtaining token credentials: ' . $e->getMessage();
		}
		return FALSE;
	}
	function getCallbackUrl() {
		return base_url().'evernote/callback';
	}
	function getAuthorizationUrl() {
		$client = new Client(array(
			'consumerKey' => $this->config->item('evernote/consumer_key'),
			'consumerSecret' => $this->config->item('evernote/consumer_secret'),
			'sandbox' => $this->config->item('evernote/sandbox')
		));
	
		return $client->getAuthorizeUrl($_SESSION['requestToken']);
	}
}

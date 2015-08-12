<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Copyright 2010-2012 Evernote Corporation.
 *
 * This file contains configuration information for Evernote's PHP OAuth samples.
 * Before running the sample code, you must change the client credentials below.
 */
 
// Client credentials. Fill in these values with the consumer key and consumer secret 
// that you obtained from Evernote. If you do not have an Evernote API key, you may
// request one from http://dev.evernote.com/documentation/cloud/
define('OAUTH_CONSUMER_KEY', '');
define('OAUTH_CONSUMER_SECRET', '');

// Replace this value with FALSE to use Evernote's production server
define('SANDBOX', TRUE);

/*
 * Copyright 2011-2012 Evernote Corporation.
 *
 * This file contains functions used by Evernote's PHP OAuth samples.
 */


require_once 'thirdparty/evernote/Evernote/Client.php';
require_once 'thirdparty/evernote/packages/Types/Types_types.php';
require_once 'thirdparty/evernote/packages/Errors/Errors_types.php';
require_once 'thirdparty/evernote/packages/Limits/Limits_constants.php';

// Import the classes that we're going to be using
use EDAM\Error\EDAMSystemException,
	EDAM\Error\EDAMUserException,
	EDAM\Error\EDAMErrorCode,
	EDAM\Error\EDAMNotFoundException;
use Evernote\Client;

use EDAM\Types\Data, EDAM\Types\Note, EDAM\Types\Resource, EDAM\Types\ResourceAttributes;

// Verify that you successfully installed the PHP OAuth Extension
if (!class_exists('OAuth')) {
	die("<span style=\"color:red\">The PHP OAuth Extension is not installed</span>");
}

// Verify that you have configured your API key
if (strlen(OAUTH_CONSUMER_KEY) == 0 || strlen(OAUTH_CONSUMER_SECRET) == 0) {
	$configFile = dirname(__FILE__) . '/config.php';
	die("<span style=\"color:red\">Before using this sample code you must edit the file $configFile " .
		  "and fill in OAUTH_CONSUMER_KEY and OAUTH_CONSUMER_SECRET with the values that you received from Evernote. " .
		  "If you do not have an API key, you can request one from " .
		  "<a href=\"http://dev.evernote.com/documentation/cloud/\">http://dev.evernote.com/documentation/cloud/</a></span>");
}

/*
 * The first step of OAuth authentication: the client (this application)
 * obtains temporary credentials from the server (Evernote).
 *
 * After successfully completing this step, the client has obtained the
 * temporary credentials identifier, an opaque string that is only meaningful
 * to the server, and the temporary credentials secret, which is used in
 * signing the token credentials request in step 3.
 *
 * This step is defined in RFC 5849 section 2.1:
 * http://tools.ietf.org/html/rfc5849#section-2.1
 *
 * @return boolean TRUE on success, FALSE on failure
 */
function getTemporaryCredentials()
{
	global $lastError, $currentStatus;
	try {
		$client = new Client(array(
			'consumerKey' => OAUTH_CONSUMER_KEY,
			'consumerSecret' => OAUTH_CONSUMER_SECRET,
			'sandbox' => SANDBOX
		));
		$requestTokenInfo = $client->getRequestToken(getCallbackUrl());
		if ($requestTokenInfo) {
			$_SESSION['requestToken'] = $requestTokenInfo['oauth_token'];
			$_SESSION['requestTokenSecret'] = $requestTokenInfo['oauth_token_secret'];
			$currentStatus = 'Obtained temporary credentials';

			return TRUE;
		} else {
			$lastError = 'Failed to obtain temporary credentials.';
		}
	} catch (OAuthException $e) {
		$lastError = 'Error obtaining temporary credentials: ' . $e->getMessage();
	}

	return FALSE;
}

/*
 * The completion of the second step in OAuth authentication: the resource owner
 * authorizes access to their account and the server (Evernote) redirects them
 * back to the client (this application).
 *
 * After successfully completing this step, the client has obtained the
 * verification code that is passed to the server in step 3.
 *
 * This step is defined in RFC 5849 section 2.2:
 * http://tools.ietf.org/html/rfc5849#section-2.2
 *
 * @return boolean TRUE if the user authorized access, FALSE if they declined access.
 */
function handleCallback()
{
	global $lastError, $currentStatus;
	if (isset($_GET['oauth_verifier'])) {
		$_SESSION['oauthVerifier'] = $_GET['oauth_verifier'];
		$currentStatus = 'Content owner authorized the temporary credentials';

		return TRUE;
	} else {
		// If the User clicks "decline" instead of "authorize", no verification code is sent
		$lastError = 'Content owner did not authorize the temporary credentials';

		return FALSE;
	}
}

/*
 * The third and final step in OAuth authentication: the client (this application)
 * exchanges the authorized temporary credentials for token credentials.
 *
 * After successfully completing this step, the client has obtained the
 * token credentials that are used to authenticate to the Evernote API.
 * In this sample application, we simply store these credentials in the user's
 * session. A real application would typically persist them.
 *
 * This step is defined in RFC 5849 section 2.3:
 * http://tools.ietf.org/html/rfc5849#section-2.3
 *
 * @return boolean TRUE on success, FALSE on failure
 */
function getTokenCredentials()
{
	global $lastError, $currentStatus;

	if (isset($_SESSION['accessToken'])) {
		$lastError = 'Temporary credentials may only be exchanged for token credentials once';

		return FALSE;
	}

	try {
		$client = new Client(array(
			'consumerKey' => OAUTH_CONSUMER_KEY,
			'consumerSecret' => OAUTH_CONSUMER_SECRET,
			'sandbox' => SANDBOX
		));
		$accessTokenInfo = $client->getAccessToken($_SESSION['requestToken'], $_SESSION['requestTokenSecret'], $_SESSION['oauthVerifier']);
		if ($accessTokenInfo) {
			$currentStatus = 'Exchanged the authorized temporary credentials for token credentials';

			return $accessTokenInfo['oauth_token'];
		} else {
			$lastError = 'Failed to obtain token credentials.';
		}
	} catch (OAuthException $e) {
		echo $lastError = 'Error obtaining token credentials: ' . $e->getMessage();
	}

	return FALSE;
}

/*
 * Get the URL of this application. This URL is passed to the server (Evernote)
 * while obtaining unauthorized temporary credentials (step 1). The resource owner
 * is redirected to this URL after authorizing the temporary credentials (step 2).
 */
function getCallbackUrl()
{
	return base_url().'evernote/?action=callback';
}

/*
 * Get the Evernote server URL used to authorize unauthorized temporary credentials.
 */
function getAuthorizationUrl()
{
	$client = new Client(array(
		'consumerKey' => OAUTH_CONSUMER_KEY,
		'consumerSecret' => OAUTH_CONSUMER_SECRET,
		'sandbox' => SANDBOX
	));

	return $client->getAuthorizeUrl($_SESSION['requestToken']);
}

class Evernote extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	public function index() {
		if(!$this->axipi_session->userdata('mbr_id')) {
			//Error obtaining token credentials: Invalid auth/bad request (got a 401, expected HTTP/1.1 20X or a redirect)
			//redirect(base_url());
		}

		$data = array();

	// Status variables
	$lastError = null;
	$currentStatus = null;

	// Request dispatching. If a function fails, $lastError will be updated.
	if (isset($_GET['action'])) {
		$action = $_GET['action'];
		if ($action == 'callback') {
			echo 'a<br>';
			if (handleCallback()) {
				echo 'b<br>';
				$oauth_token = getTokenCredentials();
				if($oauth_token) {
					echo 'c<br>';
					$tok = $this->db->query('SELECT tok.* FROM '.$this->db->dbprefix('tokens').' AS tok WHERE tok.tok_type = ? AND tok.mbr_id = ? GROUP BY tok.tok_id', array('evernote', $this->member->mbr_id))->row();

					$this->db->set('tok_value', $oauth_token);

					if(!$tok) {
						$this->db->set('tok_type', 'evernote');
						$this->db->set('mbr_id', $this->member->mbr_id);
						$this->db->set('tok_datecreated', date('Y-m-d H:i:s'));
						$this->db->insert('tokens');
					} else {
						$this->db->where('tok_type', 'evernote');
						$this->db->where('mbr_id', $this->member->mbr_id);
						$this->db->update('tokens');
					}
				}
			}
		} elseif ($action == 'authorize') {
			if (getTemporaryCredentials()) {
				// We obtained temporary credentials, now redirect the user to evernote.com to authorize access
				header('Location: ' . getAuthorizationUrl());
			}
		} elseif ($action == 'reset') {
			resetSession();
		}
	}

	if (!empty($lastError)) {
		echo '<span style="color:red">' . htmlspecialchars($lastError) . '</span>';
	} else {
		echo '<span style="color:green">' . htmlspecialchars($currentStatus) . '</span>';
	}

		print_r($_SESSION);

		$content = $this->load->view('evernote_index', $data, TRUE);
		$this->readerself_library->set_content($content);
	}
	function list_notebooks() {
		$tok = $this->db->query('SELECT tok.* FROM '.$this->db->dbprefix('tokens').' AS tok WHERE tok.tok_type = ? AND tok.mbr_id = ? GROUP BY tok.tok_id', array('evernote', $this->member->mbr_id))->row();
		try {
			$client = new Client(array(
				'token' => $tok->tok_value,
				'sandbox' => SANDBOX
			));

			$notebooks = $client->getNoteStore()->listNotebooks();
			$result = array();
			if (!empty($notebooks)) {
				foreach ($notebooks as $notebook) {
					$result[] = $notebook->name;
				}
			}
			print_r($result);
			$currentStatus = 'Successfully listed content owner\'s notebooks';

			return TRUE;
		} catch (EDAMSystemException $e) {
			if (isset(EDAMErrorCode::$__names[$e->errorCode])) {
				$lastError = 'Error listing notebooks: ' . EDAMErrorCode::$__names[$e->errorCode] . ": " . $e->parameter;
			} else {
				$lastError = 'Error listing notebooks: ' . $e->getCode() . ": " . $e->getMessage();
			}
		} catch (EDAMUserException $e) {
			if (isset(EDAMErrorCode::$__names[$e->errorCode])) {
				$lastError = 'Error listing notebooks: ' . EDAMErrorCode::$__names[$e->errorCode] . ": " . $e->parameter;
			} else {
				$lastError = 'Error listing notebooks: ' . $e->getCode() . ": " . $e->getMessage();
			}
		} catch (EDAMNotFoundException $e) {
			if (isset(EDAMErrorCode::$__names[$e->errorCode])) {
				$lastError = 'Error listing notebooks: ' . EDAMErrorCode::$__names[$e->errorCode] . ": " . $e->parameter;
			} else {
				$lastError = 'Error listing notebooks: ' . $e->getCode() . ": " . $e->getMessage();
			}
		} catch (Exception $e) {
			$lastError = 'Error listing notebooks: ' . $e->getMessage();
		}
	}
	function create_note($itm_id) {
		$tok = $this->db->query('SELECT tok.* FROM '.$this->db->dbprefix('tokens').' AS tok WHERE tok.tok_type = ? AND tok.mbr_id = ? GROUP BY tok.tok_id', array('evernote', $this->member->mbr_id))->row();
		//add filter on subscription (subscribed only)
		$itm = $this->db->query('SELECT itm.* FROM '.$this->db->dbprefix('items').' AS itm WHERE itm.itm_id = ? GROUP BY itm.itm_id', array($itm_id))->row();
		try {
			$client = new Client(array(
				'token' => $tok->tok_value,
				'sandbox' => SANDBOX
			));

			$noteStore = $client->getNoteStore();
			
			// To create a new note, simply create a new Note object and fill in
			// attributes such as the note's title.
			$note = new Note();
			$note->title = $itm->itm_title;
			
			// The content of an Evernote note is represented using Evernote Markup Language
			// (ENML). The full ENML specification can be found in the Evernote API Overview
			// at http://dev.evernote.com/documentation/cloud/chapters/ENML.php
			$note->content =
				'<?xml version="1.0" encoding="UTF-8"?>' .
				'<!DOCTYPE en-note SYSTEM "http://xml.evernote.com/pub/enml2.dtd">' .
				'<en-note>'.strip_tags($itm->itm_content, '<p><br><br/><em><strong><h1><h2><h3><h4><h5><h6>').'</en-note>';

			// When note titles are user-generated, it's important to validate them
			$len = strlen($note->title);
			$min = $GLOBALS['EDAM_Limits_Limits_CONSTANTS']['EDAM_NOTE_TITLE_LEN_MIN'];
			$max = $GLOBALS['EDAM_Limits_Limits_CONSTANTS']['EDAM_NOTE_TITLE_LEN_MAX'];
			$pattern = '#' . $GLOBALS['EDAM_Limits_Limits_CONSTANTS']['EDAM_NOTE_TITLE_REGEX'] . '#'; // Add PCRE delimiters
			if ($len < $min || $len > $max || !preg_match($pattern, $note->title)) {
				//print "\nInvalid note title: " . $note->title . '\n\n';
				//exit(1);
			}
			
			// Finally, send the new note to Evernote using the createNote method
			// The new Note object that is returned will contain server-generated
			// attributes such as the new note's unique GUID.
			try {
				$createdNote = $noteStore->createNote($note);
				print "Successfully created a new note with GUID: " . $createdNote->guid . "\n";
			} catch (EDAMUserException $edue) {
				// Something was wrong with the note data
				// See EDAMErrorCode enumeration for error code explanation
				// http://dev.evernote.com/documentation/reference/Errors.html#Enum_EDAMErrorCode
				print "EDAMUserException: " . $edue;
			} catch (EDAMNotFoundException $ednfe) {
				// Parent Notebook GUID doesn't correspond to an actual notebook
				print "EDAMNotFoundException: Invalid parent notebook GUID";
			}


			return TRUE;
		} catch (EDAMSystemException $e) {
			if (isset(EDAMErrorCode::$__names[$e->errorCode])) {
				$lastError = 'Error listing notebooks: ' . EDAMErrorCode::$__names[$e->errorCode] . ": " . $e->parameter;
			} else {
				$lastError = 'Error listing notebooks: ' . $e->getCode() . ": " . $e->getMessage();
			}
		} catch (EDAMUserException $e) {
			if (isset(EDAMErrorCode::$__names[$e->errorCode])) {
				$lastError = 'Error listing notebooks: ' . EDAMErrorCode::$__names[$e->errorCode] . ": " . $e->parameter;
			} else {
				$lastError = 'Error listing notebooks: ' . $e->getCode() . ": " . $e->getMessage();
			}
		} catch (EDAMNotFoundException $e) {
			if (isset(EDAMErrorCode::$__names[$e->errorCode])) {
				$lastError = 'Error listing notebooks: ' . EDAMErrorCode::$__names[$e->errorCode] . ": " . $e->parameter;
			} else {
				$lastError = 'Error listing notebooks: ' . $e->getCode() . ": " . $e->getMessage();
			}
		} catch (Exception $e) {
			$lastError = 'Error listing notebooks: ' . $e->getMessage();
		}
	}
}

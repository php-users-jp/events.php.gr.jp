<?php
/**
 * A simple OpenID consumer component for CakePHP.
 * 
 * Requires version 2.1.0 of PHP OpenID library from http://openidenabled.com/php-openid/
 *
 * Copyright (c) by Daniel Hofstetter (http://cakebaker.42dh.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @version			$Revision: 49 $
 * @modifiedby		$LastChangedBy: dho $
 * @lastmodified	$Date: 2008-06-09 08:57:31 +0200 (Mon, 09 Jun 2008) $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
$pathExtra = APP.DS.'vendors'.DS.PATH_SEPARATOR.VENDORS;
$path = ini_get('include_path');
$path = $pathExtra . PATH_SEPARATOR . $path;
ini_set('include_path', $path);

App::import('Vendor', 'consumer', array('file' => 'Auth'.DS.'OpenID'.DS.'Consumer.php'));
App::import('Vendor', 'filestore', array('file' => 'Auth'.DS.'OpenID'.DS.'FileStore.php'));
App::import('Vendor', 'sreg', array('file' => 'Auth'.DS.'OpenID'.DS.'SReg.php'));

class OpenidComponent extends Object {
	private $controller = null;
	
	public function startUp($controller) {
		$this->controller = $controller;
	}
	
	/**
	 * @throws InvalidArgumentException if an invalid OpenID was provided
	 */
	public function authenticate($openidUrl, $returnTo, $realm, $required = array(), $optional = array()) {
		if (trim($openidUrl) != '') {
			$consumer = $this->getConsumer();
			$authRequest = $consumer->begin($openidUrl);
		}
		
		if (!isset($authRequest) || !$authRequest) {
		    throw new InvalidArgumentException('Invalid OpenID');
		}
		
		$sregRequest = Auth_OpenID_SRegRequest::build($required, $optional);
		
		if ($sregRequest) {
			$authRequest->addExtension($sregRequest);
		}
		
		if ($authRequest->shouldSendRedirect()) {
			$redirectUrl = $authRequest->redirectUrl($realm, $returnTo);
			
			if (Auth_OpenID::isFailure($redirectUrl)) {
				throw new Exception('Could not redirect to server: '.$redirectUrl->message);
			} else {
				$this->controller->redirect($redirectUrl, null, true);
			}
		} else {
			$formId = 'openid_message';
			$formHtml = $authRequest->formMarkup($realm, $returnTo, false , array('id' => $formId));

			if (Auth_OpenID::isFailure($formHtml)) {
				throw new Exception('Could not redirect to server: '.$formHtml->message);
			} else {
				echo '<html><head><title>OpenID transaction in progress</title></head>'.
					 "<body onload='document.getElementById(\"".$formId."\").submit()'>".
					 $formHtml.'</body></html>';
				exit;
			}
		}
	}
	
	public function getResponse($currentUrl) {
		$consumer = $this->getConsumer();
		$response = $consumer->complete($currentUrl);
		
		return $response;
	}
	
	private function getConsumer() {
		$storePath = TMP.'openid';

		if (!file_exists($storePath) && !mkdir($storePath)) {
		    throw new Exception('Could not create the FileStore directory '.$storePath.'. Please check the effective permissions.');
		}

		$store = new Auth_OpenID_FileStore($storePath);
		$consumer = new Auth_OpenID_Consumer($store);
		
		return $consumer;
	}
}
?>

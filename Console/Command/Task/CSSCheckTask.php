<?php
App::uses('StyleCheckTask', 'CodingStandards.Console/Command/Task');

class CSSCheckTask extends StyleCheckTask {

	protected $_exts = array('css');

	public function __construct() {
		parent::__construct();
		$this->_path = Configure::read('CodingStandards.CSS_PATH');
	}

	public function execute() {
		$this->_interactive();
	}

	public function run($path, $summary = false) {
		App::uses('HttpSocket', 'Network/Http');
		$HttpSocket = new HttpSocket();

		$validatorURL = "http://jigsaw.w3.org/css-validator/validator";
		try {
			$cssFileURL = $this->toURL($path);
		} catch (Exception $e) {
			return $e->getMessage() . "\r\n";
		}

		$cssValidatorOptions = array(
			'uri' => $cssFileURL,
			'output' => 'text',
			'vextwarning' => 'true', // report vendor extensions as warnings, not errors
			'warning' => 'no' // return only validation errors, not warnings
		);

		$response = $HttpSocket->get($validatorURL, http_build_query($cssValidatorOptions));
		$bodyHTML = $response->body;

		if ($summary) {
			return strpos($bodyHTML, "Congratulations! No Error Found.");
		} else {
			$output = $this->__cleanValidatorOutput($bodyHTML);
			return "CSS formatting errors:\r\n$output\r\n";
		}
	}

	private function __cleanValidatorOutput($html) {
		$cssInformationStart = strpos($html, 'Valid CSS information');
		if ($cssInformationStart) {
			$html = substr($html, 0, $cssInformationStart);
		}

		$successInformationStart = strpos($html, 'This document validates as CSS level 3 !');
		if ($successInformationStart) {
			$html = substr($html, 0, $successInformationStart);
		}

		return strip_tags(trim($html));
	}

/**
 * @throws Exception
 */
	public function toURL($filepath) {
		if (!Configure::read('CodingStandards.SERVER_NAME')) {
			throw new Exception("CakePHP config variable CodingStandards.SERVER_NAME not set when trying to get url for $filepath");
		}
		preg_match('/\/css\/.*$/i', $filepath, $fileURL);
		return 'http://' . Configure::read('CodingStandards.SERVER_NAME') . current($fileURL);
	}
}

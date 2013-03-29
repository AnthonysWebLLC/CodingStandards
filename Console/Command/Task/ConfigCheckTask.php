<?php
App::uses('PHPCheckTask', 'CodingStandards.Console/Command/Task');

class ConfigCheckTask extends PHPCheckTask {

	protected $_exts = array('php');

	public function __construct() {
		parent::__construct();
		$this->_path = APP . 'Config';
	}
}

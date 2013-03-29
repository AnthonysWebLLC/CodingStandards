<?php
App::uses('PHPCheckTask', 'CodingStandards.Console/Command/Task');

class ModelCheckTask extends PHPCheckTask {

	protected $_exts = array('php');

	public function __construct() {
		parent::__construct();
		$this->_path = current(App::path('Model'));
	}
}

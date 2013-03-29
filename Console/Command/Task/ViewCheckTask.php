<?php
App::uses('PHPCheckTask', 'CodingStandards.Console/Command/Task');

class ViewCheckTask extends PHPCheckTask {

	protected $_exts = array('ctp');

	public function __construct() {
		parent::__construct();
		$this->_path = current(App::path('View'));
	}
}

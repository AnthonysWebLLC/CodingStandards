<?php
App::uses('PHPCheckTask', 'CodingStandards.Console/Command/Task');

class ModelCheckTask extends PHPCheckTask {

	protected $exts = array('php');

	public function __construct() {
		parent::__construct();
		$this->path = current(App::path('Model'));
	}
}

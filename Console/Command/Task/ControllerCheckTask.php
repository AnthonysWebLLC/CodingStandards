<?php
App::uses('PHPCheckTask', 'CodingStandards.Console/Command/Task');

class ControllerCheckTask extends PHPCheckTask {
	protected $exts = array('php');

	function __construct(){
		parent::__construct();
		$this->path = current(App::path('Controller'));
	}
}

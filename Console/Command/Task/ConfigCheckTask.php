<?php
App::uses('PHPCheckTask', 'CodingStandards.Console/Command/Task');

class ConfigCheckTask extends PHPCheckTask {
	protected $exts = array('php');

	function __construct(){
		parent::__construct();
		$this->path = APP . 'Config';
	}
}

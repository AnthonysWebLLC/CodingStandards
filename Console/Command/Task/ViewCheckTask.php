<?php
App::uses('PHPCheckTask', 'CodingStandards.Console/Command/Task');

class ViewCheckTask extends PHPCheckTask {

	protected $exts = array('ctp');

	public function __construct() {
		parent::__construct();
		$this->path = current(App::path('View'));
	}
}

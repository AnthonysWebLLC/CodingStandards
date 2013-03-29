<?php
App::uses('AnyCheckTask', 'CodingStandards.Console/Command/Task');

class ConsoleCheckTask extends AnyCheckTask {

	public function __construct() {
		parent::__construct();
		$this->_path = current(App::path('Console'));
	}
}

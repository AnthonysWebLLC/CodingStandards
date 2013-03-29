<?php
App::uses('AnyCheckTask', 'CodingStandards.Console/Command/Task');

class ConsoleCheckTask extends AnyCheckTask {
	function __construct(){
		parent::__construct();
		$this->path = current(App::path('Console'));
	}
}

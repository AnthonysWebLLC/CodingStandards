<?php
App::uses('PHPCheckTask', 'CodingStandards.Console/Command/Task');

class ViewCheckTask extends PHPCheckTask {

	// Hacking out ctp inclusion as the coding standards there aren't really ready; It's just doing php checking which isn't good for views -- Anthony June 4th 2013
	// protected $_exts = array('ctp');
	protected $_exts = array('[ViewCodingStandardsChecksSuppressed]');

	public function __construct() {
		parent::__construct();
		$this->_path = current(App::path('View'));
	}
}

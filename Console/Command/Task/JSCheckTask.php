<?php
App::uses('StyleCheckTask', 'CodingStandards.Console/Command/Task');

class JSCheckTask extends StyleCheckTask {

	protected $_exts = array('js');

	public function __construct() {
		parent::__construct();
		$this->_path = Configure::read('CodingStandards.JS_PATH');
	}

	public function execute() {
		$this->_interactive();
	}

	public function run($filepath, $summary = false) {
		$parentOutput = parent::run($filepath, $summary);

		$pluginPath = Configure::read('CodingStandards.PLUGIN_PATH');
		$jscheckPath = $pluginPath . DS . 'Vendor' . DS . 'jshint';

		if ($summary) {
			exec($jscheckPath . DS . "jscheck.sh $filepath", $result);
			return empty($result) && $parentOutput;
		} else {
			exec($jscheckPath . DS . "jscheck.sh $filepath", $result);
			$output = implode("\r\n", $result);
		}

		return "$parentOutput\r\nJavaScript formatting errors:\r\n$output";
	}
}

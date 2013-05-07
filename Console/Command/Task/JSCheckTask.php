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
		$jsHintGlobals = Configure::read('CodingStandards.JSHINT_GLOBALS');

		$start = microtime(true);
		exec($jscheckPath . DS . "jscheck.sh $filepath $jsHintGlobals", $result);
		$secondsRan = microtime(true) - $start;

		if ($summary) {
			return empty($result) && $parentOutput;
		} else {
			$output = implode("\r\n", $result);
			if ($output) {
				return "$parentOutput\r\nJavaScript formatting errors:\r\n<failure>$output</failure>";
			} else {
				return "$parentOutput <success>[JS Coding Standards checks passed (" . sprintf('%01.2f', $secondsRan) . "s)]</success>";
			}
		}
	}
}

<?php
App::uses('StyleCheckTask', 'CodingStandards.Console/Command/Task');
App::uses('PHPCheckTask', 'CodingStandards.Console/Command/Task');
App::uses('CSSCheckTask', 'CodingStandards.Console/Command/Task');
App::uses('JSCheckTask', 'CodingStandards.Console/Command/Task');

class AnyCheckTask extends StyleCheckTask {

	protected $_exts = array('php', 'ctp', 'js', 'css');

	public function setPath($path) {
		$this->_path = $path;
	}

	public function execute() {
		$this->_interactive();
	}

	public function run($filepath, $summary = false) {
		$parentOutput = parent::run($filepath, $summary);

		$ext = pathinfo($filepath, PATHINFO_EXTENSION);

		foreach (array('PHP', 'CSS', 'JS') as $LeafCheckType) {
			$CheckTaskName = "{$LeafCheckType}CheckTask";
			$CheckTaskObject = new $CheckTaskName();
			if ($CheckTaskObject->checksExt($ext)) {
				return $CheckTaskObject->run($filepath);
			}
		}
	}
}

<?php
App::uses('StyleCheckTask', 'CodingStandards.Console/Command/Task');
App::uses('PHPCheckTask', 'CodingStandards.Console/Command/Task');
App::uses('CSSCheckTask', 'CodingStandards.Console/Command/Task');
App::uses('JSCheckTask', 'CodingStandards.Console/Command/Task');

class AnyCheckTask extends StyleCheckTask {

	protected $path = null;

	protected $exts = array('php', 'ctp', 'js', 'css');

	public function setPath($path){
		$this->path = $path;
	}

    public function execute() {
		$this->_interactive();
    }

    public function run($path, $summary = false) {
        $parentOutput = parent::run($path, $summary);

		$ext = pathinfo($path, PATHINFO_EXTENSION);

		foreach(array('PHP', 'CSS', 'JS') AS $LeafCheckType){
			$CheckTaskName = "{$LeafCheckType}CheckTask";
			$CheckTaskObject = new $CheckTaskName();
			if($CheckTaskObject->checksExt($ext)){
				return $CheckTaskObject->run($path);
			}
		}
    }
}

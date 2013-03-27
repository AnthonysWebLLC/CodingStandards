<?php
App::uses('StyleCheckTask', 'CodingStandards.Console/Command/Task');

class JSCheckTask extends StyleCheckTask {
	protected $exts = array('js');

    public function execute() {
        $this->path = Configure::read('CodingStandards.JS_PATH');
        $this->_interactive();
    }

    public function run($path, $summary = false) {
        $parentOutput = parent::run($path, $summary);

        $pluginPath = Configure::read('CodingStandards.PLUGIN_PATH');
        $jscheckPath = $pluginPath . DS . 'Vendor' . DS . 'jshint';

        if ($summary) {
            exec($jscheckPath . DS . "jscheck.sh $path", $result);
            return empty($result) && $parentOutput;
        } else {
            exec($jscheckPath . DS . "jscheck.sh $path", $result);
            $output = implode("\r\n", $result);
        }

        return "$parentOutput\r\nJavaScript formatting errors:\r\n$output\r\n";
    }
}

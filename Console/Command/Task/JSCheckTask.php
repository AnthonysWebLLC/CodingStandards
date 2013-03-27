<?php
App::uses('StyleCheckTask', 'CodingStandards.Console/Command/Task');

class JSCheckTask extends StyleCheckTask {

    public function execute() {
        $this->path = Configure::read('CodingStandards.JS_PATH');
        $this->_interactive();
    }

    protected function _interactive() {
        $this->hr();
        $this->out(__d('cake_console', "Validate file\nPath: %s", $this->path));
        $this->hr();

        $this->_files = $this->getAllFiles('.*\.js');
		if(empty($this->_files)){
			$this->out("No JavaScript files found");
			return;
		}

        $options = array_merge($this->_files, array('All files'));

        $option = $this->inOptions($options, 'Choose which file you want to validate:');

        $filename = $options[$option];


        if ($filename == 'All files') {
            $path = $this->path . DS . '*';
        } else {
            $path = $filename;
        }

        $output = $this->run($path);

        $this->out($output);
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

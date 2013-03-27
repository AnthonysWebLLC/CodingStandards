<?php
App::uses('StyleCheckTask', 'CodingStandards.Console/Command/Task');

class PHPCheckTask extends StyleCheckTask {

    public function execute($classToValidate = false) {
        switch ($classToValidate) {
            case 'model':
                $this->path = current(App::path('Model'));
                $this->_interactive();
                break;
            case 'view':
                $this->path = current(App::path('View'));
                $this->_interactive();
                break;
            case 'controller':
                $this->path = current(App::path('Controller'));
                $this->_interactive();
                break;
            default:
                $this->out(__d('cake_console', 'Invalid selection.'));
                exit(0);
                break;
        }
    }

    protected function _interactive() {
        $this->hr();
        $this->out(__d('cake_console', "Validate Class\nPath: %s", $this->path));
        $this->hr();

        $this->_files = $this->getAllFiles('.*\.php|.*\.ctp');
		if(empty($this->_files)){
			$this->out("No PHP files found");
			return;
		}

        $options = array_merge($this->_files, array('All files'));

        $option = $this->inOptions($options, 'Choose which file you want to validate:');

        $filename = $options[$option];

        if ($filename == 'All files') {
            $path = $this->path;
        } else {
            $path = $filename;
        }

        $output = $this->run($path);

        $this->out($output);
    }

    public function run($path, $summary = false) {
        $parentOutput = parent::run($path, $summary);

        if ($summary) {
            exec("phpcs --warning-severity=0 --extensions=ctp,php --standard=CakePHP --report=summary $path", $result);
            return empty($result) && $parentOutput;
        } else {
            exec("phpcs --warning-severity=0 --extensions=ctp,php --standard=CakePHP $path", $result);
            $output = implode("\r\n", $result);
			if(strlen($output)){
				$return = "$parentOutput\r\nPHP formatting errors:\r\n$output\r\n";
			} else {
				$return = "$parentOutput\r\n[No PHP formatting errors found]";
			}
			return $return;
        }

    }
}

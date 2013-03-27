<?php
App::uses('StyleCheckTask', 'CodingStandards.Console/Command/Task');

class PHPCheckTask extends StyleCheckTask {
	protected $exts = array('php', 'ctp');

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

    public function run($path, $summary = false) {
        $parentOutput = parent::run($path, $summary);

        if ($summary) {
            exec("phpcs --warning-severity=0 --extensions=ctp,php --standard=CakePHP --report=summary $path", $result);
            return empty($result) && $parentOutput;
        } else {
            exec("phpcs --warning-severity=0 --extensions=ctp,php --standard=CakePHP $path", $result);
            $output = implode("\r\n", $result);
			if(strlen($output)){
				$return = "$parentOutput\r\nPHP Coding Standards errors:\r\n$output\r\n";
			} else {
				$return = "$parentOutput\r\n[PHP Coding Standards checks passed]";
			}
			return $return;
        }

    }
}

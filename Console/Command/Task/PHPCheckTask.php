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
			$result = $this->_strip_legacy_camel_case_errors($result);
            return empty($result) && $parentOutput;
        } else {
            exec("phpcs --warning-severity=0 --extensions=ctp,php --standard=CakePHP $path", $result);
			$result = $this->_strip_legacy_camel_case_errors($result);
            $output = implode("\r\n", $result);
			if(strlen($output)){
				$return = "$parentOutput\r\nPHP Coding Standards errors:\r\n$output\r\n";
			} else {
				$return = "$parentOutput\r\n[PHP Coding Standards checks passed]";
			}
			return $return;
        }

    }

	private function _strip_legacy_camel_case_errors($result_lines){
		$result_lines = $this->_strip_errors($result_lines, "Variable \"title_for_layout\" is not in valid camel caps format");
		$result_lines = $this->_strip_errors($result_lines, "Variable \"scripts_for_layout\" is not in valid camel caps format");
		$result_lines = $this->_strip_errors($result_lines, "Variable \"page_title\" is not in valid camel caps format");

		return $result_lines;
	}

	private function _strip_errors($result_lines, $search){
		// Strip individual error lines
		$removed_count = 0;
		foreach($result_lines AS $line_number=>$result_line){
			if(false !== strpos($result_line, $search)){
				unset($result_lines[$line_number]);
				$removed_count++;
			}
		}

		if($removed_count){
			// Fix top error summary
			preg_match('/^FOUND ([0-9]+) ERROR\\(S\\) AFFECTING ([0-9]+) LINE\\(S\\)$/', $result_lines[3], $matches);
			$errors = $matches[1] - 1;
			$lines = $matches[2] - 1;

			// No errors so return empty output as it would have otherwise
			if(0 === $errors){
				return array();
			}
		}

		return $result_lines;
	}
}

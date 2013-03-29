<?php
App::uses('StyleCheckTask', 'CodingStandards.Console/Command/Task');

class PHPCheckTask extends StyleCheckTask {

	protected $exts = array('php', 'ctp');

	public function execute() {
		$this->_interactive();
	}

	public function run($path, $summary = false) {
		$parentOutput = parent::run($path, $summary);

		$start = microtime(true);
		exec("phpcs --warning-severity=0 --extensions=ctp,php --standard=CakePHP $path", $result);
		$result = $this->__stripLegacyCamelCaseErrors($result);
		$secondsRan = microtime(true) - $start;
		if ($summary) {
			return empty($result) && $parentOutput;
		} else {
			$output = implode("\r\n", $result);
			if (strlen($output)) {
				$return = "$parentOutput\r\nPHP Coding Standards errors:\r\n$output\r\n";
			} else {
				$return = "$parentOutput [PHP Coding Standards checks passed (" . sprintf('%01.2f', $secondsRan) . "s)] \r\n";
			}
			return $return;
		}
	}

	private function __stripLegacyCamelCaseErrors($resultLines) {
		$resultLines = $this->__stripErrors($resultLines, "Variable \"title_for_layout\" is not in valid camel caps format");
		$resultLines = $this->__stripErrors($resultLines, "Variable \"scripts_for_layout\" is not in valid camel caps format");
		$resultLines = $this->__stripErrors($resultLines, "Variable \"page_title\" is not in valid camel caps format");

		return $resultLines;
	}

	private function __stripErrors($resultLines, $search) {
		// Strip individual error lines
		$removedCount = 0;
		foreach ($resultLines as $lineNumber => $resultLine) {
			if (false !== strpos($resultLine, $search)) {
				unset($resultLines[$lineNumber]);
				$removedCount++;
			}
		}

		if ($removedCount) {
			// Fix top error summary
			preg_match('/^FOUND ([0-9]+) ERROR\\(S\\) AFFECTING ([0-9]+) LINE\\(S\\)$/', $resultLines[3], $matches);
			$errors = $matches[1] - $removedCount;
			$lines = $matches[2] - $removedCount;

			// No errors so return empty output as it would have otherwise
			if (0 === $errors) {
				return array();
			}
			$resultLines[3] = sprintf('FOUND %s ERROR(S) AFFECTING %s LINE(S)', $errors, $lines);
		}

		return $resultLines;
	}
}

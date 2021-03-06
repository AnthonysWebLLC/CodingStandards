<?php
App::uses('StyleCheckTask', 'CodingStandards.Console/Command/Task');

class PHPCheckTask extends StyleCheckTask {

	protected $_exts = array('php', 'ctp');

	public function execute() {
		$this->_interactive();
	}

	public function run($filepath, $summary = false) {
		$parentOutput = parent::run($filepath, $summary);

		$start = microtime(true);
		exec("phpcs --warning-severity=0 --standard=CakePHP $filepath", $result);
		$result = $this->__stripLegacyCamelCaseErrors($result);
		$result = $this->_stripRunTiming($result);
		$secondsRan = microtime(true) - $start;
		if ($summary) {
			return (0 === strlen(implode('', $result))) && $parentOutput;
		} else {
			$output = implode("\r\n", $result);
			if (strlen($output)) {
				$return = "$parentOutput\r\n<failure>PHP Coding Standards errors:\r\n$output</failure>";
			} else {
				$return = "$parentOutput <success>[PHP Coding Standards checks passed (" . sprintf('%01.2f', $secondsRan) . "s)]</success>";
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

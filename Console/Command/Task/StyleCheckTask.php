<?php
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

class StyleCheckTask extends Shell {

	public $tasks = array(
		'CodingStandards.AnyCheck',
		'CodingStandards.ModelCheck',
		'CodingStandards.ViewCheck',
		'CodingStandards.ControllerCheck',
		'CodingStandards.JSCheck',
		'CodingStandards.CSSCheck',
		'CodingStandards.ConfigCheck',
		'CodingStandards.ConsoleCheck',
		'CodingStandards.Options',
		'Template'
	);

	protected $_path = null;

	protected $_exts = null;

	public function checksExt($ext) {
		return in_array(strtolower($ext), array_map('strtolower', $this->_exts));
	}

	protected function _getAllFiles() {
		// Sanity Check
		if (empty($this->_exts)) {
			$this->err(__d('cake_console', "No exts set for " . __CLASS__));
			$this->_stop();
		}

		// Find
		$regex = '.*\.' . implode('$|.*\.', $this->_exts) . '$';
		$folder = new Folder($this->_path);
		$files = $folder->findRecursive($regex, true);

		// Ignore
		foreach ($files as $key => $file) {
			foreach (Configure::read('CodingStandards.PATH_IGNORE_PATTERNS') as $pathIgnorePattern) {
				if (preg_match($pathIgnorePattern, $file)) {
					unset($files[$key]);
					continue 2;
				}
			}
		}

		return $files;
	}

	public function fullReport() {
		$this->out(__d('cake_console', "Generating report:"));

		$reportDateTime = date('Y-m-d H:i:s');

		$checks = array(
			'Model',
			'View',
			'Controller',
			'JS',
			'CSS',
			'Config',
			'Console'
		);
		$start = microtime(true);
		$checkResults = array();
		foreach ($checks as $check) {
			$output = '';
			$checkClass = "${check}Check";

			$startCheckGroup = microtime(true);
			$files = $this->$checkClass->_getAllFiles();
			echo ' Checking ' . count($files) . ' ' . $check . ' file' . (count($files) > 1?'s':'');
			if (empty($files)) {
				$output .= "No $check files found";
			} else {
				foreach ($files as $filepath) {
					$output .= $this->$checkClass->run($filepath) . "\r\n";
					echo '.';
				}
			}
			trim($output);
			echo "\r\n";
			$secondsRanCheckGroup = microtime(true) - $startCheckGroup;
			$checkResults[$check] = array('output' => $output, 'secondsRan' => $secondsRanCheckGroup);
		}

		foreach (Configure::read('CodingStandards.ADDITIONAL_PATHS') as $additionalPath) {
			$output = '';

			$startCheckGroup = microtime(true);
			$this->AnyCheck->setPath($additionalPath);
			$files = $this->AnyCheck->_getAllFiles();
			echo ' Checking ' . count($files) . ' Additional file' . (count($files) > 1?'s':'') . ' in ' . $additionalPath;
			foreach ($files as $filepath) {
				$output .= $this->AnyCheck->run($filepath) . "\r\n";
				echo '.';
			}
			trim($output);
			echo "\r\n";
			$secondsRanCheckGroup = microtime(true) - $startCheckGroup;
			$checkResults["Additional::$additionalPath"] = array('output' => $output, 'secondsRan' => $secondsRanCheckGroup);
		}
		$secondsRan = microtime(true) - $start;

		$pluginPath = Configure::read('CodingStandards.PLUGIN_PATH');
		$this->Template->templatePaths = array($pluginPath . DS . 'Console' . DS . 'Templates' . DS);
		$this->Template->set(compact('reportDateTime', 'checkResults', 'secondsRan', 'additionalCheckResults'));

		$HTMLreport = $this->Template->generate('code_style_checks', 'report');
		$filepath = $pluginPath . DS . 'tmp' . DS . 'reports' . DS . 'full' . DS . date('Y-m-d__H-i-s') . '.ctp';

		file_put_contents($filepath, $HTMLreport);

		$reportURL = (Configure::read('CodingStandards.SERVER_NAME')?'http://' . Configure::read('CodingStandards.SERVER_NAME'):'');
		$reportURL .= '/coding_standards/reports/view/full/' . date('Y-m-d__H-i-s');
		$this->out(__d('cake_console', "\r\nOpen $reportURL in a web browser to view the report."));
	}

	public function run($filepath, $summary = false) {
		$sniffs = 'Generic.Files.ByteOrderMark'; // Files MUST use only UTF-8 without BOM
		$sniffs .= 'Generic.WhiteSpace.DisallowSpaceIndent'; // Code MUST use an tab indent, and MUST NOT use spaces for indenting
		$sniffs .= ',Generic.Files.LineEndings'; // All files MUST use the Unix LF (linefeed) line ending

		// There MUST NOT be trailing whitespace at the end of non-blank lines
		$sniffs .= ',Squiz.WhiteSpace.SuperfluousWhitespace';
		$sniffs .= ',Squiz.WhiteSpace.SuperfluousWhitespace.StartFile';
		$sniffs .= ',Squiz.WhiteSpace.SuperfluousWhitespace.EndFile';
		$sniffs .= ',Squiz.WhiteSpace.SuperfluousWhitespace.EmptyLines';

		if ($summary) {
			exec("phpcs --standard=CakePHP --report=summary --sniffs=$sniffs $filepath", $result);
			return empty($result);
		} else {
			$start = microtime(true);
			exec("phpcs --warning-severity=0 --standard=CakePHP --sniffs=$sniffs $filepath", $result);
			$secondsRan = microtime(true) - $start;
			$result = implode("\r\n", $result);
			if (strlen($result)) {
				$result = "File formatting errors:\r\n<failure>$result</failure>\r\n";
			} else {
				if (strlen($filepath) > 40) {
					$filepath = '...' . substr($filepath, -37);
				}
				$filepath = sprintf("%40s", $filepath);
				$result = "$filepath <success>[Base file formatting checks passed (" . sprintf('%01.2f', $secondsRan) . "s)]</success>";
			}
			return $result;
		}
	}

	protected function _interactive() {
		$this->Options->menuHeader('File choice');
		$this->out(__d('cake_console', "%s files under %s", '.' . implode(', .', $this->_exts), $this->_path));
		echo "\r\n";

		$files = $this->_getAllFiles();
		if (empty($files)) {
			$this->out("No " . implode(',', $this->_exts) . " files found");
			return;
		}

		$options = array_merge($files, array('All files'));

		$filename = $options[$this->Options->select($options, 'Choose which file to validate:')];

		$filesToValidate = array();
		if ($filename == 'All files') {
			foreach ($files as $filepath) {
				$filesToValidate[] = $filepath;
			}
		} else {
			$filesToValidate[] = $filename;
		}

		$output = '';

		echo 'Checking ' . count($filesToValidate) . ' file' . (count($filesToValidate) > 1?'s':'');
		foreach ($filesToValidate as $url) {
			$output .= $this->run($url) . "\r\n";
			echo '.';
		}
		$output = trim($output);
		echo "\r\n";

		$this->out($output);
	}
}

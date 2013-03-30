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

	public function inOptions($options, $prompt = null, $default = null) {
		$valid = false;
		$max = count($options);
		while (!$valid) {
			$len = strlen(count($options) + 1);
			foreach ($options as $i => $option) {
				$this->out(sprintf("%${len}d. %s", $i + 1, $option));
			}
			if (empty($prompt)) {
				$prompt = __d('cake_console', 'Make a selection from the choices above');
			}
			$choice = $this->in($prompt, null, $default);
			if (intval($choice) > 0 && intval($choice) <= $max) {
				$valid = true;
			}
		}
		return $choice - 1;
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
					$output .= $this->$checkClass->run($filepath);
					echo '.';
				}
			}
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
				$output .= $this->AnyCheck->run($filepath);
				echo '.';
			}
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

		$this->Template->createFile($filepath, $HTMLreport);

		$reportURL = (Configure::read('CodingStandards.SERVER_NAME')?'http://' . Configure::read('CodingStandards.SERVER_NAME'):'');
		$reportURL .= '/coding_standards/reports/view/full/' . date('Y-m-d__H-i-s');
		$this->out(__d('cake_console', "Open $reportURL in web browser to view report."));
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
				$result = "File formatting errors:\r\n$result\r\n";
			} else {
				if (strlen($filepath) > 40) {
					$filepath = '...' . substr($filepath, -37);
				}
				$filepath = sprintf("%40s", $filepath);
				$result = "[File: $filepath] [Base file formatting checks passed (" . sprintf('%01.2f', $secondsRan) . "s)]";
			}
			return $result;
		}
	}

	protected function _interactive() {
		$this->hr();
		$this->out(__d('cake_console', "Validate file\nPath: %s", $this->_path));
		$this->hr();

		$files = $this->_getAllFiles();
		if (empty($files)) {
			$this->out("No " . implode(',', $this->_exts) . " files found");
			return;
		}

		$options = array_merge($files, array('All files'));

		$option = $this->inOptions($options, 'Choose which file you want to validate:');

		$filename = $options[$option];

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
			$output .= $this->run($url);
			echo '.';
		}
		echo "\r\n";

		$this->out($output);
	}
}

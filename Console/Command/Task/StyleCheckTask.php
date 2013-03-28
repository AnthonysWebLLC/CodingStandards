<?php
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

class StyleCheckTask extends Shell {

    public $tasks = array(
        'CodingStandards.ModelCheck',
        'CodingStandards.ViewCheck',
        'CodingStandards.ControllerCheck',
        'CodingStandards.JSCheck',
        'CodingStandards.CSSCheck',
        'Template'
    );

    protected $path = null;

    protected $files = array();

	protected $exts = array();

    protected function getAllFiles() {
		// Sanity Check
		if(empty($this->exts)){
			$this->err(__d('cake_console', "No exts set for " . __CLASS__));
			$this->_stop();
		}

		// Find
		$regex = '.*\.' . implode('|.*\.', $this->exts);
        $folder = new Folder($this->path);
        $files = $folder->findRecursive($regex, true);

		// Ignore
		foreach($files AS $key=>$file){
			foreach(Configure::read('CodingStandards.PATH_IGNORE_PATTERNS') AS $pathIgnorePattern){
				if(preg_match($pathIgnorePattern, $file)){
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
			'CSS'
		);
		$start = microtime(true);
		$checkResults = array();
		foreach($checks AS $check){
			$output = '';
			$checkClass = "${check}Check";

			$startCheckGroup = microtime(true);
			$files = $this->$checkClass->getAllFiles();
			echo ' Checking ' . count($files) . ' ' . $check . ' file' . (count($files) > 1?'s':'');
			foreach ($files as $filepath) {
				$output .= strip_tags($this->$checkClass->run($filepath));
				echo '.';
			}
			echo "\r\n";
			$secondsRanCheckGroup = microtime(true) - $startCheckGroup;
			$checkResults[$check] = array('output'=>$output, 'secondsRan'=>$secondsRanCheckGroup);
		}
		$secondsRan = microtime(true) - $start;

        $pluginPath = Configure::read('CodingStandards.PLUGIN_PATH');
        $this->Template->templatePaths = array($pluginPath . DS . 'Console' . DS . 'Templates' . DS);
        $this->Template->set(compact('reportDateTime', 'checkResults', 'secondsRan'));

        $HTMLreport = $this->Template->generate('code_style_checks', 'report');
        $filepath = current(App::path('View')) . DS . 'Pages' . DS . 'code_style_check_report.ctp';

        $this->Template->createFile($filepath, $HTMLreport);

		$reportURL = (Configure::read('CodingStandards.SERVER_NAME')?Configure::read('CodingStandards.SERVER_NAME'):'');
		$reportURL .= '/pages/code_style_check_report';
        $this->out(__d('cake_console', "Open $reportURL in web browser to view report."));
    }

    public function run($path, $summary = false) {
        $sniffs = 'Generic.Files.ByteOrderMark'; // Files MUST use only UTF-8 without BOM
        $sniffs .= 'Generic.WhiteSpace.DisallowSpaceIndent'; // Code MUST use an tab indent, and MUST NOT use spaces for indenting
        $sniffs .= ',Generic.Files.LineEndings'; // All files MUST use the Unix LF (linefeed) line ending

        // There MUST NOT be trailing whitespace at the end of non-blank lines
        $sniffs .= ',Squiz.WhiteSpace.SuperfluousWhitespace';
        $sniffs .= ',Squiz.WhiteSpace.SuperfluousWhitespace.StartFile';
        $sniffs .= ',Squiz.WhiteSpace.SuperfluousWhitespace.EndFile';
        $sniffs .= ',Squiz.WhiteSpace.SuperfluousWhitespace.EmptyLines';

        // Style check for CSS files can last long so it's turned off by default
        if ($summary) {
            exec("phpcs --extensions=php,ctp,js --standard=CakePHP --report=summary --sniffs=$sniffs $path", $result);
            return empty($result);
        } else {
			$start = microtime(true);
            exec("phpcs --warning-severity=0  --extensions=php,ctp,js --standard=CakePHP --sniffs=$sniffs $path", $result);
			$secondsRan = microtime(true) - $start;
            $result = implode("\r\n", $result);
			if(strlen($result)) {
				$result = "File formatting errors:\r\n$result\r\n";
			} else {
				if(strlen($path) > 40){
					$path = '...' . substr($path, -37);
				}
				$path = sprintf("%40s", $path);
				$result = "[File: $path] [Base file formatting checks passed (".sprintf('%01.2f', $secondsRan)."s)]";
			}
            return $result;
        }
    }

	public function _interactive() {
        $this->hr();
        $this->out(__d('cake_console', "Validate file\nPath: %s", $this->path));
        $this->hr();

        $this->_files = $this->getAllFiles();
		if(empty($this->_files)){
			$this->out("No ".implode(',', $this->exts)." files found");
			return;
		}

        $options = array_merge($this->_files, array('All files'));

        $option = $this->inOptions($options, 'Choose which file you want to validate:');

        $filename = $options[$option];

        $filesToValidate = array();
        if ($filename == 'All files') {
            foreach ($this->_files as $filepath) {
                $filesToValidate[] = $filepath;
            }
        } else {
            $filesToValidate[] = $filename;
        }

        $output = '';

		echo 'Checking ' . count($filesToValidate) . ' file'. (count($filesToValidate) > 1?'s':'');
        foreach ($filesToValidate as $url) {
            $output .= $this->run($url);
			echo '.';
        }
		echo "\r\n";

        $this->out($output);
    }
}

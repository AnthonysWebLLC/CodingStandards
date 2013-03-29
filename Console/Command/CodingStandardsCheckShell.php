<?php
App::uses('Folder', 'Utility');

class CodingStandardsCheckShell extends AppShell {

	public $tasks = array(
		'CodingStandards.StyleCheck',
		'CodingStandards.AnyCheck',
		'CodingStandards.PHPCheck',
		'CodingStandards.ModelCheck',
		'CodingStandards.ViewCheck',
		'CodingStandards.ControllerCheck',
		'CodingStandards.JSCheck',
		'CodingStandards.CSSCheck'
	);

	public function main() {
		$options = array('M', 'V', 'C', 'J', 'S', 'F', 'Q');
		$this->out(__d('cake_console', 'Coding Standards Check Shell'));
		$this->hr();
		$this->out(__d('cake_console', '[M]odels'));
		$this->out(__d('cake_console', '[V]iews'));
		$this->out(__d('cake_console', '[C]ontrollers'));
		$this->out(__d('cake_console', '[J]avaScript'));
		$this->out(__d('cake_console', '[S]tylesheets'));
		if(count(Configure::read('CodingStandards.ADDITIONAL_PATHS'))){
			$this->out(__d('cake_console', '[A]dditional'));
			$options[] = 'A';
		}
		$this->out(__d('cake_console', '[F]ull report in HTML format'));
		$this->out(__d('cake_console', '[Q]uit'));

		$classToValidate = strtoupper($this->in(__d('cake_console', 'What would you like to validate?'), $options));

		switch ($classToValidate) {
			case 'M':
				$this->ModelCheck->execute();
				break;
			case 'V':
				$this->ViewCheck->execute();
				break;
			case 'C':
				$this->ControllerCheck->execute();
				break;
			case 'J':
				$this->JSCheck->execute();
				break;
			case 'S':
				$this->CSSCheck->execute();
				break;
			case 'A':
				// Choose subpath (or auto-select iff one path)
				$additionalPaths = Configure::read('CodingStandards.ADDITIONAL_PATHS');
				if(empty($additionalPaths)){
					$this->out(__d('cake_console', 'No CodingStandards.ADDITIONAL_PATHS defined'));
					break;
				} elseif(count($additionalPaths) == 1){
					$additionalPath				= array_pop($additionalPaths);
				} else {
					$options = array();
					$ii = 0;
					$this->out(__d('cake_console', 'Additional Paths:'));
					$this->hr();
					foreach($additionalPaths AS $additionalPath){
						$option = $additionalPath;
						$options[$ii++] = $option;
					}
					$additionalPath = $this->inOptions($options, 'Which path?');
				}
				$this->AnyCheck->setPath($additionalPath);
				$this->AnyCheck->execute();
				break;
			case 'F':
				$this->StyleCheck->fullReport();
				break;
			case 'Q':
				exit(0);
				break;
			default:
				$this->out(__d('cake_console', 'You have made an invalid selection. Please choose a type of class to validate by entering M, V, C, J, S or F.'));
		}
		$this->hr();
		$this->main();
	}

	public function ignore_file_status() {
		$filepath = $this->args[0];
		$output = false;
		foreach(Configure::read('CodingStandards.PATH_IGNORE_PATTERNS') AS $pathIgnorePattern){
			if(preg_match($pathIgnorePattern, $filepath)){
				$output = true;
				break;
			}
		}
		var_export($output);
	}

	public function check_file() {
		$filepath = $this->args[0];
		$option = $this->args[1];

		$filetype = pathinfo($filepath, PATHINFO_EXTENSION);

		switch($option) {
			case 'full_report':
				$summary = false;
				break;
			case 'summary';
				$summary = true;
				break;
			default:
				$this->out('Invalid option. Use full_report | summary.');
				exit;
		}

		$file = new File($filepath);
		if (!$file->exists()) {
			echo "$filepath doesn't exist";
			exit;
		}

		switch ($filetype) {
			case 'ctp':
				$output = $this->ViewCheck->run($filepath, $summary);
				break;
			case 'php':
				$output = $this->PHPCheck->run($filepath, $summary);
				break;
			case 'js':
				$output = $this->JSCheck->run($filepath, $summary);
				break;
			case 'css':
				$output = $this->CSSCheck->run($filepath, $summary);
				break;
		}

		if ($summary) {
			var_export($output);
		} else {
			$this->out($output);
		}
	}

	public function install() {
		$this->out('Installing PHP-Pear');
		$result = $this->_execCommand("yum install php-pear -y");
		if (!$result) {
			$this->out('Aborting install due to errors');
			exit;
		}

		$this->out('Installing PHP_CodeSniffer');
		$result = $this->_execCommand("pear install PHP_CodeSniffer");
		if (!$result) {
			$this->out('Aborting install due to errors');
			exit;
		}

		$this->out('Installing CakePHP coding standard for PHP_CodeSniffer');
		$result = $this->_execCommand("pear channel-discover pear.cakephp.org", false);
		$result = $this->_execCommand("pear channel-update pear.php.net", false);
		$result &= $this->_execCommand("pear install cakephp/CakePHP_CodeSniffer", false);
		if (!$result) {
			$this->out('Aborting install due to errors');
			exit;
		}

		$this->out('Installing Ant');
		$result = $this->_execCommand("yum install ant -y");
		if (!$result) {
			$this->out('Aborting install due to errors');
			exit;
		}

		$this->out('Installing Rhino');
		$pluginPath = Configure::read('CodingStandards.PLUGIN_PATH');
		$result = $this->_execCommand("git clone https://github.com/mozilla/rhino.git $pluginPath/tmp/rhino", false);
		$result &= $this->_execCommand("ant -buildfile $pluginPath/tmp/rhino/build.xml", false);
		$result &= $this->_execCommand("chmod +x $pluginPath/Vendor/jshint/jscheck.sh", false);
		$result &= $this->_execCommand("phpcs --config-set rhino_path /usr/bin/rhino");
		$folder = new Folder("$pluginPath/tmp/rhino");
		$folder->delete();
		if (!$result) {
			$this->out('Aborting install due to errors');
			exit;
		}

		$this->out('Installation complete');
	}

	protected function _execCommand($command, $sudo = true) {
		@exec("id -u", $uid);
		$rootUser = (current($uid) == 0);

		if ($sudo && !$rootUser) {
			$command = "sudo $command";
		}

		@exec($command, $output, $resultVar);

		$shellCommand = strtok($command, " ");
		$success = $this->__getCommandSuccess($shellCommand, $resultVar);

		if (!$success) {
			$this->out("Failed to run $command");
			$this->out($output);
		}

		return $success;
	}

	private function __getCommandSuccess($shellCommand, $resultVar) {
		switch ($shellCommand) {
			case 'pear':
				return $resultVar == 0 || $resultVar == 1;
				break;
			default:
				return $resultVar == 0;
				break;
		}
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

/**
 * Override welcome method to remove a header for the shell
 *
 * @return void
 */
	protected function _welcome() {
	}
}

<?php
App::uses('Folder', 'Utility');

class CodingStandardsCheckShell extends AppShell {

	public $tasks = array(
		'CodingStandards.StyleCheck',
		'CodingStandards.PHPCheck',
		'CodingStandards.JSCheck',
		'CodingStandards.CSSCheck'
	);

	public function main() {
		$this->out(__d('cake_console', 'Coding Standards Check Shell'));
		$this->hr();
		$this->out(__d('cake_console', '[M]odels'));
		$this->out(__d('cake_console', '[V]iews'));
		$this->out(__d('cake_console', '[C]ontrollers'));
		$this->out(__d('cake_console', '[J]avaScript'));
		$this->out(__d('cake_console', '[S]tylesheets'));
		$this->out(__d('cake_console', '[F]ull report in HTML format'));
		$this->out(__d('cake_console', '[Q]uit'));

		$classToValidate = strtoupper($this->in(__d('cake_console', 'What would you like to validate?'), array('M', 'V', 'C', 'J', 'S', 'F', 'Q')));

		switch ($classToValidate) {
			case 'M':
				$this->PHPCheck->execute('model');
				break;
			case 'V':
				$this->PHPCheck->execute('view');
				break;
			case 'C':
				$this->PHPCheck->execute('controller');
				break;
			case 'J':
				$this->JSCheck->execute();
				break;
			case 'S':
				$this->CSSCheck->execute();
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

/**
 * Override welcome method to remove a header for the shell
 *
 * @return void
 */
	protected function _welcome() {
	}
}
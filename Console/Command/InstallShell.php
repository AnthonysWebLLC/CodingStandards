<?php
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

class InstallShell extends AppShell {

    public $tasks = array(
        'Template'
    );

    public function main() {
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

    public function installGitPreCommitHook() {
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

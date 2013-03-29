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
        $result = $this->_execCommand("pear channel-discover pear.phpunit.de", false);
        $result = $this->_execCommand("pear channel-update pear.phpunit.de", false);
        $result = $this->_execCommand("pear install PHP_CodeSniffer", false);
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

        $selection = $this->in('Do you want to install git pre-commit hook?', array('y', 'n'), 'y');
        if ('y' === $selection) {
            $this->installGitPreCommitHook();
        }
    }

    public function installGitPreCommitHook() {
        $pluginPath = Configure::read('CodingStandards.PLUGIN_PATH');
        $repoPath = ROOT . DS . '.git';
        $hooksPath = $repoPath . DS . 'hooks' . DS;

        // Check if a directory is a valid Git repository
        $file = new File($repoPath . DS . 'HEAD');
        if (!$file->exists()) {
            $this->out("Not a git repository: $repoPath");
            exit;
        }

        $this->out("git repository found at: $repoPath");
        // END Check if a directory is a valid Git repository

        // Create symbolic link to pre-commit-submodule hook
        $this->out("Linking pre-commit-submodule to $repoPath:");
        $sourcePath = $pluginPath . DS . 'Vendor' . DS . 'pre-commit-submodule';
        $destinationPath = $hooksPath . DS . 'pre-commit-submodule';
        $result = $this->_execCommand("ln -s -f $sourcePath $destinationPath");
         if (!$result) {
            $this->out("ln -s -f $sourcePath $destinationPath failed!");
            exit;
        }
        // END Create symbolic link to pre-commit-submodule hook

        // Make pre-commit-submodule hook executable
        $this->out("Checking if hooks are executable:");
        $result = $this->_execCommand("chmod +x $destinationPath");
         if (!$result) {
            $this->out("chmod +x $destinationPath");
            exit;
        }

        $this->out('git pre-commit hook installation complete');
        // END Make pre-commit-submodule hook executable
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

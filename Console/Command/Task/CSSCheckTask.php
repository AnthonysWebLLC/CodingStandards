<?php
App::uses('StyleCheckTask', 'CodingStandards.Console/Command/Task');

class CSSCheckTask extends StyleCheckTask {

    public function execute() {
        $this->path = Configure::read('CodingStandards.CSS_PATH');
        $this->_interactive();
    }

    protected function _interactive() {
        $this->hr();
        $this->out(__d('cake_console', "Validate file\nPath: %s", $this->path));
        $this->hr();

        $this->_files = $this->getAllFiles('.*\.css');

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

        foreach ($filesToValidate as $url) {
            $output .= $this->run($url);
        }

        $this->out($output);
    }

    public function run($path, $summary = false) {
        App::uses('HttpSocket', 'Network/Http');
        $HttpSocket = new HttpSocket();

        $validatorURL = "http://jigsaw.w3.org/css-validator/validator";
		try {
			$url = $this->toURL($path);
		} catch (Exception $e) {
			return $e->getMessage();
		}
        $response = $HttpSocket->get($validatorURL, "uri=$url&output=text");
        $bodyHTML = $response->body;

        if ($summary) {
            return strpos($bodyHTML, "Congratulations! No Error Found.");
        } else {
            $output = $this->cleanValidatorOutput($bodyHTML);
            return "CSS formatting errors:\r\n$output\r\n";
        }
    }

    private function cleanValidatorOutput($html) {
        $cssInformationStart = strpos($html, 'Valid CSS information');
        if ($cssInformationStart) {
            $html = substr($html, 0, $cssInformationStart);
        }

        $successInformationStart = strpos($html, 'This document validates as CSS level 3 !');
        if ($successInformationStart) {
            $html = substr($html, 0, $successInformationStart);
        }

        return trim($html);
    }

    public function toURL($filepath) {
		if(!Configure::read('CodingStandards.SERVER_NAME')){
			throw new Exception("CakePHP config variable CodingStandards.SERVER_NAME not set");
		}
        preg_match('/\/css\/.*$/i', $filepath, $fileURL);
        return 'http://' . Configure::read('CodingStandards.SERVER_NAME') . current($fileURL);
    }
}

<?php
App::uses('CodingStandardsAppController', 'CodingStandards.Controller');
class ReportsController extends CodingStandardsAppController {

	public $uses = array();

	public function index() {
		$reportfolderpath = Configure::read('CodingStandards.PLUGIN_PATH') . DS . 'tmp' . DS . 'reports' . DS . 'full';
		App::uses('Folder', 'Utility');
		$dir = new Folder($reportfolderpath);
		$files = $dir->find('.*\.ctp');
		rsort($files);
		$reports = array();
		foreach ($files as $file) {
			$reports[] = array(
				'type'		=> 'full',
				'datetime'	=> basename(basename($file), '.ctp')
			);
		}
		$this->set(compact('reports'));
	}

	public function view($type, $datetime) {
		$filepath = Configure::read('CodingStandards.PLUGIN_PATH') . DS . 'tmp' . DS . 'reports' . DS . $type . DS . $datetime . '.ctp';
		App::uses('File', 'Utility');
		$file = new File($filepath);
		$report = $file->read();
		$this->set(compact('type', 'datetime', 'report'));
	}
}

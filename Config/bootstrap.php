<?php
/**
 * Path to CodingStandards plugin folder
 */
Configure::write('CodingStandards.PLUGIN_PATH', APP . 'Plugin' . DS . 'CodingStandards');
/**
 * Path to JavaScript folder
 */
Configure::write('CodingStandards.JS_PATH', ROOT . DS . 'html' . DS . 'js');
/**
 * Path to CSS folder
 */
Configure::write('CodingStandards.CSS_PATH', ROOT . DS . 'html' . DS . 'css');
/**
 * Path patterns to ignore in checks
 */
Configure::write('CodingStandards.PATH_IGNORE_PATTERNS',
	array(
		'/.*' . '\\' . DS . 'html' . '\\' . DS . 'js' . '\\' . DS . '.*\\.min\\.js$/i'	// Minified JavaScript wouldn't match Coding Standards
	));

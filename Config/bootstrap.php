<?php
/*/
 * Coding Standards configuration variables
 *
 * Override varaible(s) by placing similar statement(s) after your ~/app/Config/bootstrap.php includes this file
 */

/**
 * Path to CodingStandards plugin folder
 */
Configure::write('CodingStandards.PLUGIN_PATH', APP . 'Plugin' . DS . 'CodingStandards');

/**
 * Path to JavaScript folder
 */
Configure::write('CodingStandards.JS_PATH', APP . WEBROOT_DIR . DS . 'js');

/**
 * Path to CSS folder
 */
Configure::write('CodingStandards.CSS_PATH', APP . WEBROOT_DIR . DS . 'css');

/**
 * Path patterns to also check in full reports & present option to check on console
 */
Configure::write('CodingStandards.ADDITIONAL_PATHS', array(
//	'CodingStandards' => Configure::read('CodingStandards.PLUGIN_PATH')
));

/**
 * Filepath patterns to ignore in checks
 * * Also ignores those added @ CodingStandards.ADDITIONAL_PATHS
 */
Configure::write('CodingStandards.PATH_IGNORE_PATTERNS', array(
	'/.*' . '\\' . DS . 'js' . '\\' . DS . '.*\\.min\\.js$/i',					// Minified JavaScript wouldn't match Coding Standards
	'/.*empty$/',																// Don't check git folder placeholders
	'/.*\\/vendor\\/.*/i',														// Don't check vendor folders
	'/.*\\/vendors\\/.*/i',														// Don't check vendors folders
	'/.*\\/tmp\\/.*/',															// Don't check temp files
	'/.*app\\' . DS . 'Config\\' . DS . 'Schema\\' . DS . 'i18n.php/',			// Don't check i18n.php as CakePHP core didn't make to CS
	'/.*css' . '\\' . DS . 'cake.generic.css/',									// Don't check CakePHP default CSS, it's a fail
	'/.*app\\' . DS . 'Plugin\\' . DS . 'DebugKit\\' . DS . '.*/'				// Don't check DebugKit plugin, it's an external plugin
));

/**
 * Telling JSHint about global variables that are defined elsewhere.
 */
Configure::write('CodingStandards.JSHINT_GLOBALS', "$");

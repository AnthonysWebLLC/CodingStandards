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
	'/.*\\/tmp\\/.*/'															// Don't check temp files
));

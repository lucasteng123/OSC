<?php

/**
 * TA Framework
 *
 * A very small PHP framework to power this REST API
 */

// Framework path
define ('FRAMEWORK_PATH', realpath(dirname(__FILE__)) );
// SITE_PATH constant is just a shortcut for the working directory
define('SITE_PATH',getcwd());
define('ROOT_PATH',getcwd());
// The WEB_PATH constant
/*
WEB_PATH offers a constant for
the root of this website as seen by the browser.
For instance, http://website.com if this is on website.com,
or http://website.com/sub if this is in a subdirectory.
*/
$pattern = '/^'.preg_quote($_SERVER['DOCUMENT_ROOT'],'/').'/';
$webpath = "http://".$_SERVER['HTTP_HOST'].preg_replace($pattern,'',getcwd());
define('WEB_PATH',$webpath);



function issetor(&$var, $default = false) {
	return isset($var) ? $var : $default;
}


/**
 * Stores a list of include paths, throwing an exception when paths are not found
 */
class IncludePathHandler {
	static function add_include_path ($path) {
		foreach (func_get_args() AS $path)
		{
			if (!file_exists($path) OR (file_exists($path) && filetype($path) !== 'dir'))
			{
				throw new Exception (
					"Include path '{$path}' does not exist"
				);
				continue;
			}
			
			// Get array from path variable
			$paths = explode(PATH_SEPARATOR, get_include_path());
			
			// Add path to array only if it doesn't exist
			if (array_search($path, $paths) === false)
				array_unshift($paths, $path);
			
			// Set new path variable from array
			set_include_path(implode(PATH_SEPARATOR, $paths));
		}
	}

	static function remove_include_path ($path) {
		// Support multiple arguments as multiple maths
		foreach (func_get_args() AS $path)
		{
			// Get array from path variable
			$paths = explode(PATH_SEPARATOR, get_include_path());
			
			// Remove path if it exists
			if (($k = array_search($path, $paths)) !== false)
				unset($paths[$k]);
			else
				continue;

			// Combine array back into string (or set to empty string if none)
			if (!count($paths))
			{
				set_include_path("");
			} else {
				set_include_path(implode(PATH_SEPARATOR, $paths));
			}
		}
	}
}

// AUTOLOAD
function framework_autoload($className) {
	$classPathAndName = $className;
	// Get only name of class if there's a namespace
	$className = explode("\\", $className);
	$className = $className[count($className)-1];
	// Generate possible paths for file
	$possibleLocations = array();
	$possibleLocations[] = $className . '.class.php';
	$possibleLocations[] = $className.".class/main.php";
	$possibleLocations[] = $className."/".$className.".php";
	$possibleLocations[] = $className.".php"; // (as a last resort)
	// Attempt to include each file
	foreach ($possibleLocations as $location) {
		@include($location);
		if (class_exists($className)) return;
	}
	// Throw error if class still isn't loaded
	if (!class_exists($className)) {
		throw new Exception (
			"Autoload failed; no file or folder with the given classname (".$className.") was readable"
		);
	}
}
spl_autoload_register(function ($className) {
	framework_autoload($className);
});


if (DEV_MODE) {
	$_FRAMEWORK['error_log'] = array();
	function exception_error_handler($errno, $errstr, $errfile, $errline ) {
		echo "de errno:".$errno.";errstr:".$errstr.";errfile:".$errfile.";errline:".$errline;
	}
	function framework_shutdown_function() {
		$var = error_get_last();
		if ($var) {
			echo "Oops; an error occured to such an extent that the operation had to stop, and a web page could not be sent to you.";
			echo "<br /><br />DEV MODE IS ON:<br /><pre>";
			print_r($var);
			echo "</pre>";
		}
	}
	set_error_handler("exception_error_handler");
	register_shutdown_function('framework_shutdown_function');
}




function framework_load_paths() { // throws FrameworkException
	IncludePathHandler::add_include_path(FRAMEWORK_PATH.DIRECTORY_SEPARATOR."framework");
}

framework_load_paths();

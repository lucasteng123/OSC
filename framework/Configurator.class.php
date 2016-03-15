<?php
// This renders the Registry class a bit useless...

class Configurator {
	private $properties;
	// Constructors . . .
	function __construct() {
		$this->properties = array();
	}
	static function make_from_arr($array) {
		$this->properties = $array;
	}


	function set_from_ini_file($file, $section = null) {
		if ( ($ini_contents = parse_ini_file($file, $section != null))  ===  false ) {
			throw new Exception("Configurator: Couldn't parse ini file '".$file."'");
		}
		$arrayToUse = ($section != null) ? $ini_contents[$section] : $ini_contents;
		foreach ($arrayToUse as $key => $value) {
			$this->properties[$key] = $value;
		}
	}
	function set_from_array($array) {
		foreach ($array as $key => $value) {
			$this->properties[$key] = $value;
		}
	}
	function get_property($index) {
		return issetor($this->properties[$index]);
	}
	function set_property($index, $value) {
		$this->properties[$index] = $value;
	}

	function has_properties($keys) {
		foreach ($keys as $key) {
			if (!array_key_exists($key, $this->properties)) {
				return false;
			}
		}
		return true;
	}

	public function __get($index) {
	    return $this->get_property($index);
	}
	public function __set($index, $value) {
	    $this->set_property($index, $value);
	}
}

/* Overly applicaiton-specific idea from Geelu's code
class Configurator_v0 {
	private $globalConfig;
	function __construct($file) {
		if (!($this->globalConfig = parse_ini_file($file,true))) {
			throw new Exception("Configurator: Couldn't load '"+$file+"'");
		}
	}
	function getConfig($scope) {
		$passConfig = array();
		$globalConfig = $this->globalConfig;
		foreach($globalConfig as $key => $val) if (!is_array($val)) {
			$passConfig[$key] = $val;
		}
		if (array_key_exists($scope, $globalConfig)) {
			foreach($globalConfig[$scope] as $key => $val) {
				$passConfig[$key] = $val;
			}
		} else {

		}
		return $passConfig;
	}
}
*/
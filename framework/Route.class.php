<?php
class Route {
	function __construct($pathArray) {
		$this->pathArray = $pathArray;
	}
	function get_path_array() {
		return $this->pathArray;
	}
}

<?php
class AnonymousClass {
	private $accessible = array();
	public $vars = array();
	function __construct($init_array = null) {
		if ($init_array == null) $init_array = array();
		foreach ($init_array as $name => $object) { // $object in this case being a method or property.
			$accessible[$name] = $object;
		}
		$required_methods = $this->get_required_methods();
		foreach ($required_methods as $method) {
			if (!is_callable($accessible[$method])) {
				throw new Exception("Missing method");
			}
		}
		$this->accessible = $accessible;
	}
	protected function get_required_methods() {
		return array();
	}
	/*
	function __get($name) {
		return $this->accessible[$name];
	}
	*/
	function __call($name, $args) {
		array_unshift($args, $this);
		return call_user_func_array($this->accessible[$name], $args);
	}
}

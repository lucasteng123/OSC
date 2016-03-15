<?php
class Controller extends AnonymousClass {
	public $tools = array();
	public $route = array();
	protected function get_required_methods() {
		return array(
			'run',
		);
	}
	public function add_tool($key,$tool) {
		$this->tools[$key] = $tool;
	}
	public function set_route($route) {
		$this->route = $route;
	}
}

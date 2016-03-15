<?php

/**
 * Controller for 404 error response
 * @todo add HTTP 404 header
 */

$methods = array();

$methods['run'] = function($instance) {
	header('Content-Type: application/json');
	
	$r = $instance->route;
	$response = array();
	$response['status'] = "notfound";
	$response['code'] = "404";
	$response['message'] = "The request URI did not match a controller";

	ob_get_clean();
	echo json_encode($response);
};

$page_controller = new Controller($methods);
unset($methods);

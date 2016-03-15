<?php

/**
 * Controller for 404 error response
 * @todo add HTTP 404 header
 */

$methods = array();

$methods['run'] = function($instance) {
	// Set headers
	header('Content-Type: application/json');
	
	// Get URL variables
	$r = $instance->route;
	$characterID = $r[0];
	$stationID   = $r[1];

	// Generate testing data
	$data2 = array(
		'id' => 12658,
		'pri_color' => 120,
		'sec_color' => 110,
		'stage1' => "ffffff",
		'stage2' => "ffffff",
		'stage3' => "ffffff",
		'stage4' => "ffffff",
		'stage5' => "ffffff"
	);
	$option = 2; 

	// Send data
	ob_get_clean();
	echo json_encode( $data2 );
};

$page_controller = new Controller($methods);
unset($methods);

<?php

/**
 * Controller for 404 error response
 * @todo add HTTP 404 header
 */

$methods = array();

$methods['run'] = function($instance) {
	// Set headers
	header('Content-Type: application/json');

	// Get tools
	$pdo = $instance->tools['con_manager']->get_connection();
	
	// Get URL variables
	$r = $instance->route;
	$characterID = $r[0]; // TODO: check valid int
	$stationID   = $r[1]; // TODO: check valid int


	// === Modify character for visit === ///

	$notSureWhat = null; // what's the feature id?

	$sql = "INSERT INTO visits (character_ID, feature_ID, date_posted)
	VALUES (:charid, :featid, now())";
	$stmt = $pdo->prepare($sql);
	// Bind variables
	$stmt->bindValue("charid", $characterID, PDO::PARAM_INT );
	$stmt->bindValue("featid", $notSureWhat,  PDO::PARAM_INT );
	// Insert the row
	$stmt->execute();
	// Get the id of what we just inserted
	$idInserted = $pdo->lastInsertId();


	// === Generate current character === //

	$sql = "SELECT c.*, v.*, f.* FROM characters c
	LEFT JOIN visits v ON v.character_ID=c.id
	LEFT JOIN features f ON v.feature_ID=f.id
	WHERE c.id=:charid
	";
	// Prepare statement
	$stmt = $pdo->prepare($sql);
	// Bind values
	$stmt->bindValue("charid",  $characterID,  PDO::PARAM_INT );
	// Do the thing
	$stmt->execute();
	// Fetch results into associative array
	$result = array();
	while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
		$result[] = $row;
	}

	// Print results to a temporary file for debugging
	ob_start();
		print_r($result);
	file_put_contents("output.txt", ob_get_clean());

	// ... you'll need to do something with the results to make the array
	// that gets sent

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

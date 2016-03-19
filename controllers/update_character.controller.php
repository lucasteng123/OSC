<?php

/**
 * Controller for 404 error response
 * @todo add HTTP 404 header
 */

$methods = array();

$methods['error'] = function($instance){
	echo '{"error": "Something went wrong"}';
};

$methods['run'] = function($instance) {
	// Set headers
	header('Content-Type: application/json');

	// Get tools
	$pdo = $instance->tools['con_manager']->get_connection();
	
	// Get URL variables
	$r = $instance->route;
	$characterID = $r[0]; // TODO: check valid string
	$stationID   = $r[1]; // TODO: check valid string

	// === Generate current character === //

	// detect if there is a character already with this ID
	$sql = "SELECT c.HEXid FROM characters c
	WHERE c.HEXid=:charid
	";
	// Prepare statement
	$stmt = $pdo->prepare($sql);
	// Bind values
	$stmt->bindValue("charid",  $characterID,  PDO::PARAM_STR );
	$stmt->execute();
	// Fetch results into associative array
	$result = array();
	while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
		$result[] = $row;
	}
	//if there is no character with this ID, create it
	if(count($result) < 1){
		$sql = "INSERT INTO characters (HEXid, pri_color, sec_color, date_created)
		VALUES (:HXid, :pricol, :seccol, now())";
		$stmt = $pdo->prepare($sql);
		// Bind variables
		$stmt->bindValue("HXid", $characterID, PDO::PARAM_STR );
		$stmt->bindValue("pricol", 0,  PDO::PARAM_INT );
		$stmt->bindValue("seccol", 0,  PDO::PARAM_INT );
		// Insert the row
		$stmt->execute();
		// Get the id of what we just inserted
		$idInserted = $pdo->lastInsertId();
	}


	// ====== get amt of states ====== //
	$sql = "SELECT v.id FROM visits v
	WHERE v.character_ID=:charid
	";
	// Prepare statement
	$stmt = $pdo->prepare($sql);
	// Bind values
	$stmt->bindValue("charid",  $characterID,  PDO::PARAM_STR );
	$stmt->execute();
	// Fetch results into associative array
	$result = array();
	while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
		$result[] = $row;
	}
	$current_state = count($result)+1;



	// === Modify character for visit === ///
	if($current_state <= 5){
		$sql = "SELECT f.HEXid FROM features f
		WHERE f.station_id=:stnID ORDER BY rand()
		limit 1
		";
		// Prepare statement
		$stmt = $pdo->prepare($sql);
		// Bind values
		$stmt->bindValue("stnID",  $stationID,  PDO::PARAM_STR );
		$stmt->execute();
		// Fetch results into associative array
		$feature_ID = null;
		if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$feature_ID = $row["HEXid"];
		} else {
			$methods["error"];
		}

		$sql = "INSERT INTO visits (character_ID, feature_ID, current_state, date_posted)
		VALUES (:charid, :featid, :state, now())";
		$stmt = $pdo->prepare($sql);
		// Bind variables
		$stmt->bindValue("charid", $characterID, PDO::PARAM_STR);
		$stmt->bindValue("featid", $feature_ID,  PDO::PARAM_STR);
		$stmt->bindValue("state", $current_state,  PDO::PARAM_STR);
		// Insert the row
		$stmt->execute();
		// Get the id of what we just inserted
		$idInserted = $pdo->lastInsertId();
	}


	//get result from db
	$sql = "SELECT c.HEXid, c.pri_color, c.sec_color, v.current_state, f.sprite_filename FROM characters c
	LEFT JOIN visits v ON v.character_ID=c.HEXid
	LEFT JOIN features f ON v.feature_ID=f.HEXid
	WHERE c.HEXid=:charid
	";
	// Prepare statement
	$stmt = $pdo->prepare($sql);
	// Bind values
	$stmt->bindValue("charid",  $characterID,  PDO::PARAM_STR );
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
	ob_get_clean();

	// make JSON object of result, and print that
	echo json_encode( $result );
};

$page_controller = new Controller($methods);
unset($methods);

<?php
$methods = array();
$methods['error'] = function($instance){
	echo '{"error": "Something went wrong"}';
};

$methods['run'] = function($instance) {
	$states = [1 => "/img/eggs/", 2 => "/img/bodies/", 3 => "/img/accessories/", 4 => "/img/features/", 5 => "/img/accessories/" ];
	// Set headers
	header('Content-Type: text/html; charset=utf-8');

	// Get tools
	$pdo = $instance->tools['con_manager']->get_connection();
	
	// Get URL variables
	$r = $instance->route;
	$characterID = $r[0];

	//get result from db
	$sql = "SELECT v.current_state, f.sprite_filename FROM characters c
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
		$result[$row["current_state"]] = $row["sprite_filename"];
	}
	foreach ($result as $state => $sprite) {
		echo "<img href=\"";
		echo $states[$state],$sprite;
		echo "\"/>";

	}
	

	// Print results to a temporary file for debugging
	ob_start();
		print_r($result);
	file_put_contents("output.txt", ob_get_clean());
	ob_get_clean();

};

$page_controller = new Controller($methods);
unset($methods);

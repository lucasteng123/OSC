<?php
$methods = array();
$methods['error'] = function($instance){
	echo '{"error": "Something went wrong"}';
};

$methods['run'] = function($instance) {
	$states = [1 => "img/eggs/", 2 => "img/bodies/", 3 => "img/accessories/", 4 => "img/features/", 5 => "img/accessories/" ];
	// Set headers
	header('Content-Type: image/png');

	// Get tools
	$pdo = $instance->tools['con_manager']->get_connection();
	
	// Get URL variables
	$r = $instance->route;
	$characterID = $r[0];

	  //===========================\\
	 //|====== GET CHARACTER ======|\\
	//||===========================||\\
	$sql = "SELECT v.current_state, f.sprite_filename FROM characters c
	LEFT JOIN visits v ON v.character_ID=c.HEXid
	LEFT JOIN features f ON v.feature_ID=f.HEXid
	WHERE c.HEXid=:charid
	LIMIT 5
	";
	// Prepare statement
	$stmt = $pdo->prepare($sql);
	// Bind values
	$stmt->bindValue("charid",  $characterID,  PDO::PARAM_STR );
	// Do the thing
	$stmt->execute();
	// Fetch results into associative array
	$images = array();
	while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
		$result[$row["current_state"]] = $row;
	}

	  //===========================\\
	 //|====== CREATE  IMAGE ======|\\
	//||===========================||\\
	//create blank image to put it in
 	$final_image = imagecreatetruecolor(400, 400);
    imagesavealpha($final_image, true);

    $trans_colour = imagecolorallocatealpha($final_image, 0, 0, 0, 127);
    imagefill($final_image, 0, 0, $trans_colour);
    $images = array();
    for ($i=1; $i <= count($result); $i++) { 
    	$images[] = imagecreatefrompng($states[$i] . $result[$i]["sprite_filename"]);
    }
	imagealphablending($images[0], true);
	imagesavealpha($images[0], true);

	for ($i=1; $i < count($images); $i++) { 
		imagecopy($images[0], $images[$i], 0,0,0,0,50,50);
	}
	//imagecopy($image_1, $image_2, 0, 0, 0, 0, 50, 50);



	imagecopyresized($final_image, $images[0], 0, 0, 0, 0, 400, 400, 50, 50);

	imagepng($final_image);

	

	// Print results to a temporary file for debugging
	ob_start();
		print_r($result);
		print($states[2]);
		print($result[1]["sprite_filename"]);
	file_put_contents("output.txt", ob_get_clean());
	ob_get_clean();

};

$page_controller = new Controller($methods);
unset($methods);

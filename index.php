<?php
define('DEV_MODE', true);
require_once("./ta_framework.php");

function main() {
	IncludePathHandler::add_include_path(FRAMEWORK_PATH.DIRECTORY_SEPARATOR."lib");

	// Setup some tools for this website
	$dbConfig = new Configurator();
	$dbConfig->set_from_ini_file(SITE_PATH."/config/database_mysql.ini");
	$tool_database = new DBConnectionManager($dbConfig);

	// Instanciate a router object
	$router = new Router();
	// Set route from request uri
	$router->set_route_from_request(array_key_exists('ri', $_GET) ? $_GET['ri'] : '/');

	// Get correct controller from route
	$controller = $router->get_controller(SITE_PATH . "/controllers");

	if ($controller === false) {
		// 404 Page
		$router->set_route_from_request("404");
		$controller = $router->get_controller(SITE_PATH . "/errors");
		$controller->run();
	} else {
		$controller->add_tool('con_manager', new DBConnectionManager($dbConfig));
		$controller->run();
	}
}

try {
	main();
} catch (Exception $e) {
	echo "Fatal error occured: ".$e->getMessage();
	echo '<hr />';
	VarTools::predump($e);
}
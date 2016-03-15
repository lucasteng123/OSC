<?php
class Router {
	private $route;
	function set_route_from_request($requestStr, $setNullToIndex = true) {
		$requestStr = urldecode($requestStr);
		$explodeStr = "/(\/+|\\\\+)/"; // w/o PHP escapes: (/+|\\+)
		$pathArray = preg_split($explodeStr, $requestStr);
		if ($pathArray[0] === '' && $setNullToIndex) $pathArray = array("index");
		$this->route = new Route($pathArray);
	}
	function get_controller($controllerPaths) {
		if (! is_array($controllerPaths)) {
			$temp = $controllerPaths;
			$controllerPaths = array();
			$controllerPaths[] = $temp;
			unset($temp);
		}
		$route = $this->route;
		// Set initial values
		$pathArray = $route->get_path_array();

		$controllerRoute = array(); // Path given to controller after loading

		while (count($pathArray) > 0) {
			$controllerName = array_pop($pathArray);

			// Generate list of possible locations
			$possibleLocations = array();
			foreach ($controllerPaths as $path) {
				// Append subfolders to controller path
				foreach ($pathArray as $item) {
					$path .= "/".$item;
				}
				// If .controller directory exists, look for controller in there...
				if (file_exists($path."/".$controllerName) && filetype($path."/".$controllerName) == "dir") {
						$possibleLocations[] = $path."/".$controllerName.".controller/main.php";
						$possibleLocations[] = $path."/".$controllerName.".controller/".$controllerName.".php";
				} else { // If instead a .controller file exists, that will be loaded
					$possibleLocations[] = $path."/".$controllerName . '.controller.php';
				}
			}

			// Look though possible locations. Attempt to load a controller at each.
			foreach ($possibleLocations as $location) {
				if (is_readable($location)) {
					include($location); // sets $page_controller
					if ($page_controller instanceof Controller) {
						$page_controller->set_route($controllerRoute);
						return $page_controller;
					}
				}
			}

			// Prepend the path component tested to the controllerRoute.
			array_unshift($controllerRoute, $controllerName);
		}
		// If the function didn't return in the foreach, no controller exists for this route.
		return false;
	}
}

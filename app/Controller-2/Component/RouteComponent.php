<?php

class RouteComponent extends Component {

	public $route_file = '../Config/routes.php';

	public function initialize() {
		if (!is_file($this->route_file)) {
			die('The path to your route file is wrong. Edit /app/controllers/components/route.php and fix the problem.');
		}
	}

	public function add($route) {
		$route = $route . "\n";
		if (is_writable($this->route_file)) {
			$routes = file($this->route_file);
			$new_routes = '';
			foreach ($routes as $i) {
				if (trim($i) != 'require CAKE."Config".DS."routes.php";?>') {
					$new_routes .= $i;
				} else
					break;
			}
			$handle = fopen($this->route_file, 'w');
			if (fwrite($handle, $new_routes . $route . 'require CAKE."Config".DS."routes.php";?>')) {
				return true;
			} else
				return false;
			fclose($handle);
		} else
			return false;
	}

	public function remove($route) {
		$route = $route . "\n";
		if (is_writable($this->route_file)) {
			$routes = file($this->route_file);
			$new_routes = '';
			foreach ($routes as $i) {
				if (trim($i) != 'require CAKE."Config".DS."routes.php";?>') {
					if ($i != $route) {
						$new_routes .= $i;
					}
				} else
					break;
			}
			$handle = fopen($this->route_file, 'w');
			if (fwrite($handle, $new_routes . 'require CAKE."Config".DS."routes.php";?>')) {
				return true;
			} else
				return false;
			fclose($handle);
		} else
			return false;
	}

}

<?php // These aren't the functions you're looking for.

class DBConnectionManager {
	private $error;
	private $lastException;
	private $con;
	private $config;

	function __construct($config) {
		if (!$config instanceof Configurator) {
			throw new FrameworkException (
				"DBConnectionManager received an invalid configurator.",
				FrameworkException::CONFIG_INVALID
				);
		}
		$this->config = $config;
	}
	function getError() {
		return $this->lastException;
	}
	function get_connection() {
		if ($this->con instanceof PDO) return $this->con;

		$method = $this->config->get_property('method');

		try {
			switch ($method) {
				case 'mysql':
					$this->con = $this->connect_mysql();
					break;
				case 'sqlite':
					$this->con = $this->connect_sqlite();
					break;
				default:
					$this->con = $this->connect_mysql();
					break;
			}
			return $this->con;
		} catch (PDOException $e) {
			$this->lastException = $e; //catch and show the error
			$this->error = $e->getCode();
			throw $e;
		}

	}

	function getConnection() {
		call_user_func_array($this->get_connection, func_get_args());
	}

	function connect_mysql() {

		if (!$this->config->has_properties(array('user','pass','host','schema'))) {
			throw new FrameworkException (
				"DBConnectionManager$config missing required parameters for MySQL connection.",
				FrameworkException::CONFIG_MISSING_KEY
				);
		}

		$config = $this->config;
		$dbDsn = "mysql:host=".$config->get_property('host').";dbname=".$config->get_property('schema');
		$con = new PDO( $dbDsn, $config->get_property('user'), $config->get_property('pass') );
		$con->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		return $con;
	}
	function connect_sqlite() {

		if (!$this->config->has_properties(array('file'))) {
			throw new FrameworkException (
				"DBConnectionManager$config missing required parameters for SQLite connection.",
				FrameworkException::CONFIG_MISSING_KEY
				);
		}

		$config = $this->config;
		$dbDsn = "sqlite:".$config->get_property('file');
		$con = new PDO( $dbDsn );
		$con->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		return $con;
	}
}

?>

<?php
/**
 * @brief Database Handler Class
 **/

class DB extends PDO {
	static private $instance = false;
	static public function instance() {
		if (! self::$instance ) {
			try {
				self::$instance = new PDO( 'mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
				self::$instance->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

			} catch(PDOException $e) {
				die ('Sorry, Errors occured and logged.');
			}
		}
		return self::$instance;
	}
}

?>
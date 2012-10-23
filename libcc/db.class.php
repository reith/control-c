<?php
/**
 * @brief some tools for working with PDO
 **/

class DB {
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

	static public function bindColumns( PDOStatement &$stmt, array $nametype, array &$bound, $assoc = true ) {
		/*
		 * type array of name, values: "title" -> i (PDO::PARAM_INT)
		 */
		foreach( $nametype as $name => $type ) {
			switch( $type ) {
				case 'i': $pdo_t = PDO::PARAM_INT; break;
				case 's': $pdo_t = PDO::PARAM_STR; break;
				case 'b': $pdo_t = PDO::PARAM_BOOL; break;
				default: throw new Exception('Bad type '.$type );
			}
			if ( $assoc )
				$stmt->bindColumn( $name, $bound[$name], $pdo_t );
			else
				$stmt->bindColumn( $name, $bound[], $pdo_t );
		}
	}

	/**
	 * fetchAllBound PDO fetchAll for bounded values
	 * 
	 * @param PDOStatement $stmt 
	 * @param array $bound reference to bounded record
	 * @param mixed $records possible reference to reurn value
	 * @static
	 * @access public
	 * @return array of bounded values
	 */
	static public function fetchAllBound( PDOStatement &$stmt, &$bound, &$records = null ) {
		if( is_null($records) ) {
			$recourds = array();
		}

		while($stmt->fetch(PDO::FETCH_BOUND)) {
			$records[] = unserialize( serialize( $bound ) );
		}
		
		return $records;
	}

	static public function foundRows() {
		/*
		 * Returns number of found records.
		 * NOTE: care SQL_CALC_FOUND_ROWS usage in query.
		 */

		 return intval(self::$instance->query('SELECT FOUND_ROWS()')->fetchColumn(0));
	}
}

?>

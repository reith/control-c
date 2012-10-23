<?php

class Query {
	function __construct() {
		$this->select = array();
		$this->from = array();
		$this->where = array();
		$this->query = "";
		$this->calc_found_rows = false;
	}

	function addSelect( $fields ) {
		$this->select[] = $fields;
	}

	function addFrom( $table, $alias = NULL ) {
		if( array_search($table, $this->from ) === false ) {
			if( is_null( $alias ) )
				$this->from[] = $table;
			else
				$this->from[ $alias ] = $table;
		}
		return false;
	}

	function addWhere( $query ) {
		$this->where[] = ' '.$query.' ';
	}

	function calcFoundRows( $answer = true ) {
		switch( $answer ) {
			case true: $this->calc_found_rows = true; break;
			case false: $this->calc_found_rows = false; break;
			default: Throw new Exception("Bad option `$answer` passed to calcFoundRows");
		}
	}

	function generate() {
		$from = "";
		$first_flag = true;
		foreach( $this->from as $a => $v ) {
			if(! $first_flag )
				$from .= ',';

			$from .= '`'.$v.'` ';
			if( intval($a) !== $a  ) {
				$from .= ' AS ';
				$from .= '`'.$a.'`';
			}
			$first_flag = false;
		}

		$qstr = '';
		if ($this->select ) {
			$qstr .= 'SELECT ';
			if( $this->calc_found_rows )
				$qstr .= ' SQL_CALC_FOUND_ROWS ';
			$qstr .= implode($this->select, ',');
		}
		if ($from) {
			$qstr .= " FROM $from ";
		}

		if ($this->where ) {
			$qstr .= ' WHERE '.implode($this->where, ' ');
		}

		return $qstr;
	}

	function __toString() {
		return $this->generate();
	}

}
?>

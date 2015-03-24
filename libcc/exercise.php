<?php
require_once 'libcc/db.class.php';
function get_exercise( $id ) {
	$q = <<<'EOQ'
SELECT
	`c`.name as `cname`, `e`.id, `c`.id AS `cid`, `e`.explain, `e`.number,
	`e`.`tcCount`, `e`.wage, `ps`.deadlineDate AS `ddate`,
	`ps`.checkDate AS `chdate`, `ps`.createDate as `cdate`,
	`e`.title, `ps`.number AS `snum`, `ps`.id AS `sid`,
	UNIX_TIMESTAMP()>`ps`.`deadlineDate` AS expired,
	UNIX_TIMESTAMP()>`ps`.`checkDate` AS `graded`
FROM
	`problemset` AS `ps`, `exercise` AS `e`, `course` AS `c`
WHERE
	`e`.`id` = ? AND `ps`.`id`=`e`.set AND `c`.id=`ps`.course
EOQ;
	$con = DB::instance();
	$stmt = $con->prepare( $q );
	$stmt->bindParam( 1, $id, PDO::PARAM_INT );
	return $stmt -> execute() ? $stmt : false;
}

/**
 * get_exercise_stats 
 * 
 * @param mixed $id exercise id
 * @param mixed $grade currently not used
 * @param mixed $testcase currently not used
 * @access public
 * @return stats array
 */
function get_exercise_stats( $id, $grade = true, $testcase = true ) {
	$table = "exercise_result_$id";
	$query = "SELECT `grade`, COUNT(`grade`) AS `count` FROM `$table` GROUP BY `grade`";

	$con = DB::instance();
	$stmt = $con->query($query);
	$record = array();
	DB::bindColumns( $stmt, array('grade'=>'i', 'count' =>'i'), $record, false );
	$result = DB::fetchAllBound( $stmt, $record );
	return $result;
}

?>

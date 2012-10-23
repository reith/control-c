<?php
require_once 'libcc/db.class.php';

/**
 * get_course 
 * 
 * @param integer $id course id
 * @access public
 * @return assoiciative array of course data
 *
 * @todo some other data fetched but not returned. remove them.
 */

function get_course( $id ) {
	$con = DB::instance();
	$query = <<<'EOQ'
SELECT
	`c`.*, CONCAT(`t`.firstName, ' ', `t`.lastName) AS `teacherName`,
	`t`.id AS `teacher`, COUNT(`student`) AS `sc`, AVG(`gradeAverage`) AS `sag`,
	MAX(`gradeAverage`) AS `smg`
FROM
	`Course` as `c`, `User` as `t`, `membership` AS `m`
WHERE
	`c`.id = ? AND `t`.id = `c`.teacher AND `m`.`course` = `c`.id AND `m`.`confirm` = 'j'
LIMIT 1;
EOQ;
	$stmt = $con->prepare( $query );
	$stmt->bindParam(1, $id, PDO::PARAM_INT );
	if(! $stmt->execute() )
		return false;

	$course = array();
	DB::bindColumns( $stmt, array(
		'id' => 'i',
		'name' => 's',
		'language' => 's',
		'teacher' => 'i',
		'teacherName' => 's',
		'createDate' => 'i',
		'closeDate' => 'i',
		'setsCount' => 'i',
		'checkedSetsWageSum' => 'i',
		'checkedSetsCount' => 'i',
		'closed' => 'b',
		'locked' => 'b',
		'sag' => 'i',
		'smg' => 'i',
		'sc' => 'i'
	), $course );

	$stmt->fetch(PDO::FETCH_BOUND);
	return $course;
}


function get_courses_names() {
	$con = DB::instance();
	$query = 'SELECT `id`, `name` FROM `Course`';
	return $con->query( $query );
}

function get_top_students( $course_id ) {
	$con = DB::instance();
	$query = <<<'EOQ'
SELECT
	`m`.gradeAverage, `s`.id, `s`.username
FROM
	`User` AS `s`, `membership` AS `m`
WHERE
	`m`.course = ? AND `m`.`student` = `s`.id
ORDER BY `m`.gradeAverage DESC
LIMIT 0, 3;
EOQ;
	$stmt = $con->prepare( $query );
	$stmt->bindParam(1, $course_id, PDO::PARAM_INT );
	$stmt->execute();
	return $stmt->fetch();

}

function get_students_grades( $course_id ) {
	$con = DB::instance();
	$query = <<<'EOQ'
SELECT
	`m`.gradeAverage
FROM
	`User` AS `s`, `membership` AS `m`
WHERE
	`m`.course = ? AND `m`.`student` = `s`.id AND `m`.`confirm` = 'j';
EOQ;
	$stmt = $con->prepare( $query );
	$stmt->bindParam(1, $course_id, PDO::PARAM_INT );
	$stmt->execute();
	return $stmt->execute() ? $stmt : false;
}
?>

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
	`course` as `c`, `user` as `t`, `membership` AS `m`
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

function get_top_students( $course_id ) {
	$con = DB::instance();
	$query = <<<'EOQ'
SELECT
	`m`.gradeAverage, `s`.id, `s`.username
FROM
	`user` AS `s`, `membership` AS `m`
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
	`user` AS `s`, `membership` AS `m`
WHERE
	`m`.course = ? AND `m`.`student` = `s`.id AND `m`.`confirm` = 'j';
EOQ;
	$stmt = $con->prepare( $query );
	$stmt->bindParam(1, $course_id, PDO::PARAM_INT );
	$stmt->execute();
	return $stmt->execute() ? $stmt : false;
}

class Course {
	private $id;
	private $name;
	private $teacher_id;
	private $language;
	private $teacher_name;

	/*
	 * [> object to table mapping <]
   * static mapping = new array (
	 *   'id' => 'id',
	 *   'name' => 'name',
	 *   'language' => 'language',
	 *   'teacher_id' => 'teacher'
	 * );
	 */

	public function __construct(int $id, string $name, string $language, int $teacher_id) {
		$this->id = id;
		$this->name = name;
		$this->language = language;
		$this->teacher_id = teacher_id;
	}

	public function getId() {
		return id;
	}

	public function getName() {
		return name;
	}

	public function getTeacherId() {
		return teacher_id;
	}

	public function getTeacherName() {
		return teacher_name;
	}

	static public function queryAllSummaryAsRows() {
		return DB::instance()->query("SELECT `c`.* from `" . DB_COURSE_TABLE . "` as `c` ")->fetchAll(PDO::FETCH_ASSOC);
	}

	static public function queryAll() {
		$result = Array();
		$query = "SELECT `c`.* from  `". DB_COURSE_TABLE . "` as `c`";
		$stmt = DB::instance()->query($query);
		forEach ($stmt->fetchAll() as $row) {
			$result[] = self::fromDBRow($row);
		}
		return $result;
	}

	static public function fromDBRow(array $row) {
		return new Course((int)$row['id'], $row['name'], $row['language'], (int)$row['teacher']);
	}
}
?>

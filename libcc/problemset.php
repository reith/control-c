<?php
$errors="";
require_once 'libcc/db.class.php';


/* SHOULD BE FUCKED UP */
function getProblemSet( $number ) {
	$con = DB::instance();
	$query = <<<'EOQ'
SELECT
	`c`.`name`, `c`.`id` AS `courseId`, `es`.`createDate`, `es`.`deadlineDate`,
	`es`.`correctionDate`, `es`.`seri` AS `seri`, `es`.`id` AS `seriId`,
	`t`.`id` AS `teacherId` , `es`.`comment`, `es`.`wage`,
	NOW()>`es`.`deadlineDate` AS `expired`, `es`.`exerciseCount`,
		NOW()>`es`.`correctionDate` AS `checked`,
	CONCAT(`t`.`firstName`, ' ', `t`.`lastName`) AS `teacherName`
FROM
	`exercise_seri` AS `es`, `course` AS `c`, `User` AS `t`
WHERE
	`es`.`id` = ? AND `c`.`id`=`es`.`course` AND `t`.`id`=`c`.`teacher`
EOQ;

	$stmt = $con->prepare( $query );
	$stmt->bindParam( 1, $number, PDO::PARAM_INT );
	if(! $stmt->execute() ) {
		throw Exception("ProblemSetDBFetch");
	} else {
		return $stmt->fetch( PDO::FETCH_OBJ );
	}
	return false;
}

/**
 * get_problemset 
 * 
 * @param integer $id prblemset ID
 * @access public
 * @return array problemset data + courseName + courseID + courseSetsWageSum + expired
 */
function get_problemset( $id ) {
	$con = DB::instance();
	$query = <<<'EOQ'
SELECT
	`p`.*, `c`.name AS `courseName`, `c`.id AS `courseId`,
	`c`.`setsWageSum` AS `courseSetsWageSum`, NOW() > `p`.deadlineDate AS `expired`,
	NOW() > `p`.checkDate AS `graded`
FROM
	`Problemset` AS `p`, `Course` AS `c`
WHERE
		`p`.`id` = ? AND `p`.course = `c`.id 	
LIMIT 1
EOQ;
	$stmt = $con->prepare($query);
	$stmt->bindParam(1,$id,PDO::PARAM_INT);
	if(! $stmt->execute() )
		return false;
	$problemset = array();
	DB::bindColumns($stmt, array(
		'id' => 'i',
		'course' => 'i',
		'expired' => 'b',
		'graded' => 'b',
		'number' => 'i',
		'exerciseCount' => 'i',
		'wage' => 'i',
		'locked' => 'b',
		'comment' => 's',
		'createDate' => 'i',
		'deadlineDate' => 'i',
		'checkDate' => 'i',
		'courseName' => 's',
		'courseId' => 'i',
		'courseSetsWageSum' => 'i'
	), $problemset );
	
	if (! $stmt->fetch(PDO::FETCH_BOUND) || ! isset($problemset['id']) )
		return false;

	// Get problemset's exercises *mini* details
	$problemset['exercise'] = array();
	$stmt = $con->prepare('SELECT `id`, `title`, `wage`, `number` FROM `Exercise` WHERE `set` = ?');
	$stmt->bindParam(1, $id, PDO::PARAM_INT);
	$stmt->execute();

	$exercise = array();
	DB::bindColumns($stmt, array(
		'id' => 'i',
		'title' => 's',
		'wage' => 'i',
		'number' => 'i'
	), $exercise );

	$problemset['exercise'] = DB::fetchAllBound($stmt, $exercise);
	return $problemset;
}

require_once 'libcc/query.class.php';


function get_problemset_exercises_stats( $id ) {
	return for_exercise_in_problemset( $id, get_exercise_stats );
};

function get_problemset_exercises( $id ) {
	require_once 'libcc/exercise.php';
	return for_exercise_in_problemset( $id, get_exercise );
}

/**
 * for_exercise_in_problemset Apply a @function for each exercise in problemset @id
 * 
 * @param mixed $id problemset
 * @param mixed $function function to be appilied
 * @access public
 * @return array of exercise id as key and reusult from function as value
 */
function for_exercise_in_problemset( $id, $function ) {
	$query = 'SELECT `id` FROM `Exercise` WHERE `set` = ?';
	$con = DB::instance();
	$stmt = $con->prepare($query);
	$stmt->bindParam(1, $id, PDO::PARAM_INT);
	if(! $stmt->execute() ) return false;
	$stmt->bindColumn( 1, $exerciseId, PDO::PARAM_INT );
	$result = array();
	while( $stmt->fetch(PDO::FETCH_BOUND) )
		$result[$exerciseId] = $function( $exerciseId );
	return $result;
}

// NOTE: this shoud be changed with student_upload change
function get_problemset_stats( $id ) {
	$query = 'SELECT `grade`, COUNT(`grade`) AS `count` FROM `student_upload` WHERE `seri` = ? GROUP BY `grade` ORDER BY `grade` ASC';
	$con = DB::instance();
	$stmt = $con->prepare($query);
	$stmt->bindParam(1, $id, PDO::PARAM_INT);
	if(! $stmt->execute() ) return false;
	$record = array();

	DB::bindColumns( $stmt, array(
		'grade' => 'i',
		'count' => 'i'
	), $record, false );

	$result = DB::fetchAllBound($stmt, $record);
	return $result;
}
/*
  echo "<fieldset><legend>تمرین سری $res->seri از درس <a href='".__url__."/course/{$res->courseId}'>{$res->name}</a></legend>";
  if (!empty($res->comment))
    echo "<label>توضیحات: </label> <div class='exerciseBody'> $res->comment </div>";

  echo "<label>ضریب سری: </label>".fa_number($res->wage)."<br />";
  echo "<label>تعداد تمرین‌ها:</label> ".fa_number($res->exerciseCount)." <br />";


		
  echo "<label>اضافه شده در تاریخ:</label> ".jl_date($res->createDate)."<br/>";
  echo "<label>مهلت ارسال پاسخ:</label> ".jl_date($res->deadlineDate)."<br/>";
  echo "<label>زمان تصحیح:</label> ".jl_date($res->correctionDate)."<br/>";
  echo "<label> استاد درس:</label> <a href='".__url__."/profile/{$res->teacherId}'>{$res->teacherName}</a><br/>";
  echo "</fieldset>";

  if (! $res->expired)
    echo "<a href='".__url__."/exercises'>فرستادن پاسخ</a><br/>";

  else if ($res->checked)
  {
	$stmt->closeCursor(); 
	($sstmt = $con->prepare( 'call get_exercise_seri_results(?)' )) || die('xx');
	$sstmt->bindParam(1, $seriPath, PDO::PARAM_INT) || die('yyy');
	$sstmt->execute() || die('zzz');
    $row = $sstmt->fetch(PDO::FETCH_NUM);
      echo <<<EOF
      <fieldset>
	<legend>نتایج تصحیح</legend>
	<label>تعداد فایل دریافت شده: </label>$row[0] <br />
	<label>بیشینه‌ی نمره‌ی کسب شده: </label>$row[1] <br />
	<label>میانگین نمرات کسب شده: </label>$row[2] <br />
EOF;
    $sstmt->closeCursor();
  }
 
  $stmt->closeCursor(); 
  $estmt = $con->prepare("call get_seri_s_exercises(?)");
  $estmt->bindParam(1, $seriPath, PDO::PARAM_INT);
  $estmt->execute();
  $exercises=$estmt->fetchAll(PDO::FETCH_OBJ);
	foreach($exercises as $exercise) 
		echo "تمرین ".fa_number($exercise->number)." : <a href='".__url__."/exercise/{$exercise->id}'>\"{$exercise->title}\"</a> با ضریب ".fa_number($exercise->wage)."<br />";
}
if (!empty($errors))
  echo "<div class='errorText'>متاسفانه خطاهای زیر رخ داد: <br/> $errors</div";
*/
?>

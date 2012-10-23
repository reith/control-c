<?php
//    REITH: general functions
//   a) these functions don't and should not check authenticate
//   b) arguments must be valid. here is no place for validating.
//   DANGER: CHANGE mysqli_error in execQuery to mysqli_errno before upload

require_once "error_codes.php";

$error="";
set_error_handler('errorHandler', E_ALL);


function errorHandler($errno, $errstr) {
  global $logsFile, $PHPERROR;

  if ($errno==E_NOTICE)
    return true;

  errorLogger ($PHPERROR[$errno], $errstr);
  return true;
}

function errorLogger($errtype, $errstr, $kill=false) {
  global $logsFile;

  $fh=fopen ($logsFile, 'a');
  $bt=debug_backtrace();
  $btc=count($bt)-1;
  
  if ($bt[1]['function'] == 'errorHandler') {	//triggered automatically
    $line=$bt[2]['line'];
    $file=$bt[2]['file'];
    $fbt=1;		                        //discard errorHandler function
  }
  else						//triggered manually
  {
    $line=$bt[0]['line'];
    $file=$bt[0]['file'];
    $fbt=0;		                        //discard errorLogger function
  }

  fputs ($fh , "\n========\n");
  $spc=0;
  while ($fbt < $btc) {
    fprintf ($fh, str_repeat (' ', $spc++)."%s (", $bt[$btc]['function']);
    if (isset($bt[$btc]['args']))
      foreach ($bt[$btc]['args'] as $arg) 
	fputs ($fh, print_r ($arg, true).", ");
    fputs ($fh, " )\n");
    $btc--;
  }
  
  fprintf ($fh, "DATE: %s\nTYPE: %s\nERRSTR: %s\nLINE %d in %s\n", date("Ymd"), $errtype, $errstr, $line, $file);

  if (isset($_SESSION['id']))
    fprintf ($fh, "GROUP: %s\nID: %d\n", $_SESSION['gp'], $_SESSION['id']);

  fclose($fh);
  if ($kill)
        if ($kill == 'json')
            dieJSON($errtype);
        else
            if ($ERRORS[$errtype])
                die ($ERRORS[$errtype]);
            else
                die ( _('An error occurred. it\'s logged.') );
}

function &newMySQLi ()
{
    global $error, $dbLocation, $dbUsername, $dbPassword, $dbName, $ERRNO;
    @$con = new mysqli($dbLocation, $dbUsername , $dbPassword, $dbName);
    if (mysqli_connect_errno())
      return false;

    $con->set_charset('utf8');
    return $con;
}

function execQuery ($queryStr, &$affectedRows=null)
{
//first argument is query string(s separated by `;')
//set second argument when you like find affected rows number
    global $errors, $dbLocation, $dbUsername, $dbPassword, $dbName;
    @$con = new mysqli($dbLocation, $dbUsername , $dbPassword, $dbName);
    if (mysqli_connect_errno())
    {
      $errors .= "<li>خطا در برقراری اتصال با پایگاه داده: </li><br>";
      $errors .= mysqli_connect_error();
      return false;
    }
    mysqli_set_charset ($con, 'utf8');
    $queryTok = strtok($queryStr, ";");
    do
    {
      $result = $con->query($queryTok);
      if (!$result) {
	  $errors.="<li>خطا در پردازش دستور MySql:".mysqli_error($con)."</li>";
	  break;
      }
    } while ($queryTok=strtok(";"));

    if (isset($affectedRows))
      $affectedRows = $con -> affected_rows;
    $con->close();
    return $result;
}

function mysqlres($function, $types)
{
  if (!$con=newMySQLi())
    return false;
  
  global $badRequest, $errors;
  $result=false;

  $i=0;
  while (($j=$i+2)<func_num_args())
  {
    switch ($types[$i])
    {
      case "d": $args[]=(int)func_get_arg($j); break;
      case "s": $args[]=$con->real_escape_string(func_get_arg($j)); break;
      default: $args[]=func_get_arg($j);
    }
  $i++;
  }

  $query=call_user_func_array($function, $args);

  if ($badRequest)
    $errors.="اطلاعات معتبر فرستاده نشد";
  else if (!$query || !empty($errors))
    $errors.="اجرای عملیات به دلیل مشکلات پیش آمده متوقف شد $function";
  else if (!$result=$con->query($query))
  {
    $errors.=$ERRORS['DB_QUERY'];
    errorLogger('MYSQLRES', "$con->error in $query");
  }

  $con->close();
  return $result;
}

function ownCoursesMenu()
{
  switch ($_SESSION['gp'])
  {
    case "t":
	  $query = 'call get_teacher_courses(?)';
//       $dbRes=mysqlres(getCoursesList, "", "own", "name", "ASC", 0, 20);
// 	if ($dbRes)
// 	  while ($row=$dbRes->fetch_row())
// 	    echo "<option value='$row[0]'>$row[1]</option>";
      break;
    case "s":
		$query = 'call get_student_courses(?)'; 
//       $dbRes=mysqlres(getCoursesList, "", "reg", "name", "ASC", 0, 20);
// 	if ($dbRes)
// 	  while ($row=$dbRes->fetch_row())
// 	    echo "<option value='$row[0]'>$row[1]</option>";
	break;
	default:
	    return;
	    break;
  }
  
  require_once 'libcc/db.class.php';
  $con = DB::instance();
  $stmt = $con->prepare($query);
  $stmt->bindParam(1, $_SESSION['id'], PDO::PARAM_INT);
  $stmt->execute();
  while ($row = $stmt->fetch( PDO::FETCH_NUM ) )
	    echo "<option value='$row[0]'>$row[1]</option>";
  $con = null;
  return true;
}

function getCoursesList ($view, $sort, $order, $from, $limit) {
  global $errors, $badRequest, $dbUserTable, $dbCourseTable, $dbMembershipTable;
  //FIXED

  $query="SELECT `c`.`id`, `c`.`name`, CONCAT(`u`.firstName, ' ', `u`.lastName), `c`.`year`, `c`.`lock`, `u`.id FROM `$dbCourseTable` as `c`, `$dbUserTable` as `u`";
  if ($_SESSION['gp']=="s")
    switch($view) {
    case "all": $query ="SELECT `c`.`id`, `c`.`name`, CONCAT(`u`.firstName, ' ', `u`.lastName), `c`.`year`, `c`.`lock`, ".
                  "`m`.confirm, `u`.id ".
                  "FROM `$dbCourseTable` as `c` LEFT JOIN $dbMembershipTable as `m` ON `m`.course=`c`.id AND `m`.student='{$_SESSION['id']}',".
		  " `$dbUserTable` as `u` WHERE `u`.id=`c`.teacher"; break;
    case "reg": $query.=",`$dbMembershipTable` as `m` WHERE `u`.id=`c`.teacher AND `m`.`student`= '{$_SESSION['id']}'".
                " AND `m`.`confirm`='j' AND `m`.`course`= `c`.id"; break;
    case "nreg": $query.=" WHERE `u`.id=`c`.teacher AND NOT EXISTS (SELECT * FROM `$dbMembershipTable` as `m` ".
                  "WHERE `c`.id=`m`.course AND `m`.student='{$_SESSION['id']}')"; break;
    default: $badRequest=true;
  }
  else if ($_SESSION['gp']=="t")
  {
    switch ($view)
    {
        case "all":$query.=" WHERE `c`.teacher=`u`.id ";  break;
        case "own": $query.=" WHERE `c`.`teacher`='{$_SESSION['id']}' AND `c`.teacher=`u`.id "; break;
        default: $badRequest=true;
    }
  }
  else
    $badRequest=true;

  $query.=" GROUP BY `c`.`id` ORDER BY ";
  switch($sort)
  {
    case "id": $query.="`c`.id"; break;
    case "name": $query.="`c`.name"; break;
    case "teacherF": $query.="`u`.firstName"; break;
    case "teacherL": $query.="`u`.lastName"; break;
    case "year": $query.="`c`.year"; break;
    case "lock": $query.="`c`.lock"; break;
    default: $badRequest=true;
  }
  
  $query.=" $order LIMIT $from , $limit;";
  return $query;
}

function getMembershipRequestsList($course, $sort, $order, $from, $limit) {
  //NOTE: if course id is not 'all' permission being cheched. don't concern.
  //FIXED
  global $badRequest, $dbCourseTable, $dbMembershipTable, $dbUserTable, $dbStudentTable;

  if (!$con=newMySQLi())
    return false;
  
  $badRequest=false;
  $res=false;

  if ($course=="all")
    $query="SELECT `m`.id, CONCAT( `u`.firstName, ' ', `u`.lastName ), `s`.number, `u`.email, `c`.name ".
    "FROM `$dbMembershipTable` AS `m`, `$dbStudentTable` AS `s`, `$dbUserTable` as `u` , `$dbCourseTable` AS `c` ".
    "WHERE `m`.student = `u`.id AND `m`.confirm='w' AND `c`.id = `m`.course AND EXISTS (".
    "SELECT * FROM `$dbCourseTable` AS `c` WHERE `m`.course = `c`.id AND `c`.teacher = '{$_SESSION['id']}') ";
  else
    $query="SELECT `m`.id, CONCAT( `u`.firstName, ' ', `u`.lastName ), `s`.number, `u`.email, `c`.name ".
    "FROM `$dbMembershipTable` AS `m`, `$dbStudentTable` AS `s` , `$dbUserTable` as `u`, `$dbCourseTable` AS `c` ".
    "WHERE `c`.id=`m`.course AND `m`.confirm='w' AND `c`.id='{$course}' AND `c`.teacher='{$_SESSION['id']}' AND `m`.student=`s`.id";

  $query.=" AND `s`.id=`u`.id GROUP BY `m`.id ORDER BY ";
  switch($sort)
    {
      case "id": $query.="`m`.id"; break;
      case "course": $query.="`c`.name"; break;
      case "firstName": $query.="`s`.firstName"; break;
      case "lastName": $query.="`s`.lastName"; break;
      case "number": $query.="`s`.number"; break;
      default: $badRequest=true;;
    }
  $query.=" $order LIMIT $from , $limit;";
  return $query;
}


function getLogsList ($view, $course, $sort, $order, $from, $limit)
{
  //all for course meens all of TEACHER courses
  global $badRequest, $dbCourseTable, $dbExerciseTable, $dbExerciseSeriTable, $dbMembershipTable, $dbUserTable, $dbStudentUploadTable;

  //DANGER: $_SESSION['gp] needs another mechanism!
  switch ($_SESSION['gp']) {
  case 't': $query="SELECT `es`.id AS `seriId`, `e`.id AS `exerciseId`, `e`.number, `u`.id AS `studentId`, CONCAT(`u`.firstName, ' ', `u`.lastName) AS  studentName, `c`.name AS `courseName`, `es`.seri AS `seriNum`".
  " FROM `$dbStudentUploadTable` AS `su`, `$dbExerciseTable` AS `e`, `$dbExerciseSeriTable` AS `es`, `$dbCourseTable` AS `c`, `$dbUserTable` AS `u`, `$dbMembershipTable` AS `m`".
  " WHERE `es`.id=`e`.seri AND `es`.course=`c`.id AND `c`.teacher='{$_SESSION['id']}' AND `m`.student=`u`.id AND `m`.course=`c`.id AND `m`.confirm='j' AND `su`.student=`m`.student AND `su`.seri=`es`.id "; break;
  case 's': $query="SELECT `es`.id AS `seriId`, `e`.id AS `exerciseId`, `e`.number, `s`.id AS `studentId`, `c`.name AS `courseName`, `es`.seri AS `seriNum`".
  " FROM `$dbExerciseTable` AS `e`, `$dbExerciseSeriTable` AS `es`, `$dbCourseTable` AS `c` `$dbMembershipTable` AS `m`".
  " WHERE `es`.id=`e`.seri AND `es`.course=`c`.id AND `es`.student='{$_SESSION['id']}' AND `e`.student='{$_SESSION['id']}' AND `m`.student=`s`.id AND `m`.course=`c`.id AND `m`.confirm='j'"; break;
  };

  if ($course!="all")
    $query.=" AND `c`.id='$course'";

  $query.=" GROUP BY ";
  switch ($view)
  {
    case "seri": $query.="`u`.id, `es`.id"; break;
    case "single": $query.="`u`.id, `e`.id"; break;
    default: $badRequest=true; break;
  }
  $query.=" ORDER BY ";
  switch($sort)
  {
    case "studentF": $query.="`u`.firstName"; break;
    case "studentL": $query.="`u`.lastName"; break;
    case "courseName": $query.="`c`.name"; break;
    case "seriNum": $query.="`es`.seri"; break;
    default: $badRequest=true;
  }
  $query.=" $order LIMIT $from , $limit;";
  return $query;
}

function getCourseMembersList ($course, $sort, $order, $from, $limit)
{
  //FIXED
  global $badRequest, $dbCourseTable, $dbMembershipTable, $dbStudentTable, $dbUserTable;

  $query="SELECT `s`.number, CONCAT(`u`.firstName, ' ', `u`.lastName) AS sName, `c`.name AS cName, `m`.gradeAverage, `u`.id AS sID, `c`.id as cID".
	 " FROM `$dbMembershipTable` AS `m`, `$dbCourseTable` AS `c`, `$dbStudentTable` AS `s`, `$dbUserTable` AS `u`".
	 " WHERE `u`.id=`s`.id AND `m`.confirm='j' AND `m`.course=`c`.id AND `m`.student=`s`.id";

  switch ($course)
  {
    case "all": break;
    case "own": $query.=" AND `c`.teacher={$_SESSION['id']}"; break;
    default: $query.=" AND `m`.course='$course'"; break;
  }

  $query.=" GROUP BY `m`.id ORDER BY ";

  switch ($sort)
  {
    case "sNumber": $query.=" `s`.number"; break;
    case "sFirstName": $query.=" `u`.firstName"; break;
    case "sLastName": $query.=" `u`.lastName"; break;
    case "cName": $query.=" `c`.name"; break;
    case "mGrade": $query.=" `m`.gradeAverage"; break;
    default: $badRequest=true;
  }
  $query.=" $order LIMIT $from , $limit;";
  return $query;
}

/*
DEPRECATED
function getTeacherExercisesList($view, $course, $sort, $order, $from, $limit) {
  //NOTE: if course is not 'all' permission being cheched. don't worry.
  //FIXED
  
  global $badRequest, $dbCourseTable, $dbUserTable, $dbExerciseSeriTable, $dbExerciseTable;

  $query="SELECT `es`.seri, `es`.createDate, `es`.deadlineDate, CONCAT( `u`.firstName, ' ', `u`.lastName ), `c`.name, `es`.`lock`,".
	 "IF (NOW() >`es`.deadlineDate, 1, 0) as `expire`, `es`.id , `e`.id, `e`.number".
	 " FROM `$dbExerciseSeriTable` as `es`, `$dbExerciseTable` as `e`, `$dbCourseTable` as `c`, `$dbUserTable` as `u`".
	 " WHERE `es`.course=`c`.id AND `c`.teacher=`u`.id AND `e`.seri=`es`.id ";
   switch ($course)
   {
     case "all": break;
     case "own": $query.=" AND `u`.id={$_SESSION['id']}"; break;
     default: $query.=" AND `c`.id='$course' "; break;
   }
   
   switch ($view)
   {
     case "single": $query.=" GROUP BY `e`.id"; break;
     case "seri": $query.=" GROUP BY `es`.id"; break;
     default: $badRequest=true;
   }

  $query.=" ORDER BY ";
  switch($sort)
    {
      case "courseName": $query.="`c`.name"; break;
      case "seriNum": $query.="`es`.seri"; break;
      case "teacherF": $query.="`u`.firstName"; break;
      case "teacherL": $query.="`u`.lastName"; break;
      case "cDate": $query.="`es`.createDate"; break;
      case "dDate": $query.="`es`.deadlineDate"; break;
      case "expire": $query.="expire"; break;
      default: $badRequest=true;
    }

  $query.=" $order LIMIT $from , $limit;";
  return $query;
}
*/

function getStudentExercisesList ($view, $solved, $expired, $course, $sort, $order, $from, $limit)
{
  global $badRequest, $dbExerciseSeriTable, $dbMembershipTable, $dbExerciseTable, $dbCourseTable, $dbStudentUploadTable;
  $query="";
  $query="SELECT `es`.seri, `c`.name, `es`.createDate, `es`.deadlineDate, `es`.correctionDate, IF (NOW() >`es`.deadlineDate, 1, 0) as `expire`, ".
	 "IF (NOW() >`es`.correctionDate, 1, 0) as `check` , `es`.`lock`, `es`.id AS seriID, `e`.id AS exID, `e`.number, `su`.grade as `seriGrade`, ".
	 "`su`.id AS suID, `su`.date as `sentDate`, `m`.confirm, `c`.id AS `cid` ".
	 "FROM `$dbExerciseTable` as `e` LEFT JOIN `$dbStudentUploadTable` as `su` ON `su`.student='{$_SESSION['id']}' AND `e`.seri=`su`.seri, ".
	 "`$dbCourseTable` as `c` LEFT JOIN `$dbMembershipTable` as `m` ON `m`.student='{$_SESSION['id']}' AND `m`.course=`c`.id, ".
	 "`$dbExerciseSeriTable` as `es` ".
	 "WHERE `es`.id=`e`.seri AND `es`.course=`c`.id ";

  switch ($course)
  {
    case "all": break;
    case "own": $query.=" AND `m`.confirm='j'"; break;
    default: $query.=" AND `c`.id='{$course}'"; break;
  }
  switch ($expired)
  {
    case "yes": $query.=" AND NOW() > `es`.deadlineDate"; break;
    case "no": $query.=" AND NOW() < `es`.deadlineDate"; break;
    case "all": break;
    default: $badRequest=true;
  }

  switch ($solved)
  {
    case "yes": $query.=" AND `su`.id IS NOT NULL"; break;
    case "no": $query.=" AND `su`.id IS NULL"; break;
    case "all": break;
    default: $badRequest=true;
  }

  switch ($view)
  {
     case "single": $query.=" GROUP BY `e`.id"; break;
     case "seri": $query.=" GROUP BY `es`.id"; break;
     default: $badRequest=true;
  }
  $query.=" ORDER BY ";
  switch($sort)
  {
      case "cName": $query.="`c`.name"; break;
      case "eSeri": $query.="`es`.seri"; break;
      case "cDate": $query.="`es`.createDate"; break;
      case "dDate": $query.="`es`.deadlineDate"; break;
      case "expired": $query.="expire"; break;
      default: $badRequest=true;
  }
  $query.=" $order LIMIT $from, $limit";
  return $query;
}

function addTeacher($firstName, $lastName, $email, $password) {

  global $errors, $dbTeacherTable, $md5key;
  if (!$con=newMySQLi())
    return false;

  $firstName=$con->real_escape_string($firstName);
  $lastName=$con->real_escape_string($lastName);
  $email=$con->real_escape_string($email);

  $res=false;

  $dbRes=$con->query("SELECT `id` FROM `$dbTeacherTable` AS `t` WHERE (`t`.email='$email') OR (`t`.firstName='$firstName' AND `t`.lastName='$lastName');");
  if ($dbRes and $dbRes->fetch_row())
    $errors.="<li>از این مشخصات قبلا استفاده شده</li>";
  else
  {
    $password=md5($password.$md5key);
    $dbRes=$con->query("INSERT INTO `$dbTeacherTable` (`id`, `firstName`, `lastName`, `email`, `password`, `lastLogin`, `lock`)"
		      ."VALUES (NULL, '$firstName', '$lastName', '$email', '$password', NULL, NULL);");
    if (!$dbRes)
      $errors.="<li>خطا در اضافه کردن استاد</li>";
    else
      $res=true;
  }
  $con->close();
  return $res;
}

function removeTeacher($id) {
  global $errors, $dbTeacherTable, $dbCourseTable;
  if (!$con=newMySQLi())
    return false;

  settype ($id, 'integer');
  $res=false;

  $dbRes=$con->query("UPDATE `$dbCourseTable` SET `lock`='1' WHERE `teacher` ='$id';");
    if (!$dbRes)
      $errors.="<li>خطا در بستن دروس استاد</li>";
  $dbRes=$con->query("DELETE FROM `$dbTeacherTable` WHERE id='$id' LIMIT 1;");
  if ($dbRes)
    $res=true;
  else
    $errors.="<li>خطا در حذف استاد</li>";

  $con->close();
  return $res;
}

function checkNewMembershipRequest($courseID=null)
{
  //if $courseID is null, returns whether loged id teacher has new request in any of his courses
  //else returns whether specified course has new request or not and teacher loged in not cheched
  require_once 'libcc/db.class.php';
  
  if (! $con = DB::instance() )
    return false;

  $res = 0;
  $stmt = $con->prepare( 'call get_nof_students_in_wl(?,?)' );
  $stmt->bindParam(1, $_SESSION['id'], PDO::PARAM_INT);
  $stmt->bindParam(2, $courseID, PDO::PARAM_INT|PDO::PARAM_NULL);
  
  $stmt->execute();
  $res = $stmt->fetch(PDO::FETCH_NUM)[0];
  return $res;
}

function addNotice ($title, $body)
{
  global $errors, $dbNoticeTable;

  require_once 'libcc/db.class.php';
  $con = DB::instance();
  $query="call add_new_announcement(?,?)";
  ($stmt=$con->prepare($query)) || errorLogger('DB', "خطا در اضافه کردن رکورد اعلان", true);
  $stmt->bindParam(1, $title, PDO::PARAM_STR);
  $stmt->bindParam(2, $body, PDO::PARAM_STR);
  ($stmt->execute()) || errorLogger ('DB', $errors.=$stmt->error, true);
  $con = null;

  if (!empty($errors))
    return false;

  return true;
}

function showMsg ($strMsg, $goURL=null)
{
  //first argument is message,
  //second argument is possible URL redirected to it after closing message. 
  jsRunner (
    "document.getElementById('alertMsg').innerHTML = '".addslashes($strMsg)."';"
    ."showMsg('$goURL');"
    );
}

function showAlert ( $type, $message, $timeout=0, $callback=null, $url='' )
{
   // 0 for timeout means javascript part decides
   // callback is something like "function() {alert ('hey');}" or null. NO need to addslashes
   // callback executed after header alert closed
   // url is section after siteURL
   if ( is_null( $timeout ) )
        $timeout=0;
   if ( is_null( $callback ) )
        $callback = "''";

   $s="showAlert( '$type', '".addslashes($message)."', $timeout, $callback, '$url' );";
   jsRunner($s);
}

function jsRunner($jsStr)
{
  echo '<script type="text/javascript" >';
  echo $jsStr;
  echo '</script>';
}

function dieJSON($code, $str=null)
{
  global $ERROR;
  $str==null ?
  die(json_encode(array('error'=> $ERROR[$code], 'errcode'=>$code)))
  :
  die(json_encode(array('error'=> $str, 'errcode'=>$code)));
}

function signInFirst( $who=null, $post=false )
{
  global $siteURL;
  
  if ( is_null($_SESSION['id']) || !hasPrivilege($who) )
  {
    if ($post)
        dieJSON('SIGNIN');
    else
    {
        $_SESSION['go']=$siteURL.$_SERVER['REQUEST_URI'];
        header("Location: $siteURL/signin");
    }
      
  }

}

function redirect404( &$env = null )
{
	
    header('HTTP/1.1 404 Not Found');

	if( is_null( $env ) )
    	die(<<<'EOC'
<html>
<head>
<title>Not Found</title>
</head>
<body>
<h1>The requested page doesn't exist</h1>
<h2>or you shouldn't see it</h2>
</h3>or it's my fault</h3>
<h4>or ...</h4>
<p>how are u?</p>
</body>
</html>
EOC
);
	
	if(isset($_SESSION['locale']))
		$env->setLocaleFormatter(new LocaleFormatter($_SESSION['locale']));
	else
		$env->setLocaleFormatter(new LocaleFormatter('en'));

	if( $env->isHTML() ) {
		$env->setLayout('error/404');
		$env->setData('title', '404');
	}
	else if ( $env->isJSON() ) {
		$env->setHeaders();
		dieJSON('E404');
	}

	
}

function redirect302( $destination ) {
	header('HTTP/1.1 302 Found');
	header('Location: '.URL_ROOT.$destination);
	die();
};

function url ($type, $id)
{
  return "$siteURL/$type/$id";
//   switch ($type)
//   {
//     case "profile": return "$siteURL/profile/$id";
//     case "course": return "$siteURL/course/$id";
//   }
}

function jsConfig ( $retOrCfg = false )
{
  $cfg = array('url'=>__url__, 'course_url'=> __url__.'/course', 'exercise_url'=>__url__.'/exercise', 'profile_url'=>__url__.'/profile',
	    'seri_url'=>__url__.'/seri');

  if ( is_array($retOrCfg) )
  {
    foreach ($retOrCfg as $key => $val)
      $cfg[$key] = $val;
  }
  if ( is_bool($retOrCfg) && $retOrCfg )
  {
    return $cfg;
  }

  jsRunner( sprintf ("var cfg=%s;\n", json_encode($cfg) ) );
}

function getTopNotices( $n ) {
    require_once 'libcc/db.class.php';
    $con = DB::instance();
	$stmt = $con->prepare('call get_top_announcements(?);');
	$stmt->bindParam(1, $n, PDO::PARAM_INT);
	$stmt->execute();
	while ($row=$stmt->fetch(PDO::FETCH_NUM))
			echo '<li><a href="'.Routing::genURL('announcement').'/'.$row[0].'">'.$row[1].'</a></li>';
    
}

?>

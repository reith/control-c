<?php
// PROCESS FOR TEACHER ADD COURSE

signInFirst(t, true);

require_once 'libcc/formating.php';
require_once "libcc/date.php";

$courseName = $_POST['cn'];
$language = "";
$errors="";
$output=array();

if (empty($courseName))
  dieJSON('EMPFRM');

$now=date("Ymd");
$now=(gregorian_to_jalali(substr($now, 0, 4), substr($now, 4,2), substr($now, 6,2)));
$courseYear=(int)substr($now, 0,4);

switch ($_POST['lang']) {
  case 'pas': $language='Pascal'; break;
  case 'c': $language='C'; break;
  case 'c++': $language='CPP'; break;
  default: dieJSON('BADDATA'); break;
    
}

require 'libcc/db.class.php';
$con = DB::instance();
$stmt = $con->prepare('call add_new_course(?,?,?,?)');
$stmt->bindParam(1, $_SESSION['id'], PDO::PARAM_INT);
$stmt->bindParam(2, $courseName, PDO::PARAM_STR);
$stmt->bindParam(3, $language, PDO::PARAM_STR);
$stmt->bindParam(4, $courseYear, PDO::PARAM_INT);
$stmt->execute();

$res = $stmt->fetch(PDO::FETCH_NUM)[0];
if ( $res )
{
	$title="درس $courseName اضافه شد";
	$body="درس <a href=\"".Routing::genURL('course')."/$res\">$courseName</a> به کنترل‌سی اضافه شد. ";
	$body.="<br />"."زبان برنامه نویسی: <span dir='ltr'>$language</span>";
	
	$output['message'] = "$title !";
	// addNotice ($title, $body);
}
else {
    $output['error'] = _('You Can\'t.');
}

$con = null;
die (json_encode($output));

?>
<?php
// PROCESS FOR TEACHER EDIT COURSE

signInFirst('t');
$course = (int)$_POST['course'];
$action = $_POST['action'];
$output = array();
$errors="";
if (empty($course))
  dieJSON('BADDATA');

$locking = null;

switch ( $action )
{
  case "lock": $locking = 1; break;
//   case "delete":  die
  case "active": $locking = 0; break;
  default: dieJSON('BADDATA');
}

require_once 'libcc/db.class.php';
$con = DB::instance();
$stmt = $con->prepare('call un_lock_course(?,?,?)');
$stmt->bindParam(1, $course, PDO::PARAM_INT);
$stmt->bindParam(2, $_SESSION['id'], PDO::PARAM_INT);
$stmt->bindParam(3, $locking, PDO::PARAM_BOOL);
$stmt->execute();
$res = $stmt->fetch(PDO::FETCH_NUM)[0];
if ($res)
	$output['message'] = _('Changes was successfull');
else {
    $output['error'] = _('No Changes made. Check your permissions.');
}
$con = null;
die ( json_encode($output) );
?>
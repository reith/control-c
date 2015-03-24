<?php
//   REITH: PROCESS VERIFYING MEMBERSHIP, REMOVING MEMBERSHIP REQUESTS OR IGNOR
require_once 'libcc/general.functions.php';
signInFirst('t', true);
// sig
$query="";
$output=array('error'=>null, 'msg'=>array('id'=>array(), 'done'=>array()));

require 'libcc/db.class.php';
$con = DB::instance();
$stmt = $con->prepare('call un_verify_course_membership(?,?,?)');

foreach($_POST as $mID => $do) {
  $mID = (int) $mID;
  $result = false;
  switch ($do)
  {
	case 'c': $result = true; break;
    case 'a': 
    case 'd': 
    case 'b':
		$stmt->bindParam(1, $mID, PDO::PARAM_INT);
		$stmt->bindParam(2, $_SESSION['id'], PDO::PARAM_INT);
		$stmt->bindParam(3, $do, PDO::PARAM_STR);
		$result = $stmt->execute();
		break;
    default: dieJSON('BADDATA');
  }
  $output['msg']['id'][]=$mID;
  if ($result)
    $output['msg']['done'][]=$do;
  else
    $output['msg']['done'][]='Error';
}

$con = null;
die(json_encode($output));

?>

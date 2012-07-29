<?php
	require_once 'libcc/db.class.php';
	
  $con = DB::instance();
  global $errors;
  $output=array();

  if (!isset($_POST['un']))
    dieJSON('BADDATA');

	$stmt = $con->prepare('SELECT user_exists(?,"")');
	$un = $_POST['un'];
	$em = '';
	$stmt->bindParam(1, $un, PDO::PARAM_STR);
	$stmt->execute();
  if ($stmt->fetch(PDO::FETCH_NUM)[0])
      $output['s']='r';
	else
      $output['s']='n';

  if (!empty($errors)) {
    dieJSON('DB');
  }

  echo json_encode($output);
?>
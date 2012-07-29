<?php
/*
  REITH: PROCCESS STUDENT SIGNUP ALERT
*/

require 'libcc/db.class.php';

$sn = (int)$_POST["sn"];
$un = trim($_POST["un"]);
$fn = trim($_POST["fn"]);
$ln = trim($_POST["ln"]);  
$email = trim($_POST["email"]);
$psw = trim($_POST["psw"]);
$pswc = trim($_POST["pswc"]);
$prv_s = $_POST["prv_s"];
$prv_t = $_POST["prv_t"];
$prv_ca = $_POST["prv_ca"];
$prv_c = $_POST["prv_c"];


if ( ($email == "" || $psw == "" || $pswc  == "" || $fn == "" || $ln == "" || $un == "") || ( ($prv_s == 1) && ($sn == "" ) ) )
  dieJSON('EMPFRM');

if ($psw == $pswc)
  $psw = md5($psw.$md5key);
else
  dieJSON(null, _('Enter same password twice'));

$prvs=array();
$prv_s && $prvs[]='s';
$prv_t && $prvs[]='t';
$prv_c && $prvs[]='c';
$prv_ca && $prvs[]='ca';

$con = DB::instance();
$stmt = $con->prepare('SELECT user_exists(?, ?)');
$stmt->bindParam(1, $un, PDO::PARAM_STR);
$stmt->bindParam(2, $email, PDO::PARAM_STR);

if ($stmt->execute())
{
  if ( $stmt->fetch( PDO::FETCH_NUM)[0] )
    dieJSON (null, _('You have entered duplicated data'));
  // else user doesn't exists. good.
}
else
{
    errorLogger('DB_QUERY', 'Query: '.$query.'Error: '.$con->error);
    dieJSON ('DB_QUERY');
}
$output['error']=$ERRORS['DB_QUERY'];

if ( empty($output['error']) )
{
  $stmt = $con->prepare('call add_new_user(:un, :fn, :ln, :em, :pw, :prv, :sn, :rkey)' );

	$randkey = md5(date().$psw.$md5key);
	$stmt->bindParam(':un', $un, PDO::PARAM_STR);
	$stmt->bindParam(':fn', $fn, PDO::PARAM_STR);
	$stmt->bindParam(':ln', $ln, PDO::PARAM_STR);
	$stmt->bindParam(':em', $email, PDO::PARAM_STR);
	$stmt->bindParam(':pw', $psw, PDO::PARAM_STR);
	$stmt->bindParam(':prv', implode(',', $prvs), PDO::PARAM_STR);
	$stmt->bindParam(':sn', $sn, PDO::PARAM_INT);
	$stmt->bindParam(':rkey', $randkey, PDO::PARAM_STR);
	
	$result = false;
	if ( $stmt->execute() )
		list($result, $id) = $stmt->fetch(PDO::FETCH_NUM);
  if ( $result )
  {
      $output['msg']=_('Registeration was successfull');
      $message="Hi,\r\nSomeone [probably yourself] used your email address to register an account on our site.".
         "\r\nIf you're who provided this mail address, follow this URL to complete your registeration: \r\n".
         __url__."/confirm.php?id=".$id."&key=".$randkey."\r\n".
         "Good Luck! :)\r\n";

      mail( $email, "Confirm your ^C account", wordwrap($message, 120), $mailHeader, $mailOptions);
  }
}

if ($output['error'])
{
    errorLogger('DB_QUERY', 'Query: '.$query.'Error: '.$con->error);
}

echo json_encode($output);
?>

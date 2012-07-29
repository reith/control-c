<?php
require '../config.php';
require '../session.php';
require '../general.functions.php';

$email = trim($_POST['email']);
$output=array('error'=>null);

if (empty($email))
    dieJSON('EMPFRM');

$con = newMySQLi();
$query = "SELECT `id` from `$dbUserTable` WHERE `email`=?";
( ($stmt=$con->prepare($query)) || errorLogger('DB_QUERY', "$con->error in $query", 'json') );
$stmt->bind_param("s", $email);
$stmt->execute();

$user="";
$stmt->bind_result($user);
$stmt->fetch();
$stmt->close();
$output['message'] = _('A mail schedulated to be sent.. that contains a link to change your password');

if ( !empty($user) )
{
    $confirm=md5($user.date('u').$email.$md5key);
    $query="INSERT INTO `$dbConfirmTable` (`user`, `key`, `type`) VALUES (?, ?, 'r')";
    ( ($stmt=$con->prepare($query)) || errorLogger('DB_QUERY', "$con->error in $query", 'json') );
    $stmt->bind_param("ds", $user, $confirm);
    if ( $stmt->execute() )
        mail( $email, "^C password recovery", 'If you had requested for new password, please follow this link: '.$siteURL.'/?g=new_passwd&id='.$confirm, $mailHeader );
}

echo json_encode($output);
$stmt->close();
$con->close();

?>
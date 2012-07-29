<?php
// require "date.php";

require_once 'libcc/db.class.php';

$errors="";
$user=(int)$_REQUEST['id'];
if (! $user )
  die( redirect404() );

$con = DB::instance();
($stmt = $con->prepare('call get_user_data(?)')) || errorLogger('DB', $con->error, true);
$stmt->bindParam(1, $user, PDO::PARAM_INT);
($stmt->execute()) || errorLogger('DB', $ntres->error, true);

$un="";
$name="";
$email="";
$ll="";
$msg="";
$prv="";

$stmt->bindColumn(1, $un);
$stmt->bindColumn(2, $name);
$stmt->bindColumn(3, $email);
$stmt->bindColumn(4, $ll);
$stmt->bindColumn(5, $msg, PDO::PARAM_STR|PDO::PARAM_NULL);
$stmt->bindColumn(6, $prv);

if (! $stmt->fetch(PDO::FETCH_BOUND) )
  redirect404();

$prv = preg_split('/,/', $prv);
$jll=jalaliLongDate($ll);

echo<<<EOF
    <label>نام: </label>$name<br />
    <label>آخرین ورود: </label>$jll<br />
EOF;
if ($msg)
      printf ("<div class='comment'>%s</div> <br />", $msg);

if (in_array( 't', $prv ) )
{
    ($stmt = $con->prepare('call get_teacher_courses(?)')) || errorLogger('DB', $con->error, true);

    $cid="";
    $cname="";
	
	$stmt->bindParam(1, $user, PDO::PARAM_INT);
    $stmt->execute();
    $stmt->bindColumn(1, $cid, PDO::PARAM_INT);
	$stmt->bindColumn(2, $cname, PDO::PARAM_STR);
	
    while ( $stmt->fetch(PDO::FETCH_BOUND) )
        echo "<a href='".Routing::genURL('course')."/$cid'>$cname</a> <br />";
}

if ( in_array ('s', $prv) )
{
    ($stmt = $con->prepare(' call get_student_courses(?) ')) || errorLogger('DB', $con->error, true);

    $cid="";
    $cname="";
    $avggrade="";

	$stmt->bindParam(1, $user, PDO::PARAM_INT);
    $stmt->execute();
    $stmt->bindColumn(1, $cid, PDO::PARAM_INT);
    $stmt->bindColumn(2, $cname, PDO::PARAM_STR);
    $stmt->bindColumn(3, $avggrade, PDO::PARAM_INT);

    while ( $stmt->fetch(PDO::FETCH_BOUND) )
        echo "<a href='".Routing::genURL('course')."/$cid'>$cname</a> با نمره $avggrade<br />";

//     ($lscrs=$con->prepare(
//         "SELECT `grade` FROM `$dbStudentTable` WHERE `id`='$user' LIMIT 0,1")) || errorLogger('DB', $con->error, true);
//     $grade="";
//     $lscrs->execute();
//     $lscrs->bind_result($grade);
//     if ($lscrs->fetch())
//         echo "<hl /> نمره کل دروس عضو شده: $grade";
    
}
?>

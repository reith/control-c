<?php
// IMPORTANT: it doesnt check mailServer=true
// $1 seriId
// $2 courseId
// $3 seriNumber
// $4 courseName

if ($argc != 5)
  die('bad arguments');

$seriId=$argv[1];
$courseId=$argv[2];
$seriNumber=$argv[3];
$courseName=$argv[4];

require '../config.php';
require '../formating.php';
require '../general.functions.php';

($con=newMySQLi()) || die('db connection failed');
$mailQuery="SELECT `s`.`email`, `s`.`id` FROM `$dbUserTable` AS `s` LEFT JOIN `$dbMembershipTable` AS `m` on `s`.id=`m`.student ".
			"WHERE `m`.confirm='j' AND `m`.course=? AND `s`.receiveMail='1'";
($mails=$con->prepare($mailQuery)) || errorLogger ("Fetch Mail Prepare", "$con->error\n$mailQuery", true);
($mails->bind_param("d", $courseId)) || errorLogger ("Fetch Mail bind_param", "$mails->error\n$mailQuery", true);
$mailaddr="";
$student=0;
($mails->execute()) || errorLogger ("Fetch Mail Execute", "$mails->error\n$mailQuery", true);
$mails->store_result();
$mails->bind_result($mailaddr, $student);

if ($mails->num_rows>0)
{
  $subject='=?UTF-8?B?'.base64_encode("تمرینات سری ".fa_number($seriNumber)." $courseName").'?=';
  $message="تمرینات سری ".fa_number($seriNumber)." درس ".$courseName." اضافه شد."."\n";
  $message="برای مشاهده‌ی تمرینات آدرس زیر را ببینید: "."\n";
  $message.="$siteURL/seri/id\n";
  while ($mails->fetch())
	mail ($mailaddr, $subject, wordwrap($message.$mailFooter, 70), $mailHeader);
}
  
$mails->close();
$con->close();
?>
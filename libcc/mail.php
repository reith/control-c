<?php
require_once 'libcc/config.php';
require_once 'libcc/general.functions.php';
require_once 'libcc/formating.php';

$msg=<<<EOF
پروژه‌های مبانی برنامه‌سازی مقدماتی و پیشرفته روز سه‌شنبه ۱۲ بهمن تحویل گرفته می‌شوند. دانشجویان درس برنامه‌سازی پیشرفته باید از ساعت ۸ تا ۱۱ مراجعه کنند. مکان تحویل پروژه طبقه‌ی دوم سایت مرکزی می‌باشد. طبق زمان‌بندی ارائه شده حضور پیدا کنید؛ پروژه‌ای خارج از نوبت تحویل گرفته نمی‌شود. حضور تمام اعضای گروه برای ارائه‌ی پروژه الزامیست.

جدول زمان‌بندی برای دانشجویان مبانی برنامه‌سازی مقدماتی: http://85.185.67.254:2223/share/schedule.pdf
EOF;
$con=newMySQLi();
$student=0;
    $mailaddr="";
    $mailQuery="SELECT `email` FROM `$dbUserTable`";
    ($mails=$con->prepare($mailQuery)) || print ('error');
//       echo "Fetch Mail Prepare"."$con->error\n$mailQuery";
    if ($mails->execute()==0)
      echo "Fetch Mail Execute"."$mails->error\n$mailQuery";
    $mails->store_result();
    $mails->bind_result($mailaddr);
    $mailaddr="";
    if ($mails->num_rows>0)
    {
      $subject='=?UTF-8?B?'.base64_encode("ارائه‌ی پروژه ".fa_number($seriNumber)." $courseName").'?=';
      while ($mails->fetch())
      {
 # 	mail ($mailaddr, $subject, wordwrap($msg.$mailFooter, 120), $mailHeader);
      }
    }
  $mails->close();
  $con->close();
?>

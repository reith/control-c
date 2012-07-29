<?php
// REITH: PROCESS FOT SEND EXERCISE
// NOTE: THIS FILE REQUIRED BY STUDENT EXERCISE FORM. CHANGE IT TO SOMETHING SENT BY AJAX.

signInFirst('s', true);
$errors="";
$seriId = $_POST["s"];
$userFile = $_FILES["f"];
$oldSend=($_POST["o"]=='null' || $_POST["o"]==null)?false:(int)$_POST["o"];
$output=array("error");
$types=array("application/zip", "application/octet-stream", "application/x-zip-compressed");

if (empty($seriId))
  dieJSON('EMPFRM');

if (file_exists("$exercisePath/$seriId/{$_SESSION['id']}/uploaded.zip") && (!$oldSend))
  dieJSON('FILEEXT');

if ($userFile["error"] == 4)
   dieJSON('9', 'فایل زیپ شده‌ی تمرینات را انتخاب نکرده‌اید');

 if ($userFile["size"] < 1)
   dieJSON('13', 'فایل معتبری انتخاب نشده است.');

if (!in_array($userFile["type"], $types))
   dieJSON('15', 'فایل ارسالی شما با فرمت zip ساخته نشده.');

if ($userFile["size"] > $exerciseSizeLimit)
   dieJSON('17', 'حجم فایل ارسالی شما از حجم مجاز بیشتر است.');

if ($userFile["error"] > 0)
   dieJSON('11', 'متاسفانه خطای نامشخصی در ارسال فایل رخ داد.');

$con=newMySQLi();
$query="SELECT count(*) FROM `$dbExerciseSeriTable` AS `es`, `$dbCourseTable` AS `c`, `$dbMembershipTable` AS `m`"
      ." WHERE `es`.id = $seriId AND `es`.course=`c`.id AND `m`.course=`c`.id AND `m`.`confirm`='j' AND `m`.student={$_SESSION['id']} AND `es`.deadlineDate > NOW()";

if (!$oldSend) 
  $query.=" AND NOT EXISTS (SELECT * FROM `$dbStudentUploadTable` as `su` WHERE `su`.seri = `es`.id AND `su`.student='{$_SESSION['id']}')";

$dbRes=$con->query($query);

if ($dbRes && $valid=$dbRes->fetch_row()) {
   if (!$valid[0])
	dieJSON ('19', 'شما مجاز به فرستادن فایل برای این سری از تمرینات نیستید.');
} else {
      errorLogger('DB', $con->error." IN: ".$query);
      dieJSON('DB');
  }

  //ALL VALID
  //save file on disk
  @mkdir("$exercisePath/$seriId/{$_SESSION['id']}", 0770, true);
//       dieJSON('21', 'خطا در اضافه کردن دایرکتوری تمرینات');
  if (!move_uploaded_file($userFile["tmp_name"], "$exercisePath/$seriId/{$_SESSION['id']}/uploaded.zip"))
      dieJSON('23', 'خطایی در ذخیره کردن فایل رخ داد');
    //add to database
  if ($oldSend)
    $query="UPDATE `$dbStudentUploadTable` SET `date` = NOW() WHERE `id` ='$oldSend'";
  else
    $query="INSERT INTO `$dbStudentUploadTable` (`id`, `student`, `seri`, `date`) VALUES"
      ." (null, {$_SESSION['id']}, '$seriId', NOW() )";

  $dbRes = $con->query ($query);
    if (!$dbRes)
    {
      errorLogger('DB', $con->error." IN: ".$query);
      dieJSON('DB');
      unlink ("$exercisePath/$seriId/{$_SESSION['id']}/uploaded.zip");
    }
    else
      $output['msg']="تمرینات شما با موفقیت دریافت شد.";

$con->close();

$output['msg']="تمرینات شما با موفقیت دریافت شد.";
echo json_encode($output);
?>
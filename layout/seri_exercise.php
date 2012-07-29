<?php
$errors="";
require 'libcc/db.class.php';

$seriPath=$_REQUEST['id'];
if (empty($seriPath))
  $errors.="<li>سری تمرینی انتخاب نشده است</li>";
if (empty($errors))
{
  $con = DB::instance();
  $query = "call get_exercise_seri_data(?)";
  $stmt = $con->prepare($query);
  $stmt->bindParam(1, $seriPath, PDO::PARAM_INT);
  if (! $stmt->execute() )
    $errors.="<li>خطا در بازیابی سری</li>";
  else if (! $res = $stmt->fetch(PDO::FETCH_OBJ) )
    $errors.="<li>سری تمرین موجود نیست</li>";
//   else //get single exercises
//   {
//     $estmt = $con->prepare("call get_seri_s_exercises(?)");
//     $estmt->bindParam(1, $seriPath, PDO::PARAM_INT);
//     $estmt->execute();
//   }
}
if (empty($errors))
{
  echo "<fieldset><legend>تمرین سری $res->seri از درس <a href='".__url__."/course/{$res->courseId}'>{$res->name}</a></legend>";
  if (!empty($res->comment))
    echo "<label>توضیحات: </label> <div class='exerciseBody'> $res->comment </div>";

  echo "<label>ضریب سری: </label>".fa_number($res->wage)."<br />";
  echo "<label>تعداد تمرین‌ها:</label> ".fa_number($res->exerciseCount)." <br />";


		
  echo "<label>اضافه شده در تاریخ:</label> ".jl_date($res->createDate)."<br/>";
  echo "<label>مهلت ارسال پاسخ:</label> ".jl_date($res->deadlineDate)."<br/>";
  echo "<label>زمان تصحیح:</label> ".jl_date($res->correctionDate)."<br/>";
  echo "<label> استاد درس:</label> <a href='".__url__."/profile/{$res->teacherId}'>{$res->teacherName}</a><br/>";
  echo "</fieldset>";

  if (! $res->expired)
    echo "<a href='".__url__."/exercises'>فرستادن پاسخ</a><br/>";

  else if ($res->checked)
  {
	$stmt->closeCursor(); 
	($sstmt = $con->prepare( 'call get_exercise_seri_results(?)' )) || die('xx');
	$sstmt->bindParam(1, $seriPath, PDO::PARAM_INT) || die('yyy');
	$sstmt->execute() || die('zzz');
    $row = $sstmt->fetch(PDO::FETCH_NUM);
      echo <<<EOF
      <fieldset>
	<legend>نتایج تصحیح</legend>
	<label>تعداد فایل دریافت شده: </label>$row[0] <br />
	<label>بیشینه‌ی نمره‌ی کسب شده: </label>$row[1] <br />
	<label>میانگین نمرات کسب شده: </label>$row[2] <br />
EOF;
    $sstmt->closeCursor();
  }
 
  $stmt->closeCursor(); 
  $estmt = $con->prepare("call get_seri_s_exercises(?)");
  $estmt->bindParam(1, $seriPath, PDO::PARAM_INT);
  $estmt->execute();
  $exercises=$estmt->fetchAll(PDO::FETCH_OBJ);
	foreach($exercises as $exercise) 
		echo "تمرین ".fa_number($exercise->number)." : <a href='".__url__."/exercise/{$exercise->id}'>\"{$exercise->title}\"</a> با ضریب ".fa_number($exercise->wage)."<br />";
}
if (!empty($errors))
  echo "<div class='errorText'>متاسفانه خطاهای زیر رخ داد: <br/> $errors</div";
?>
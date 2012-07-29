<?php
$errors="";
$exerciseId=$_REQUEST['id'];
if (empty($exerciseId))
  $errors.="<li>تمرینی انتخاب نشده است</li>";
if (empty($errors))
{
  $con=newMySQLi();
  $query="SELECT `c`.name, `c`.id AS `courseId`, `e`.explain, `e`.number, `e`.`tcCount`, `e`.wage, `es`.deadlineDate, `es`.correctionDate,"
  ."`e`.title, `es`.seri AS `seri`, `es`.id AS `seriId`, NOW()>`es`.`deadlineDate` AS expired, NOW()>`es`.`correctionDate` AS checked"
  ." FROM `$dbExerciseSeriTable` AS `es`, `$dbExerciseTable` AS `e`, `$dbCourseTable` AS `c`"
  ." WHERE `e`.`id`='$exerciseId' AND `es`.`id`=`e`.seri AND `c`.id=`es`.course";
  $dbRes=$con->query($query);
  
  if (!$dbRes)
    $errors.="<li>خطا در بازیابی تمرین</li>";
  else if (!$res=$dbRes->fetch_object())
    $errors.="<li>تمرین مورد نظر موجود نیست</li>";
}
if (empty($errors))
{
echo <<<EOF
<fieldset>
<legend>
EOF;
echo "تمرین {$res->number} <a href='".__url__."/seri/{$res->seriId}'>سری {$res->seri}</a> از درس <a href='".__url__."/course/{$res->courseId}'>{$res->name}</a>";
echo <<<EOF
</legend>
  <div class='exerciseBody'>
  $res->title <br />
  <span style="font-size: 30px;">«</span>
  $res->explain
  <span style="font-size: 30px;">»</span>
  </div>
  <div class="comment">
      ضریب این تمرین در سری تمرین برابر با $res->wage است	
  </div>
</fieldset>
EOF;

if (!$res->expired)
  echo "<br/><a href='".__url__."/exercises'>فرستادن پاسخ</a> تا "
	.jalaliLongDate($res->deadlineDate)." ممکن است<br/>";
else if ($res->checked)
{
  $dbRes=$con->query(
    "SELECT `compileError` , COUNT(*) AS COUNT, MAX(grade) AS MAXGRADE, AVG(grade) AS AVGGRADE, AVG(TIME) AS AVGTIME"
    ." FROM `${dbExerciseResultPrefix}$exerciseId`"
    ." GROUP BY compileError ORDER BY `compileError` ASC");
  if (!$dbRes)
    $errors.="<li>خطا در بازیابی نتایج تصحیح تمرینات</li>";
  if (empty ($errors))
  {
    echo "<br/> <fieldset><legend>نتایج تصحیح</legend>";
    while ($stat=$dbRes->fetch_object())
    {
      if (!$stat->compileError)
      {
	echo "<label>تعداد پاسخ‌های اجرا شده: </label> $stat->COUNT <br/>";
	echo "<label>میانگین نمره‌ی پاسخ‌های اجرا شده: </label> $stat->AVGGRADE <br/>";
	echo "<label>بیشینه‌ی نمره‌ی پاسخ‌های اجرا شده: </label> $stat->MAXGRADE <br/>";
	echo "<label> زمان میانگین اجرای برنامه‌ها: </label> $stat->AVGTIME <br/>";
	echo "<label> دفعات اجرای هر برنامه: </label> $res->tcCount";
      }
      else if ($stat->compileError)
	echo "<br/> <label>تعداد پاسخ‌های اجرا نشده (خطای گرامر): </label> $stat->COUNT" ;
    }
    echo "</fieldset>";
    if ( hasPrivilege('s') )
      echo "<br/>در این درس عضو هستید؟ نتایج تصحیح تمرینات خود را <a href='".__url__."/exercises'>ببینید</a>";
 }
}
else
  echo "<br/>تمرینات در تاریخ ".jalaliLongDate($res->correctionDate)."تصحیح خواهد شد.";
}
if (!empty($errors))
  echo "<div class='errorText'>متاسفانه خطاهای زیر رخ داد: <br/> $errors</div>";
?>
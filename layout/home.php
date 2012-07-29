<!--REITH: USER HOMEPEAGE-->
<?php
signInFirst();

require 'libcc/db.class.php';

if (hasPrivilege('s'))
{
    echo _('Student Panel').'<hr />';
  $con=DB::instance();
  if (!$con)
     errorLogger("DB", $con->error, true);

  $query="call get_student_average_grades(?)";
  $courseName="";
  $grade="";
  $courseId="";

  ($stmt=$con->prepare($query)) || errorLogger("DB", $con->error, true);
  $stmt->bindParam(1, $_SESSION["id"], PDO::PARAM_INT);
  $stmt->execute() || errorLogger("DB", $stmt->error, true);
  $stmt->bindColumn(1, $grade, PDO::PARAM_INT);
  $stmt->bindColumn(2, $courseName, PDO::PARAM_STR);
  $stmt->bindColumn(3, $courseId, PDO::PARAM_INT);
  
  while ($stmt->fetch(PDO::FETCH_BOUND))
  {
    if ($grade>0)
      printf ("شما تاکنون نمره %s از درس <a href='%s'>%s</a> اخذ کرده‌اید.<br />", fa_number($grade), Routing::genURL('course').'/'.$courseId, $courseName);
    else
      printf ("شما هنوز نمره‌ای از درس <a href='%s'>%s</a> اخذ نکرده‌اید.<br />", Routing::getURL('course').'/'.$courseId, $courseName);
  }
}

if (hasPrivilege('t'))
{
    echo _('Teacher Panel').'<hr />';
    $errors="";
    if (checkNewMembershipRequest())
        echo "دانشجویانی در انتظار ثبت در دروس شما هستند"."<br>"."می‌توانید از قسمت <a href='".Routing::genURL('membership_requests')."'>تايید درخواست</a>، به وضعیت آن‌ها رسیدگی کنید";
    if (!empty($errors))
    {
        showAlert("error", "خطا در چک کردن درخواست عضویت رخ داد: <br/> $errors");
    }
}

?>
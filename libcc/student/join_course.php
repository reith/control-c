<?php
require_once 'libcc/general.functions.php';
signinFirst('s', true);

require_once 'libcc/db.class.php';


$course = (int) trim($_POST['course']);
if ($course != $_POST['course'])
  $output['error'] .= "شماره‌ی درس معتبر وارد نشد.";

if (!empty($output['error']))
  die(json_encode($output));

$con = DB::instance();
$stmt = $con->prepare('call request_course_membership(?,?)');
$stmt->bindParam(1, $_SESSION['id'], PDO::PARAM_INT);
$stmt->bindParam(2, $course, PDO::PARAM_INT);
$stmt->execute();

if ($old_status = $stmt->fetch(PDO::FETCH_NUM)[0])
{
  switch ($old_status)
  {
    case 'w' : $output['message']='قبلا چنین درخواستی از شما دریافت شده است.<br /> عضویت شما در انتظار تائید استاد درس است.'; break;
    case 'j' : $output['message']='عضویت شما در این درس تائید شده است.'; break;
    case 'b' : $ouptut['message']='عضویت شما در این درس توسط استاد پذیرفته نشده است.<br />امکان ثبت تقاضای جدید وجود ندارد'; break;
  }
}
else
{
    $output['message']='درخواست شما برای عضویت در درس ثبت شد. در صورت تائید استاد می‌توانید از سیستم استفاده کنید';
}
echo json_encode($output);
?>

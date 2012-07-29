<?php
// REITH: PROCESS FOR ADDING ADMINISTRATOR'S TEACHER FEATURE
  require "../session.php";
  require "../general.functions.php";
  signInFirst("a");
  
  $firstName = $_POST['p0'];
  $lastName = $_POST['p1'];
  $email = $_POST['p2'];
  $password = $_POST['p3'];
  $passwordAgain = $_POST['p4'];
  
  $errors="";
  if ($firstName=="" || $lastName=="" || $email=="" || $password =="" || $passwordAgain=="")
    $errors.="<li>پر کردن تمامی فیلدها الزامی است.</li>"; 
  if ($password != $passwordAgain)
    $errors.="<li>کلمه‌ی عبور با تکرار آن مطابقت ندارد.</li>";
  if (empty($errors))
    addTeacher($firstName, $lastName, $email, $password);
  if (empty($errors))
    echo ("<div class='okText'>کلیه‌ی عملیات با موفقیت انجام شد</div>");
  else
    echo ("<div class='errorText'>متاسفانه خطاهای زیر رخ داد <br/> $errors </div>");  
?>

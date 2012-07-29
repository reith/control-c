<?php
//   REITH: PROCESS FOR ADMINISTRATIR'S DELETING TEACHER FEATURE
  require "../session.php";
  require "../general.functions.php";
  signInFirst("a");
  
  $id = (int) $_POST['p0'];
  $errors="";
  removeTeacher($id);
  if(empty($errors))
    echo ("<div class='okText'>کلیه‌ی عملیات با موفقیت انجام شد</div>");
  else
    echo ("<div class='errorText'>متاسفانه خطاهای زیر رخ داد <br/> $errors </div>");
?>
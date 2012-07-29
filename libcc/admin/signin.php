<?php
// REITH: SIGN IN ADMIN PROCESS
require "../session.php";
require "../general.functions.php";

$name = $_POST["p0"];
$password = md5(stripslashes($_POST["p1"]) . $md5key);
$errors="";
$dbRes = execQuery("SELECT `id` FROM `$dbAdminTable` WHERE `username`='$name' and `password`='$password';");
if (!$dbRes or !$row=$dbRes->fetch_row())
  $errors.="<li>"."مشخصات صحیح نیست!"."</li>";
else
{
  $_SESSION['id']=$row[0];
  $_SESSION['group']="admin";
}
if (!empty($errors))
{
  echo "متاسفانه خطاهای زیر رخ داد: "."<br>";
  echo "<div class='errorText'>";
  echo $errors;
  echo "</div>";
} 
?>
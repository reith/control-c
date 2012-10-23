<?php
ob_start();
header("Content-type: text/javascript");
require_once 'libcc/config.php';
require_once 'libcc/session.php';
require_once 'libcc/general.functions.php';


printf ("var cfg=%s;\n", json_encode( jsConfig(true) ) );

switch ( basename($_SERVER['REQUEST_URI']) )
{
  case 'tableForm': require 'script/TableForm.js.php'; break;
  case 'student.exercises': require 'script/student/exercises.js.php'; break;
  case 'teacher.membership_requests': require 'script/teacher/membership_requests.js.php'; break;
  case 'teacher.add_exercise': require 'script/teacher/add_exercise.js.php'; break;
}


?>

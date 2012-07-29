<?php
abstract class Routing
{
	/**
	 * @brief map of urls
	 **/
	static private $url_map = array(
		'signin' => '/signin',
		'signup' => '/signup',
		'announcement' => '/announcement',
		'course' => '/course',
		'membership_requests' => '/membership_requests',
		'profile' => '/profile',
		'seri' => '/seri',
		'exercise' => '/exercise',
		'home' => '/home'
		);
		
	/**
	 * @brief map of actions. JSON actions value is a an array with second element as 1
	 **/
	static private $proc_map = array(
		'signin' => array('/common/signin.php', 1),
		'signup' => array('/account/signup.php', 1),
		'student_exercises' => array('/student/exercises.php', 1),
		'student_courses' => array('/student/courses.php', 1),
		'student_join_course' => array('/student/join_course.php', 1),
		'check_username' => array('/account/check_username.php', 1),
		'view_detailed_logs' => array('/common/view_detailed_logs.php', 1),
		'teacher_exercises' => array('/teacher/exercises.php', 1),
		'teacher_courses' => array('/teacher/courses.php', 1),
		'teacher_students' => array('/teacher/students.php', 1),
		'teacher_logs' => array('/teacher/logs.php', 1),
		'teacher_membership_requests' => array('/teacher/membership_request.php', 1),
		'teacher_verify_membership' => array('/teacher/verify_membership.php', 1),
		'teacher_add_exercise' => array('/teacher/add_exercise.php', 1),
		'edit_course' => array('/teacher/edit_course.php', 1),
		'add_course' => array('/teacher/add_course.php', 1),
		'compiler' => array('/common/compiler.php', 0),
		'send_exercise' => array('/student/send_exercise.php', 0)
	);
	
	static public function genURL( $to, $absolute=false )
	{
		if ( isset(self::$url_map[$to] ) )
			if ( true === $absolute )
				return __url__.self::$url_map[$to];
			else
			    return self::$url_map[$to];

		throw new Exception( "$to is not defined in routing map");
	}
	
	static public function genProc( $where )
	{
		if ( isset(self::$proc_map[$where]) )
			return 'to/'.$where;
		throw new Exception( "$where is not defined in process map");
	}
	
	static public function toProc( $to )
	{
		if ( isset(self::$proc_map[$to]) )
			return self::$proc_map[$to];
		throw new Exception( "$to is not defined in process map");
	}
}


// Dispatch json processors
if ( preg_match( '#^/to/([a-zA-Z0-9_]+).*$#', $_SERVER['REQUEST_URI'], $m) )
{
	$to = Routing::toProc($m[1])[0];
	if (! $to ) //It's not JSON
	{
// 		BUG
		$_SESSION['context']->setContext('http');
		require './libcc/'.$to;
	}
	else {
		$_SESSION['context']->setContext('json');
		$_SESSION['context']->setHeaders();
		require './libcc/'.$to;
		$_SESSION['context']->setContext('http'); die();
	}
	die();
}



function issetted($var) {
	return isset($var) ? $var : false;
}

if (isset($_REQUEST['red']))
{
	$redirect = $_REQUEST['red'];
	switch ( $redirect ) {
		case 'source_code':	require 'libcc/common/view_source_code.php'; die();
		case 'test_case_input': require 'libcc/common/view_test_case_input.php'; die();
		case 'test_case_output': require 'libcc/common/view_test_case_output.php'; die();
		case 'test_case_true_output': require 'libcc/common/view_test_case_true_output.php'; die();
		case 'compile_error':	require 'libcc/common/view_compile_error.php'; die();
	}
}

@$inc = basename( $_SERVER['REQUEST_URI'] );
if (isset($_REQUEST['show'])) $inc=$_REQUEST['show'];
if (! is_null($inc) && ! empty($inc) && preg_match('#^/([a-zA-Z_0-9]+).*#', $_SERVER['REQUEST_URI'], $m) )
{
	$inc = $m[1];
	switch ($inc) {
	    case 'signin':	$req_path="./forms/common/signin.php"; break;
	    case 'home': $req_path="./layout/home.php"; break;
	    case 'signup': $req_path="./forms/common/signup.php"; break;
	    case 'signout': signOut(); header("Location: ".__url__); break;
	    case 'help': $req_path="./static/F1.htm"; break;
	    case "sgl": $req_path="./layout/single_exercise.php"; break;
	    case "sri":  $req_path="./layout/seri_exercise.php"; break;
	    case "exercise": $req_path="./layout/single_exercise.php"; break;
	    case "seri":  $req_path="./layout/seri_exercise.php"; break;
	    case "course":  $req_path="./layout/course.php"; break;
	    case "profile": $req_path="./layout/profile.php"; break;
	    case 'announcement': $req_path="./layout/notice.php"; break;
	    default:
	        $not_target="";
		    if ( hasPrivilege("s") )
				switch($inc) {
				       case "courses": $req_path="./forms/student/courses.php"; break;
				       case "exercises":$req_path="./forms/student/exercises.php"; break;
				       default: $not_target.="s"; break;
			       }
		    if( hasPrivilege("t") )
				switch($inc) {
				       case "add_exercise":$req_path="./forms/teacher/add_exercise.php"; break;
				       case "add_exercise_proc":$req_path="./libcc/teacher/add_exercise.php"; break;
				       case "courses": $req_path="./forms/teacher/courses.php"; break;
				       case "exercises":$req_path="./forms/teacher/exercises.php"; break;
				       case "logs": $req_path="./forms/teacher/logs.php"; break;
				       case "membership_requests": $req_path="./forms/teacher/membership_request.php"; break;
				       case "students": $req_path="./forms/teacher/students.php"; break;
				       default: $not_target.="t"; break;
			       }
		    if( hasPrivilege("a") )
				switch($inc) {
				       case "signin": $req_path="./forms/admin/signin.php"; break;
				       case "teachers": $req_path="./forms/admin/teachers.php"; break;
				       default: $not_target.="a"; break;
			       }

		    if ( hasPrivilege('*') )
				switch ($inc) {
				       case "compiler": $req_path="./forms/common/compiler.php"; break;
				       default:
							$not_target.='*'; break;
							break;
			       }
			if ( ( $not_target == "sta" ) or empty( $req_path ) )
				$req_path = './layout/main.php';
	        break;
	}
}
else {
	if ( hasPrivilege("*") )
		$req_path="./layout/home.php";
	else
		$req_path="./layout/main.php";
}
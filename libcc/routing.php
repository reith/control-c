<?php
class Routing {
	static private $map = array (
		'$lang' => array (
			'GET' => 'index',
			'course' => array (
				'GET' => 'course/index',
				'search' => array (
					'GET' => 'course/search'
				),
				'$id' => array (
					'GET' => 'course/view'
				),
			),
			'exercise' => array (
				// 'GET' => 'exercise/index',
				'search' => array (
					'GET' => 'exercise/search_input',
					'POST' => 'exercise/search_post'
				),
				'$id' => array (
					'GET' => 'exercise/view',
					'stats' => array (
						'GET' => 'exercise/stats'
					)
				)
			),
			'announcement' => array (
				'GET' => 'announcement/index',
				'$id' => array (
					'GET' => 'announcement/view'
				)
			),
			'user' => array (
				'$id' => array (
					'GET' => 'user/view'
				)
			),
			'problemset' => array (
				// 'GET' => 'problemset/index',
				'$id' => array (
					'GET' => 'problemset/view',
					'edit' => array (
						'GET' => 'problemset/view' //Bacckbone routing
					),
					'stats' => array (
						'GET' => 'problemset/stats'
					)
				)
			),
			/*
			 * 'signup' => array (
			 *     'GET' => 'signup/view_form',
			 *     'POST' => 'signup/process_from'
			 * ),
			 */
			'login' => array (
				'GET' => 'account/login_input',
				'POST' => 'account/login_post'
			),
			'logout' => array (
				'GET' => 'account/logout'
			)
		 )
	);

	static private $mapptr = null;
	static private $params = array();
	static private $url;

	static public function currentUrl() {
		return self::$url;
	}

	static public function validateToken( $url ) {
	/*
	 * returns true if get value is matched.
	 * return false if get value not matched.
	 * return name of placeholder if gotten value is matched against it
	 */

		if( is_null( self::$mapptr ) )
			self::$mapptr = self::$map;

		// echo 'statring from '.key( self::$mapptr );
		do {
			if( ( key( self::$mapptr ) === $url ) ) {
				self::$mapptr = current( self::$mapptr );
				return true;
			} else if( substr( key( self::$mapptr ), 0, 1) === '$' ) {
				$ret = substr( key( self::$mapptr ), 1 );
				self::$params[ $ret ] = $url;
				self::$mapptr = current( self::$mapptr );
				return $ret;
			}
		} while( next( self::$mapptr ) !== FALSE );
		self::$mapptr = null;
		return false;
	}

	static public function getAction( $url, $method ) {
		self::$mapptr = null;

		while( preg_match( '#^/?([^/]+)#', $url, $match ) ) {
			// echo 'preoccessing '.$match[1]."\n";
			if(! self::validateToken( $match[1] ) )
				break;
			$url = preg_replace( '#^/?(?:[^/]+)/?#', '', $url );
		}
		if( !empty( $url ) ) {
			return false;
		}
		if( $action = @self::$mapptr[$method] )
			return $action;
		return false;
	}

	static public function route( $url, $method, Context $ctx ) {
		if( isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') )
			$ctx->setContext('json');

		$url = explode( '?', $url, 2 )[0];
		$action = self::getAction( $url , $method );
		if( $action === false ) {
			// redirect to farsi when locale is not detected.
			if( $url === '/' ) {
				redirect302('/fa');
			}
		}
		if( $action === false ) {
			return false;
		}
		if( self::$params ) {
			$ctx->setRequestData( self::$params );
		}
		if( self::setLocale( self::$params['lang'], $ctx ) ) {
			self::$url = $url;

			return $action;
		}

		return false;
	}

	static private function setLocale( $lang, Context $ctx ) {
		
		switch( $lang ) {
			case 'fa':
				setlocale(LC_ALL, 'fa_IR'); 
				break;
			case 'en':
				setlocale(LC_ALL, 'en_US'); 
				break;
			default:
				return false;
		}
		bindtextdomain('cc', 'locale/');
		$_SESSION['locale'] = $lang; //Don't remove this. things will break.
		$ctx->setLocaleFormatter( new LocaleFormatter( $lang ) );
		return true;
	}

	static public function url( $cat, array $params = null, $auto_locale = true ) {
	/*
	 * if $auto_locale be true, $cat didn't need locale. session value used.
	 * $params is array indexed by placeholders name without `$`
	 * placeholders name are gotten from $map. use `-` in passed $cat
	 * although it's not important
	 */
	 	$real_cat = $cat;
	 	if( $auto_locale ) {
			self::$mapptr = self::$map['$lang'];
			self::$mapptr = self::$map['$lang'];
			$url = '/'.$_SESSION['locale'];
		} else {
			self::$mapptr = self::$map;
			$url = '';
		}

		while( preg_match( '#^/?([^/]+)#', $cat, $match ) ) {
			$vtres =  self::validateToken( $match[1] );
			if(! $vtres )
				break;
			$url .= '/';
			if( $vtres === true )
				$url .= $match[1];
			else if(is_null( $p = $params[ $vtres ] ) ) {
				Throw new Exception( "bad cat $real_cat" );
				return false;
			} else
				$url .= $p;

			$cat = preg_replace( '#^/?(?:[^/]+)/?#', '', $cat );
		}
		if( !empty( $cat ) ) {
			Throw new Exception( "bad cat $real_cat" );
			return false;
		}

		return $url;
	}


	static public function tkel( $type, $word, $id=null ) {
	/*
	 * translate known entity link
	 * $type: problemset, exercise, user, course
	 * $id: $types's subject id
	 * $word: text to show
	 */
	 	switch( $type ) {
			case 'problemset':
			case 'user':
			case 'course':
			case 'exercise':
			case 'announcement':
				return sprintf( '<a href="%s">%s</a>', Routing::url( "$type/-", array( 'id' => $id) ), $word );
			case 'logout':
			case 'login':
			case 'signup':
				return sprintf( '<a href="%s">%s</a>', Routing::url( $type ), $word );
			default: throw new Exception('Unknown type: ', $type);
		}
	}

}

$action = Routing::route( $_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD'], $env );
if( $action )
	$env->setAction( $action );
else
	redirect404( $env );

return;

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
			/* SHOULD BE DEPRECATED */
			'profile' => '/profile',
		'user' => '/user',
		'problemset' => '/problemset',
		'exercise' => '/exercise',
		'home' => '/home',
		'exercise/search' => '/exercise/search/'
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
		'send_exercise' => array('/student/send_exercise.php', 0),
		'exercise_search' => array('../action/exercise/search.php', 1)
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
		$env->setContext('http');
		require './libcc/'.$to;
	}
	else {
		$env->setContext('json');
		$env->setHeaders();
		require './libcc/'.$to;
		$env->setContext('http'); die();
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


if( isset( $_REQUEST['sec'] ) ) {
	switch ($_REQUEST['sec']) {
		case 'exercise':
			switch ($_REQUEST['act']) {
				case 'view_search': $env->setLayout('exercise/search'); break;
			}
			break;
	}
	return;
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
	    case "sgl":
	    case "exercise": $env->setAction('exercise/view'); break;
	    case "sri":
	    case "problemset":  $req_path="./layout/problemset.php"; break;
	    case "course":  $env->setAction('course/view'); break;
	    case "profile": $req_path="./layout/profile.php"; break;
	    case 'announcement': $env->setAction('announcement/view'); break;
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

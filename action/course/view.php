<?php
require 'libcc/course.php';
require_once 'libcc/date.php';

$request_filter = array(
	'id' => FILTER_VALIDATE_INT
);
$rd = filter_var_array( $env->rd(), $request_filter );
if( !$rd || !isset( $rd['id'] ) ) {
	redirect404( $env );
	return;
}

$t = array();
$course = get_course( $rd['id'] );

if( $course ) {
	$t['course'] = $course;
	$t['course']['semester'] = $env->locale()->date( $course['createDate'], 'YYYY, MMM' );
} else {
	redirect404( $env );
}

if( $env->isJSON() )
	echo ( json_encode(($t['course']) ) );
else {
	$env->setData('title', $t['course']['name']);
	$env->setLayout('course/view');
}

?>

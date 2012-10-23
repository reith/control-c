<?php
/*
 * controller for exercise statistics
 */
if(! $env->isJSON() ) {
	redirect404($env);
	return;
}

require_once 'libcc/exercise.php';
$rdfilter = array(
	'id' => FILTER_VALIDATE_INT
);
$rd = filter_var_array( $env->rd(), $rdfilter );
if( !$rd || !isset($rd['id']) ) {
	redirect404( $env );
	return;
}

$stats = get_exercise_stats( $rd['id'] );
echo json_encode(array('grades'=>$stats));


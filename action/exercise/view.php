<?php
require_once 'libcc/db.class.php';
require 'libcc/exercise.php';

$request_filter = array(
		'id' => FILTER_VALIDATE_INT
		);
$rd = filter_var_array( $env->rd(), $request_filter );
if( !$rd || !isset( $rd['id'] ) ) {
	redirect404( $env );
	return;
}

$stmt = get_exercise( $rd['id'] );
$exercise = $stmt->fetch( PDO::FETCH_ASSOC );
if( is_null( $exercise['id'] ) ) {
	redirect404( $env );
	return;
}

$t['e'] = $exercise;
if ($env->isHTML()) {
	$env->setLayout( 'exercise/view' );
	$env->setData('title', $env->locale()->number($exercise['id']) .' - '. $exercise['title']);
}
if ($env->isJSON()) {
	die(json_encode ($exercise) );
};
?>

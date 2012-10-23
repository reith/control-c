<?php
/**
 * controller for problemset statistics
 */


if(! $env->isJSON() ) {
	redirect404($env);
	return;
}

require_once 'libcc/problemset.php';

$rd_filter = array(
	'id' => FILTER_VALIDATE_INT
);

$rd = filter_var_array( $env->rd(), $rd_filter );
if( !$rd || !isset($rd['id']) ) {
	redirect404( $env );
	return;
}

$statistics = get_problemset_stats( $rd['id'] );
echo json_encode(array('grades'=>$statistics) );
?>

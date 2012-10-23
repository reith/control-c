<?php
require 'libcc/announcement.php';
$rd_t = array (
	'id' => FILTER_VALIDATE_INT
);
$rd = filter_var_array( $env->rd(), $rd_t );
if( !$rd || !isset($rd['id'] ) ) {
	redirect404( $env );
	return;
}
$announcement = get_custom_announcement( $rd['id'] );
if( !is_null($announcement['id'] ) ) {
	$t['res'] = $announcement;
	$env->setData('title', $announcement['title']);
	$env->setLayout('announcement/view');
} else {
	redirect404( $env );
}
?>

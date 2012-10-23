<?php
/**
 * controller for problemset profile
 */

require_once 'libcc/problemset.php';

$rdfilter = array(
	'id' => FILTER_VALIDATE_INT
);
$rd = filter_var_array( $env->rd(), $rdfilter );
if( !$rd || !isset($rd['id']) ) {
	redirect404( $env );
	return;
}
$problemset = get_problemset($rd['id']);
if(false === $problemset) {
	redirect404( $env );
	return;
}

$problemset['createDate'] = $env->locale()->date($problemset['createDate']);
$problemset['deadlineDate'] = $env->locale()->date($problemset['deadlineDate']);
$problemset['checkDate'] = $env->locale()->date($problemset['checkDate']);

$t = array();
if($env->isHTML()) {
	$t['problemset'] = $problemset;
	$env->setData('title', sprintf('%s %s', _('Problemset'), $env->locale()->number($problemset['id'])));
	$env->setLayout('problemset/view');
}
?>

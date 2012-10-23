<?php
require_once 'libcc/user.php';
$request_filter = array(
	'id' => FILTER_VALIDATE_INT
);
$rd = filter_var_array( $env->rd(), $request_filter );
if( !$rd || !isset( $rd['id'] ) ) {
	redirect404( $env );
	return;
}

$t = array();
$user = get_user( $rd['id'] );

if( $user ) {
	$t['user'] = $user;
	$t['user']['lastLoginDate'] = $env->locale()->date( $t['user']['lastLogin'] );
} else {
	redirect404( $env );
}

if( $env->isJSON() )
	echo ( json_encode(($t['user']) ) );
else {
	$env->setData('title', $t['user']['username']);
	$env->setLayout('user/view');
}


?>

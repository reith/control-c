<?php

$PHPERROR = array(
		E_ERROR           => 'error',
		E_WARNING         => 'warning',
		E_PARSE           => 'parsing error',
		E_NOTICE          => 'notice',
		E_CORE_ERROR      => 'core error',
		E_CORE_WARNING    => 'core warning',
		E_COMPILE_ERROR   => 'compile error',
		E_COMPILE_WARNING => 'compile warning',
		E_USER_ERROR      => 'user error',
		E_USER_WARNING    => 'user warning',
		E_USER_NOTICE     => 'user notice');

if(defined('E_STRICT'))
$PHPERROR[E_STRICT] = 'runtime notice';

$ERROR=array(
		'SIGNIN'=>_("You must login again"),
		'E404' => _("The page yo requested is not found"),
		'PREVILEGE' => _("You have NOT sufficient privilege to do this"),
		'BADDATA'=> _("Data received is invalid"),
		'SECCODE'=> _("Security Code you entered is not true"),
		'DB'=> _("Error in Database connection"),
		'EMPFRM'=> _("Please fill the form"),
		'PSWCFR'=> _("Password is not same as repeated password"),
		'DB_QUERY'=> _("Error in Database queries").'<br />'._("Error had been logged and probaby be fixed soon"),
		'AUTH'=> _("Authentication failed"),
		'NOCHANGE'=> _("No change made"),
		'FILEEXT' => _("Exercise file exists"),
		'LOCKED' => _("Your account had been locked")
		);
?>

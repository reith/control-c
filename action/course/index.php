<?php
	require_once 'libcc/course.php';
	require_once 'libcc/general.functions.php';

	if ($env->isJSON()) {
		$env->setHeaders();
		die(json_encode(Course::queryAllSummaryAsRows()));
	}
	Routing::notFound();
?>

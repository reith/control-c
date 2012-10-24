<?php
$env->setData('title', '^C: '._('Judging till breakpoint'));
$env->setLayout('index');

require_once 'libcc/announcement.php';
$t['announcements'] = get_newest_announcements(12);
foreach ($t['announcements'] as &$announcement)
	$announcement['date'] = $env->locale()->date($announcement['time']);



?>

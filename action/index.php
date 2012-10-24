<?php
$env->setData('title', '^C: '._('Judging till breakpoint'));
$env->setLayout('index');

require_once 'libcc/announcement.php';
$t['announcements'] = formalize_announcements(
	get_newest_announcements(12), $env->locale()
);

?>

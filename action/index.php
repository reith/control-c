<?php
require_once 'action/stats/view.php';
//XXX: override layout and title setted bye above require

require_once 'libcc/announcement.php';
$t['announcements'] = formalize_announcements(
	get_newest_announcements(10), $env->locale()
);

$env->setData('title', '^C: '._('Judging till breakpoint'));
$env->setLayout('index');
?>

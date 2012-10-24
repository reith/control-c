<?php
require_once 'libcc/announcement.php';
$t['announcements'] = get_newest_announcements( 50 );
foreach ($t['announcements'] as &$announcement)
	$announcement['date'] = $env->locale()->date($announcement['time']);
	
$env->setLayout( 'announcement/index' );
$env->setData( 'title', _('Announcements') );

?>

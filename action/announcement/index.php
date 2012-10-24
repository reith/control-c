<?php
require_once 'libcc/announcement.php';
$t['announcements'] = formalize_announcements(
	get_newest_announcements(50), $env->locale()
);

$env->setLayout( 'announcement/index' );
$env->setData( 'title', _('Announcements Board') );

?>

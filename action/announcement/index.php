<?php
require_once 'libcc/announcement.php';
$t['announcements'] = get_newest_announcements( 50 );
$env->setLayout( 'announcement/index' );
$env->setData( 'title', _('Announcements') );

?>

<!--TRANSLATED -->

<div id="leftMenu">
<?php
require_once 'libcc/general.functions.php';

if (signedIn()) {
    echo "<ul>\n".'<li><a href="/'.$env->locale()->name().'/compiler" >'._("Online Compiler").'</a></li>';
    if ( hasPrivilege('s') || hasPrivilege('t') )
      echo '<li><a href="/'.$env->locale()->name().'/exercises" >'._("Exercises").'</a></li>'.
	   '<li><a href="/'.$env->locale()->name().'/courses" >'._("Courses").'</a></li>';
    if (hasPrivilege('t'))
      echo '<li><a href="/'.$env->locale()->name().'/students" >'._("Students").'</a></li>'.
           '<li><a href="/'.$env->locale()->name().'/logs" >'._("Students Logs").'</a></li>'.
           '<li><a href="/'.$env->locale()->name().'/membership_requests" >'._("Membership Requests").'</a></li>';
    if  (hasPrivilege('a'))
       echo '<li><a href="/'.$env->locale()->name().'/teachers" >'._('Manage Teachers').'</a></li>';

    echo "</ul>\n";
}
?>
</div>

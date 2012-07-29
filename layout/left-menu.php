<!--TRANSLATED -->

<div id="leftMenu">
<?php
if (!signedIn())
{
    $login_image_link='/layout/img/login-link-fa.png';
    $news_image_link='/layout/img/news-link-fa.png';
    if ( $_SESSION['locale']=='en' )
    {
        $login_image_link='/layout/img/login-link-en.png';
        $news_image_link='/layout/img/news-link-en.png';   
    }
    echo (
      "<a class='imageLink' href='".Routing::genURL('home')."'><img src='$login_image_link' alt='login' ></img></a>".
      "<a class='imageLink' href='".Routing::genURL('announcement')."'><img src='$news_image_link' alt='view news' ></img></a>"
	 );
}
else
{
    echo "<ul>\n".'<li><a href="'.__url__.'/compiler" >'._("Online Compiler").'</a></li>';

    if ( hasPrivilege('s') || hasPrivilege('t') )
      echo '<li><a href="'.__url__.'/exercises" >'._("Exercises").'</a></li>'.
	   '<li><a href="'.__url__.'/courses" >'._("Courses").'</a></li>';
    if (hasPrivilege('t'))
      echo '<li><a href="'.__url__.'/students" >'._("Students").'</a></li>'.
           '<li><a href="'.__url__.'/logs" >'._("Students Logs").'</a></li>'.
           '<li><a href="'.__url__.'/membership_requests" >'._("Membership Requests").'</a></li>';
    if  (hasPrivilege('a'))
       echo '<li><a href="'.__url__.'/teachers" >'._('Manage Teachers').'</a></li>';

    echo "</ul>\n";
}
?>
</div>

<?php
// FIXME: Not fully automated routing generated. just fix Farsi and English link.
/*
	DATE ROW
*/
	echo '<ul><li>';
        if (signedIn())
			printf('[ %s ]</li><li>[ %s ] </li>', Routing::tkel('user', $_SESSION['un'], $_SESSION['id'] ),
				Routing::tkel('signout', _('Sign out') )
			);
		printf('[ %s ]</li>', Routing::tkel('login', _('Login') )
			/*
			 * Routing::tkel('signup', _('Register') )
			 */
		);

    $datestr = $env->locale()->date( time(), "E d MMM,");
    if ($env->locale()->name() == 'en' )
		printf( " | <li>[ <a href='%s'>فارسی</a> ]</li>", preg_replace('#^/en#', 'fa', Routing::currentUrl() ) );
	else 
		printf( " | <li>[ <a href='%s'>English</a> ]</li>", preg_replace('#^/fa#', 'en', Routing::currentUrl() ) );
	echo "</li></ul>";

	printf ("<span id='serverTime' >%s: %s <span id='time' init='%s' ></span></span>",
             _("Server time"), $datestr, time() );
?>
<script type="text/javascript">

require(['i18n!nls/formatters'], function(F) {
	var timeElem = document.getElementById("time");
	var t = parseInt(timeElem.getAttribute("init"))*1000;

	var d=new Date();
	d.setTime(t);
	d.toTimeString = function() {
	<?php
    	echo "var str=F._number(d.getUTCHours())+':'+F._number(d.getUTCMinutes())+':'+F._number(d.getUTCSeconds());";
	?>
    
	return str;
	};

	timeElem.innerHTML=d.toTimeString();
	setInterval(function () {t+=1000; d.setTime(t); timeElem.innerHTML=d.toTimeString();}, 1000);
});
</script>

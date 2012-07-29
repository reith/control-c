<?php
// TRANSLATED
/*
	DATE ROW
*/
	require_once __root__."/libcc/date.php";
	require_once __root__."/libcc/formating.php";

	date_default_timezone_set('Asia/Tehran');
	echo "<ul><li>";
        if (signedIn())
            printf ("[ <a href=\"".__url__."/profile/{$_SESSION['id']}\">~{$_SESSION['un']}</a> ]</li> | "
                    ."<li>[ <a href='".__url__."/signout'>%s</a> ]", _("Sign out"));
        else
            printf ("[ <a href=\"".__url__."/signin\">%s</a> ]</li> | <li>[ <a href=\"".__url__."/signup\">%s</a> ]</li>", _("Login"), _("Register"));

	$date= date("Ymdw");
        if ($_SESSION['locale'] == 'en' )
	{
            $datestr=date("D, j M Y");
	    echo " | <li>[ <a href='".__url__."/fa'>فارسی</a> ]</li>";
	}
        else
	{
	    echo " | <li>[ <a href='".__url__."/en'>English</a> ]</li>";
            $datestr=transNumber(gregorian_to_jalali(substr($date, 0, 4), substr($date, 4, 2), substr($date, 6, 2), $date[8]));
	}
	echo "</li></ul>";

	printf ("<span style='direction: %s;'>%s: %s <span id='time' init='%s' ></span></span>",
            $_SESSION['dir'], _("Server time"), $datestr, date('U'));
?>
<script type="text/javascript">
(function() {
var timeElem=document.getElementById("time");
var t=parseInt(timeElem.getAttribute("init"))*1000;

var d=new Date();
d.setTime(t);
d.toTimeString = function() {
<?php
    echo "var str=Locale.digit(d.getUTCHours())+':'+Locale.digit(d.getUTCMinutes())+':'+Locale.digit(d.getUTCSeconds());";
?>
    
  return str;
};

timeElem.innerHTML=d.toTimeString();
setInterval(function () {t+=1000; d.setTime(t); timeElem.innerHTML=d.toTimeString();}, 1000);
})(1)
</script>
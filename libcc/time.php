<?php
require "./config.php";
header ("Cache-Control: no-cache, must-revalidate");

// date_default_timezone_get("UTC");
date_default_timezone_get("Asia/Tehran");
printf("<input id='tw' type='text' value='%d'></input>", date("U"));
?>
<script type="text/javascript">
var t=parseInt(document.getElementById('tw').value)*1000;
var d = new Date(t);
alert (d.getHours()+':'+d.getMinutes()+':'+d.getSeconds());
</script>

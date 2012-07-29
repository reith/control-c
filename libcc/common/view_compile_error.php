<?php
$exNum = $_REQUEST['exercise'];
$seriId = $_REQUEST['seri'];

header ('Content-type:	text');
readfile(__exercises_path__."/$seriId/{$_SESSION['id']}/out/$exNum.error");
?>
<?php
$exNum = $_REQUEST['exercise'];
$seriId = $_REQUEST['seri'];
header ('Content-type:	text');

$path = file_get_contents(__exercises_path__."/$seriId/{$_SESSION['id']}/out/$exNum.filename");
readfile(__exercises_path__."/$seriId/{$_SESSION['id']}/".basename(rtrim($path)));
?>
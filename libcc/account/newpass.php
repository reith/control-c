<?php
require "../config.php";

echo md5($_REQUEST['pass'].$md5key);
?>

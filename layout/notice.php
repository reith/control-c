<?php
// require "date.php";
require 'libcc/db.class.php';

$errors="";
$notice=(int)$_REQUEST['id'];
if (! $notice)
{
	getTopNotices(50);
}
else {
    
$con = DB::instance();
($res=$con->query('call get_announcement('.$notice.')')) || errorLogger('DB', $con->error, true);

list($notice, $title, $body, $date) = $res->fetch(PDO::FETCH_NUM);
$jdate=jalaliLongDate($date);

  echo<<<EOF
  <fieldset>
    <legend>
      $title
    </legend>
    <div>
      $body
    </div>
  </fieldset>
  <div class="comment">اضافه شده در $jdate</div>
EOF;
}
?>
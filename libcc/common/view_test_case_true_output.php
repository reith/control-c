<?php
$exNum = $_REQUEST['exercise'];
$seriId = $_REQUEST['seri'];
header ('Content-type:	text');
$dp = __exercises_path__."/$seriId/data";
$d = dir($dp);

while ( false !== ($ent=$d->read()) )
{
  if (preg_match("/$exNum\.(\d+)\.out/", $ent, $matches))
  {
    echo "Test-case Number {$matches[1]} true output:\n";
    readfile("$dp/{$matches[0]}");
    echo "\n\n";
  }
}
?>
<?php
// REITH: PROCESS FOR REFREASH VIEW COURSE TEACHER'S FEATURE
signinFirst('t', true);
require_once "libcc/formating.php";

$output=array("tr"=>array('id'=>array(), 'r'=>array())); 


$course = $_GET['course'];
$sort = $_GET['sort'];
$order = $_GET['order'];
$from = $_GET['from'];
$limit = $_GET['limit'];

$errors="";
if ($dbRes=mysqlres(getMembershipRequestsList, "s-sdd", $course, $sort, $order, $from, $limit))
{
  $output['th']="<th>درخواست</th><th>نام دانشجو</th><th>شماره</th><th>پست الکترونیک</th><th>نام درس</th><th>تایید</th>";
  if ($output['count']=$dbRes->num_rows)
    while($row=$dbRes->fetch_row()){
      $output['tr']['id'][]=$row[0];
      $output['tr']['r'][]="<td>".transNumber($row[0])."</td><td>$row[1]</td><td>".transNumber($row[2])."</td><td>$row[3]</td><td>$row[4]</td>";
    }
}

$output['error']=$errors;
echo json_encode($output);
?>
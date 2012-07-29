<?php

$output=array("count"=>0, "tr"=>array()); 
require "libcc/formating.php";
require_once "libcc/date.php";

signinFirst('t', true);

$view = $_GET['view'];
$course = $_GET['course'];
$sort = $_GET['sort'];
$order = $_GET['order'];
$from = $_GET['from'];
$limit = $_GET['limit'];
$errors="";


switch ($view)
{
case "seri": $output["th"]="<th>نام</th><th>سری</th><th>درس</th>"; $output["type"]='seri'; break;
case "single": $output["th"]="<th>نام</th><th>تمرین</th><th>سری تمرین</th><th>درس</th>"; $output["type"]='single'; break;
default: $output["error"]=$ERROR['BADDATA'];
}


if (empty($output["error"]) && $dbRes=mysqlres('getLogsList', "-s-sdd", $view, $course, $sort, $order, $from, $limit))
{
  if (!$obj=$dbRes->fetch_object())
    $output['count']=0;
  else
  {
  $output['count']=$dbRes->num_rows;
  do
  {
    $row="<td>$obj->studentName</td>";

    if ($view=="single")
      $row.="<td>".transNumber($obj->number)."</td>";

    $row.="<td>".transNumber($obj->seriNum)."</td><td>$obj->courseName</td>";
    $id= ($view=="single") ? $obj->exerciseId : $obj->seriId;

    $output['tr'][]=array ('s'=> $obj->studentId, 'id'=>$id, 'r'=> $row);      
    } while($obj=$dbRes->fetch_object());
  }
}
!empty($errors) && $output['error']=$errors;
echo json_encode($output);
?>

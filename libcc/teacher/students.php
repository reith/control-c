<?php
// PROCESS FOR TEACHER REFREASH VIEW COURSE

require "libcc/formating.php";
require "libcc/date.php";

signinFirst ('t', true);
$output=array("count"=>0, "tr"=>array(), "th");

$course = $_GET['course'];
$sort = $_GET['sort'];
$order = $_GET['order'];
$from = $_GET['from'];
$limit = $_GET['limit'];

if ($dbRes=mysqlres('getCourseMembersList', "--sdd", $course, $sort, $order, $from, $limit))
{
   $profile_url = Routing::genURL('profile');
  if ($output['count']=$dbRes->num_rows)
  {
    $output["th"]="<th>شماره دانشجویی</th><th>نام دانشجو</th><th>نام درس</th><th>نمره</th><th>جزئیات</th>";
    while($obj=$dbRes->fetch_object())
    {
      $r="";
      $r.="<td>".transNumber($obj->number)."</td>".
	     "<td>$obj->sName</td><td>$obj->cName</td><td>$obj->gradeAverage</td>";
      $r.="<td><a href='".$profile_url."/$obj->sID'>نمایش</a></td>";
      $output["tr"][]=array('r'=>$r, 'id'=>$obj->sID, 'cid'=>$obj->cID);
    }
  }
}

echo json_encode($output);
?>
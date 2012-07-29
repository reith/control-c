<?php
// PROCESS FOR TEACHER REFREASH VIEW COURSE
require "libcc/formating.php";

signInFirst('t');

$view = $_GET['view'];
$sort = $_GET['sort'];
$order = $_GET['order'];
$from = $_GET['from'];
$limit = $_GET['limit'];
$output=array('tr'=>array());


if ($dbRes=mysqlres('getCoursesList', null, $view, $sort, $order, $from, $limit))
{
  $profile_url = Routing::genURL('profile');
  $course_url = Routing::genURL('course');
  if (($output['count']=$dbRes->num_rows)>0)
  {
    $output['th']="<th>شناسه</th><th>نام درس</th><th>استاد درس</th><th>سال</th><th>وضعیت</th><th>جزئیات</th>";
    while($row=$dbRes->fetch_row())
    {
      $row[4] = $row[4] ? "بسته":"باز";
      $r = "<td>".transNumber($row[0])
      ."</td><td>$row[1]</td><td><a href=\"".$profile_url."/$row[5]\">$row[2]</a></td><td>".transNumber($row[3])
      ."</td><td>$row[4]</td><td><a href=\"".$course_url."/$row[0]\">نمایش</a></td>";
      $output['tr'][] = array(
          'r' => $r, 'id' => $row[0]
      );
    }
  }
}

echo json_encode($output);
   
?>
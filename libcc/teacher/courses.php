<?php
// PROCESS FOR TEACHER REFREASH VIEW COURSE
require_once 'libcc/general.functions.php';

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
    $output['th']=sprintf("<th>%s</th><th>%s</th><th>%s</th><th>%s</th><th>%s</th><th>%s</th>", 
    	_("ID"), _("Course"), _("Teacher"), _("Year"), _("Status"), _("Details"));
    while($row=$dbRes->fetch_row())
    {
      $row[3] = $env->locale()->date($row[3], 'YYYY');
      $row[4] = $row[4] ? "بسته":"باز";
      $r = "<td>".$env->locale()->number($row[0])
      ."</td><td>$row[1]</td><td><a href=\"".Routing::url('user/view', array('id' => $row[5]), true)."\">$row[2]</a></td><td>"
      .$row[3]."</td><td>$row[4]</td><td><a href=\"".$course_url."/$row[0]\">نمایش</a></td>";
      $output['tr'][] = array(
          'r' => $r, 'id' => $row[0]
      );
    }
  }
}

echo json_encode($output);
   
?>

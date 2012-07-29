<?php
// PROCESS FOR TEACHER REFREASH VIEW COURSE

require 'libcc/formating.php';
require 'libcc/date.php';
require 'libcc/db.class.php';

signinFirst('t', true);

$view = $_GET['view'];
$course = $_GET['course'];
$sort = $_GET['sort'];
$order = $_GET['order'];
$from = (int)$_GET['from'];
$limit = (int)$_GET['limit'];

$output=array("tr"=>array());

switch ($view)
{
	case "single": $output['th'] = '<th>'._('Exercise').'</th>'; break;
	case "seri": $output['th'] = ""; break;
	default: dieJSON('BADDATA');
}

$output['th'].=sprintf('<th>%s</th><th>%s</th><th>%s</th>', _('Seri'), _('Creation Date'), _('Deadline Date'));

switch ($course)
{
	case "all": $output['th'].=sprintf('<th>%s</th><th>%s</th><th>%s</th>', _('Teacher Name'), _('Course Name'), _('Expire'));
		    break;
	case "own": $output['th'].=sprintf('<th>%s</th>', _('Course Name'));
	default: $output['th'].=sprintf('<th>%s</th><th>%s</th>',_('Lock'), _('Expire'));
		 break;
}

$output['th'] .= '<th>'._('Details').'</th>';
$rowFormat = "";
$query = "SELECT `es`.seri, `es`.createDate, `es`.deadlineDate, CONCAT( `u`.firstName, ' ', `u`.lastName ), `c`.name, `es`.`lock`,".
' IF (NOW() >`es`.deadlineDate, 1, 0) as `expire`, `es`.id , `e`.id, `e`.number'.
' FROM '.DB_EXERCISE_SERI_TABLE.' as `es`, '.DB_EXERCISE_TABLE.' as `e`, '.DB_COURSE_TABLE.' as `c`, '.DB_USER_TABLE.' as `u`'.
' WHERE `es`.course=`c`.id AND `c`.teacher=`u`.id AND `e`.seri=`es`.id ';

$qvars = array();
switch ($course) {
	case "all": break;
	case "own": $query.=" AND `u`.id={$_SESSION['id']}"; break;
	default: $query.=" AND `c`.id = ".(int)$course; break;
}

switch ($view) {
	case "single": $query.=" GROUP BY `e`.id"; break;
	case "seri": $query.=" GROUP BY `es`.id"; break;
	default: $badRequest=true;
}


$query.=" ORDER BY ";

switch($sort) {
	case "courseName": $query.="`c`.name"; break;
	case "seriNum": $query.="`es`.seri"; break;
	case "teacherF": $query.="`u`.firstName"; break;
	case "teacherL": $query.="`u`.lastName"; break;
	case "cDate": $query.="`es`.createDate"; break;
	case "dDate": $query.="`es`.deadlineDate"; break;
	case "expire": $query.="expire"; break;
	default: $badRequest=true;
}

switch ($order) {
    case 'ASC': $query .= ' ASC '; break;
    case 'DESC': $query .= ' DESC '; break;
    default:
        $badRequest = true;
        break;
}

$query.=" LIMIT ?, ?;";

if ( $badRequest )
	dieJSON('BADDATA');

$con = DB::instance();
$stmt = $con->prepare($query);
$stmt->bindParam(1, $from, PDO::PARAM_INT);
$stmt->bindParam(2, $limit, PDO::PARAM_INT);

if ( $stmt->execute() )
{
	$row_count = 0;
	while( $row = $stmt->fetch(PDO::FETCH_NUM) )
	{
		$row_count++;
		$rowFormat = '';
		if ($view=="single")
			$rowFormat.="<td>".transNumber($row[9])."</td>";

		$rowFormat.="<td>".transNumber($row[0])."</td><td>".jalaliDate($row[1])."</td><td>".jalaliDate($row[2])."</td>";
		//lock
		$row[5]=$row[5]?"بسته":"باز";
		//expire
		$row[6]=$row[6]?"بله":"خیر";
		switch ($course)
		{
			case "all": $rowFormat.="<td>$row[3]"."<td>$row[4]</td>"."<td>$row[6]</td>";
				    break;
			case "own": $rowFormat.="<td>$row[4]</td>"."<td>$row[5]</td>"."<td>$row[6]</td>";
				    break;
			default : $rowFormat.="<td>$row[5]</td>"."<td>$row[6]</td>";
		}
		switch ($view)
		{
			case "seri": $rowFormat.="<td><a href='".Routing::genURL('seri')."/$row[7]'>نمایش</a></td></tr>"; break;
			case "single": $rowFormat.="<td><a href='".Routing::genURL('exercise')."/$row[8]'>نمایش</a></td></tr>"; break;
		}

		$output['tr'][]=array('r'=>$rowFormat, 'id'=>$row[0]);
	}
}

$output['count']=$row_count;
if (!empty($errors))
	dieJSON($errors);
echo (json_encode($output));
?>

<?php
// REITH: VIEW COURSE PROCESS FOR STUDENTS

require_once 'libcc/formating.php';
require_once 'libcc/date.php';
require_once 'libcc/error_codes.php';
require_once 'libcc/general.functions.php';

signinFirst('s', true);

$view = $_GET['view'];
$solved = $_GET['solved'];
$expired = $_GET['expired'];
$course = $_GET['course'];
$sort = $_GET['sort'];
$order = $_GET['order'];
$from = $_GET['from'];
$limit = $_GET['limit'];

$errors="";
$output=array('tr'=>array());
$output['th'] = sprintf( '<tr><th>%s</th><th>%s</th>', _('ID'), _('Seri') );
switch ($view)
{
  case 'single': $output['th'].= sprintf("<th>%s</th>", _('Exercise') ); break;
  case 'seri': break;
  default: $errors.=$_ERROR['BADDATA'];
}

$output['th'].=sprintf("<th>%s</th><th>%s</th><th>%s</th><th>%s</th></tr>", _('Course'), _('Creation Date'), _('Deadline Date'), _('Expired') );
if (($dbRes=mysqlres('getStudentExercisesList', '---s-sdd', $view, $solved, $expired, $course, $sort, $order, $from, $limit)) && empty($errors))
{
  if (($output['count']=$dbRes->num_rows)>0)
  {
    $output['type']=$view;
    while ($obj=$dbRes->fetch_object())
    {
     $expire = $obj->expire ? _("Yes"):_("No");
     $id=($view=='seri')?$obj->seriID:$obj->exID;

     switch ($view)
     {
	case 'seri': $row=array('id'=>$id, 'seri'=>$obj->seri, 'name'=>$obj->name, 'cid'=>$obj->cid,
        'cdate'=>$env->locale()->date($obj->createDate), 'ddate'=>$env->locale()->date($obj->deadlineDate), 'expire'=>$expire, 'sid'=>$obj->seriID); break;
	case 'single': $row=array('id'=>$id, 'seri'=>$obj->seri, 'number'=>$obj->number, 'name'=>$obj->name, 'cid'=>$obj->cid,
        'cdate'=>$env->locale()->date($obj->createDate), 'ddate'=>$env->locale()->date($obj->deadlineDate), 'expire'=>$expire, 'sid'=>$obj->seriID); break;
     }
     $output['tr'][]=array('r'=>$row, 's'=>$obj->suID, 'g'=>$obj->seriGrade, 'e'=>$obj->expire, 'id'=>$id, 'm'=>$obj->confirm, 'c'=>$obj->check);
    }
  }
}
$output['error']=$errors;
echo json_encode($output);
?>

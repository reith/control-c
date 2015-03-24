<?php
// REITH: VIEW COURSE PROCESS FOR STUDENTS
require_once 'libcc/general.functions.php';
signinFirst ('s', true);

global $errors;
$errors="";
$view = $_GET['view'];
$sort = $_GET['sort'];
$order = $_GET['order'];
$from = $_GET['from'];
$limit = $_GET['limit'];

$output=array('tr'=>array());

if (($dbRes=mysqlres("getCoursesList", "--sdd", $view, $sort, $order, $from, $limit)) && empty($errors))
{
  if (($output['count']=$dbRes->num_rows)>0) {

    $output['th']=sprintf('<th>%s</th><th>%s</th><th>%s</th><th>%s</th><th>%s</th>', _('ID'), _('Course'), _('Teacher'), _('Year'), _('Status') );
    if ( $view=='all' )
      $output['th'].=sprintf('<th>%s</th>', _('Registeration') );

    while ($row=$dbRes->fetch_row()) {
      $row[3] = $env->locale()->date($row[3], 'YYYY');
      $row[4] = $row[4] ? _('Closed'):_('Open');

      if ( $view=='all' )
	switch($row[6]) {
            case 'w': $row[6]=_('Waiting for verification'); break;
            case 'j': $row[6]=_('Registered'); break;
	    case 'b': $row[6]=_('Doesn\'t verified'); break;
	    default: $row[6]=_('Doesn\'t Requested'); break;
          }


    $r=array( 'crsid'=>$row[0], 'crsn'=>$row[1], 'tchrn'=>$row[2], 'tchrid'=>$row[5], 'yr'=>$row[3], 'stat'=>$row[4] );
    if ($view == 'all')
      $r['rgst'] = $row[6];

    $output['tr'][]=array('r'=>$r, 'type'=>$view );
    }
  }
}

$output['error']=$errors;
echo json_encode($output);
?>

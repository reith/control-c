<?php
// REITH: VIEW COURSE PROCESS FOR STUDENTS
require 'libcc/formating.php';
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
      $row[4] = $row[4] ? _('Closed'):_('Open');

      if ( $view=='all' )
	switch($row[5]) {
            case 'w': $row[5]=_('Waiting for verification'); break;
            case 'j': $row[5]=_('Registered'); break;
	    case 'b': $row[5]=_('Doesn\'t verified'); break;
	    default: $row[5]=_('Doesn\'t Requested'); break;
          }


    $r=array( 'crsid'=>$row[0], 'crsn'=>$row[1], 'tchrn'=>$row[2], 'tchrid'=>$row[6], 'yr'=>$row[3], 'stat'=>$row[4] );
    if ($view == 'all')
      $r['rgst'] = $row[5];

    $output['tr'][]=array('r'=>$r, 'type'=>$view );
    }
  }
}

$output['error']=$errors;
echo json_encode($output);
?>
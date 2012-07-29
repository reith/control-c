<?php
//   REITH: PROCCESS STUDENT SIGNIN
require_once "libcc/date.php";
require_once "libcc/formating.php";
require_once 'libcc/db.class.php';

$student="";

if (isset($_POST["student"]))
  $student=(int)$_POST["student"];
else if ($_SESSION['gp']=='s')
  $student=$_SESSION['id'];
else
  dieJSON('BADDATA');

$id = $_POST["id"];
$idType = $_POST["type"];
$query="";
$dbRes="";


$output = array('re'=>array(), 's'=>null);


switch ($idType)
{
  case "single": $isSeri = false; break;
  case "seri": $isSeri = true; break;
  default: dieJSON('BADDATA');
}

$con = DB::instance();
$stmt = $con->prepare('call get_detailed_log(?,?,?)');
if (! $stmt ) {
  errorLogger('DB', $con->error."IN: ".$query);
  dieJSON('DB');
}
$stmt->bindParam(1, $id, PDO::PARAM_INT );
$stmt->bindParam(2, $student, PDO::PARAM_INT );
$stmt->bindParam(3, $isSeri, PDO::PARAM_BOOL );

$stmt->execute();
$no_rows = true;

while ($obj = $stmt->fetch(PDO::FETCH_OBJ) )
{
  $no_rows = false;
  if (! $isSeri )
  {
    $output['compileError']=$obj->compileError;
    $output['grade']=$obj->grade;
    $output['time']=$obj->time;
    $output['cheat']=$obj->cheat;
    $output['ex_num']=$obj->number;
    $output['seri']=$obj->seri;

    $number=$obj->runtimeError;
    $i=1;
    while ($number)
    {
     if ($token=($number&1))
       $output['re'][]=$i;
     $i++;
     $number>>=1;
    }
  }
  else
  {
    $output['time']=localeDate($obj->date, 'long');
    $output['grade']=$obj->grade;
    $output['zip']=$obj->zip;
  }
}

if ($no_rows)
	$output['s']=($idType=="seri")?_("No file received").'.':_("Doesn't graded yet").'.';

echo json_encode($output);
?>
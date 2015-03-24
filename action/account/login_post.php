<?php
require_once 'libcc/db.class.php';
require 'libcc/account.php';
require 'libcc/captcha/lib.php';
require_once 'libcc/general.functions.php';

$rdfilter = array(
	'un' => array( 'filter'=>FILTER_CALLBACK, 'options'=>'trim' ),
	'pw' => null,
	'captcha' => null
);

/*
 * $username=trim($_POST['un']);
 * $password=$_POST['pw'];
 * $captcha=$_POST['captcha'];
 */

$rd = filter_input_array( INPUT_POST, $rdfilter );

if( ($rd === false) || is_null( $rd ) || !isset( $rd['captcha'], $rd['un'], $rd['pw'] ) ) {
	dieJSON('EMPFRM');
}

global $error, $dbUserTable, $md5key, $siteURL, $privilege;

  unset($_SESSION['go']);

  if (isset ($_SESSION['failed_login'])) {
    if (strtoupper($rd['captcha']) != strtoupper($_SESSION["security_code"]))
    {
      regenerate_captcha();
      dieJSON('SECCODE');
    }
    else
    {
      unset ($_SESSION["security_code"]);
      unset ($_SESSION["failed_login"]);
    }
    
  }

  if (! $con = DB::instance() )
     dieJSON('DB');

  $stmt=$con->prepare("call login (?,?,@id,@prev)");

  $password=md5($rd['pw'].$md5key);
  $stmt->bindParam(1, $rd['un'], PDO::PARAM_STR);
  $stmt->bindParam(2, $password, PDO::PARAM_STR);
  
  $res=$stmt->execute();
  if (!$res) {
    errorLogger('DB_QUERY', 'Query: '.$query.'Error: '.$con->error);
    dieJSON('DB_QUERY');
  }
  
  list($id, $privilege) = $con->query('SELECT @id, @prev')->fetch(PDO::FETCH_NUM);

  if ( $id === -1 )
    dieJSON('LOCKED');
  else if (! $id ) {
    $_SESSION['failed_login']=true;
    regenerate_captcha();
    dieJSON('AUTH');
  }
  

  $_SESSION['id']=$id;
  $_SESSION['un']=$rd['un'];
  $_SESSION['prv']= preg_split('/,/', $privilege);

//DANGER: REMOVE IT LATER
  $_SESSION['gp']=$_SESSION['prv'][0];


  $output['go'] = isset ($_SESSION['go']) ? $_SESSION['go'] : Routing::genURL( "home" );
  unset($_SESSION['go']);
  
  $output['error']=$error;

  echo json_encode($output);

  function regenerate_captcha() {
    $_SESSION['security_code'] = str_rand(6, 'alnum');
  }
?>

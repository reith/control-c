<?php
require './libcc/db.class.php';
require './libcc/captcha/lib.php';

$username=trim($_POST['un']);
$password=$_POST['pw'];
$captcha=$_POST['captcha'];

global $error, $dbUserTable, $md5key, $siteURL, $privilege;

  unset($_SESSION['go']);

  if (isset ($_SESSION['failed_login'])) {
    if (strtoupper($captcha) != strtoupper($_SESSION["security_code"]))
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

  if (empty($username) || empty($password))
    dieJSON('EMPFRM');
  if (! $con = DB::instance() )
     dieJSON('DB');

  $stmt=$con->prepare("call login (?,?,@id,@prev)");

  $password=md5($password.$md5key);
  $stmt->bindParam(1, $username, PDO::PARAM_STR);
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
  $_SESSION['un']=$username;
  $_SESSION['prv']= preg_split('/,/', $privilege);

//DANGER: REMOVE IT LATER
  $_SESSION['gp']=$_SESSION['prv'][0];


  $output['go'] = isset ($_SESSION['go']) ? $_SESSION['go'] : "$siteURL/home";
  unset($_SESSION['go']);
  
  $output['error']=$error;

  echo json_encode($output);

  function regenerate_captcha() {
    $_SESSION['security_code'] = str_rand(6, 'alnum');
  }
?>

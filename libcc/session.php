<?php
// REITH: AUTHENTICATION WITH SESSION
// NOT JUST PHP SESSION.. GENERALLY EVERYTHING MUST BE ACCESSIBLE THROUGH ALL PAGES [IN OUR SESSION WITH CLIENT]
define ('__locale_path__', "/var/www/localhost/htdocs/cc/locale");

ini_set('use_cookies', 1);
ini_set('use_only_cookies', 1);
session_start();

//DANGER: shoud be removed
function checkSignIn($who)
{
    return hasPrivilege($who);
} 

function hasPrivilege( $who=null )
{
    if ( is_null($who) )
      return true;

    if (isset($_SESSION['id']) && isset($_SESSION['prv']))
    {
      if (in_array( $who, $_SESSION['prv']))
        return true;
    }

    return ( isset($_SESSION['id']) && $who=="*" ) ? true:false;
}

function signedIn()
{
    return isset($_SESSION['id']);
}

function signOut()
{
    if (isset($_SESSION['id']))
    {
      //avoid changing locale to defaults
      $locale = $_SESSION['locale'];
      $dir = $_SESSION['dir'];

      session_destroy();
      session_start();

      $_SESSION['locale'] = $locale;
      $_SESSION['dir'] = $dir;
    }
    else //maybe for escaping captcha	
      return;
    
}

if (!isset($_SESSION['locale']))
{
    $_SESSION['locale']='fa';
    $_SESSION['dir']= 'rtl';
}
if (isset( $_REQUEST['locale']))
{
  if ($_REQUEST['locale'] == 'fa')
  {
      $_SESSION['dir']= 'rtl';
      $_SESSION['locale']='fa';
  }
  else
  {
      $_SESSION['locale']='en';
      $_SESSION['dir']= 'ltr';
  }
}

  switch ($_SESSION['locale'])
  {
    case 'fa': setlocale(LC_ALL, 'fa_IR'); break;
    case 'en': setlocale(LC_ALL, 'en_US'); break;
  }

  bindtextdomain('cc', __locale_path__); 
  textdomain('cc');
?>
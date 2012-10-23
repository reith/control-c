<?php
// REITH: AUTHENTICATION WITH SESSION
// NOT JUST PHP SESSION.. GENERALLY EVERYTHING MUST BE ACCESSIBLE THROUGH ALL PAGES [IN OUR SESSION WITH CLIENT]

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

?>

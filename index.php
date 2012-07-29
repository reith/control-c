<?php
require_once 'libcc/config.php';
/*
** Before we start session need these declarations
*/
require_once 'libcc/context.class.php';

// Now start session
require_once 'libcc/session.php';

require_once 'libcc/general.functions.php';

/*
** session must be started
*/
$req_path = null;
require 'libcc/routing.php';

if (! isset($_SESSION['context']) )
	$_SESSION['context'] = new Context('http');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?=$_SESSION['locale']?>" xml:lang="<?=$_SESSION['locale']?>" dir="<?=$_SESSION['dir']?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<base href="<?=__url__?>" />
<link rel="stylesheet" type="text/css" href="/css/styles.css" />
<link rel="stylesheet" type="text/css" href="/css/popup.css" />
<link rel="icon" type="image/png" href="/layout/img/faveicon.png" />
<script type="text/javascript" src="/script/styles.js"></script>
<script type="text/javascript" src="/script/ajax.js"></script>
<script type="text/javascript" src="/script/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="/script/popup.js"></script>
<script type="text/javascript" src="/script/locale.js.php"></script>

<script type="text/javascript">
$(document).ready(function(){
		$loading=$('#loading');
		$.ajaxSetup({ beforeSend: function() {$loading.slideDown('fast')},
			complete: function() { $loading.slideUp('slow'); },
			error: function(e,t,x){ showError ('<?=_("Sorry, Communication with server faileds.");?>'+x); $loading.slideUp('slow');},
			success: function() {$loading.slideUp('slow');},
			});
		jsonError=function(json){if (json.error) { showError (json.error) ; return true }};
		});
</script>

<title id='title'>^C Yet Another Judgement System!</title>
</head>
<body style="direction: <?=$_SESSION['dir']?>;">
<div id="header" style="text-align: <?=$_SESSION['dir']=='ltr'?'left; padding-left: 300px;':'right'?> ;">
<?php require "./layout/header_div.php"; ?>
<div id="alert"></div>
</div>

<div id="alertMsgBox" class="hidemsg">
<div>
<a class="imageLink" style="float:right" href="javascript:hideMsg();"><img src="/layout/img/close-msg-box.png" alt="close"></img></a>
<div id="alertMsg"></div>
</div>
<div id="nextURL"></div>
</div>


<div id="main">
<div id="loading"><?=_("Please wait...")?><img src="/layout/img/loading.gif" alt="loading..."/></div>
<div id="banner"><img src="/layout/img/banner-right.gif" alt="Broken by ^C!"></img></div>
<div class="container">
<?php require "./layout/left-menu.php";?>
<div class="rightContainer" ID="activeBox" style="padding:20px; width:660px;">

<?php is_null($req_path) || require $req_path; ?>

</div>
</div>
</div>
<?php
$fp = fopen('version.txt', 'r');
$version = fgets( $fp, 1024 );
$date = fgets( $fp, 1024 );
fclose($fp);
printf( '<div id="footer">Powered by <a style="color:#ffd83d" href="%s">^C</a> v%s</div>',"http://12eith.com/code/%5EC/", $version);
?>
</body>
</html>

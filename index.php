<?php
require_once 'libcc/config.php';
/*
** Before we start session need these declarations
*/
require_once 'libcc/locale.class.php';
require_once 'libcc/context.class.php';

// Now start session
require_once 'libcc/session.php';

require_once 'libcc/general.functions.php';

// if (! isset($_SESSION['context']) )
//	$_SESSION['context'] = new Context('http');

$env = new Context('http');

require 'libcc/routing.php';

if( $env->getAction() ) {
	require './action/'.$env->getAction().'.php';
}

/*
 * Finish JSON requests
 */
 if( $env->isJSON() ) {
 	$env->setHeaders();
	die();
 }
?>
<!DOCTYPE html>
<html lang="<?=$env->locale()->name()?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<base href="<?=__url__?>" />
<!-- <link rel="stylesheet" type="text/css" href="/css/styles.css" /> -->
<link rel="stylesheet" type="text/css" href="/css/popup.css" />
<link rel="stylesheet" type="text/css" href="/css/bootstrap-<?=$env->locale()->direction();?>.css" />

<!-- load javascript files don't want to be loaded bye requireJS -->
<script type="text/javascript" src="/script/styles.js"></script>
<!-- -->

<!-- requireJS pre load config -->
<script type="text/javascript">
require_pre_cfg = {config: {
	i18n: {
		locale: '<?=$env->locale()->name();?>'
	}
}};
</script>

<script src="/script/lib/require/require.js"></script>
<script src="/script/main.js"></script>

<title id='title'><?= ($env->getData('title') != null) ? $env->getData('title') : "^c"; ?></title>

</head>
<body style="direction: <?=$env->locale()->direction()?>;">


<script type="text/javascript">
// Make App javascript module
require(['app', 'jquery'], function( App, $ ){
	App.setEnv(<?=json_encode( array ( 
		'url' => Routing::currentUrl(),
		'locale' => $env->locale()->name()
	)); ?>);
});
</script>

<?php require 'layout/header.php'; ?>

<div id="alert"></div>

<div class="container-fluid" id="main">

<div id="loading"><?=_('Loading')?><img src="/layout/img/loading.gif" alt="loading..."/></div>
<!--
<?php // require "./layout/left-menu.php";?>
-->

<!-- <div class="alert">This is calendar!</div> -->

<div class="row-fluid">
<?php is_null($env->getLayout() ) || require './layout/'.$env->getLayout().'.php'; ?>
</div>

</div>

<?php
/*
 * $fp = fopen('version.txt', 'r');
 * $version = fgets( $fp, 1024 );
 * $date = fgets( $fp, 1024 );
 * fclose($fp);
 * printf( '<footer >Powered by <a style="color:#ffd83d" href="%s">^C</a> v%s</footer>',"http://12eith.com/code/%5EC/", $version);
 */
?>

<!--
<div id="nextURL" style="display:none;"></div>
</body>

<div style="
z-index: 0px;
background-color: gray;
">
<div class="row-fluid" style="
padding: 0px;
margin: 0px;
direction: ltr;
">

	<div class="span4">
		Powered bye ^C. version 2.3.3;
	</div>

	<div class="span4">
		<h3>Sitmap</h3>
		<ul>
			<li>Exercise</li>(Help | Search | Index)
			<li>Announcements</li>
		</ul>
	</div>


	<div class="span4">
		<ul>
			<li>xxx</li>
			<li>yyy</li>
		</ul>
	</div>

</div>
</div>
-->
</div>

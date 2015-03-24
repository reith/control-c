<?php
require_once 'libcc/config.php';
/*
** Before we start session need these declarations
*/
require_once 'libcc/locale.class.php';
require_once 'libcc/context.class.php';

// Now start session
require_once 'libcc/session.php';

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

<?php
if ($env->isLegacy()) {
?>
<!-- <script type="text/javascript" src="/script/ajax.js"></script> -->
<script type="text/javascript" src="/script/jquery-min.js"></script>
<script type="text/javascript" src="/script/popup.js"></script>
<!-- <script type="text/javascript" src="/script/locale.js.php"></script> -->
<?php
}
?>
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

<?php
if ($env->isLegacy()) { ?>
	<div id="main">
	<div id="loading"><?=_("Please wait...")?><img src="/layout/img/loading.gif" alt="loading..."/></div>
	<div class="container">
		<div class="span-4">
		<?php require "./layout/left-menu.php" ?>
		</div>

	<div class="rightContainer span-8" ID="activeBox" style="padding:20px; width:660px;">
		<?php require $env->getLayout(); ?>
	</div>
	</div>

<?php } else { ?>

	<div class="container-fluid" id="main">
	<div id="loading"><img src="/layout/img/loading.gif" alt="loading..."/></div>


	<div class="row-fluid">
	<?php is_null($env->getLayout() ) || require './layout/'.$env->getLayout().'.php'; ?>
	</div>
<?php } ?>

</div> <!-- #main, both ways -->

<div id="nextURL" style="display:none;"></div>

</body>
</html>

<header></header>

<script type="text/javascript">
require(['lib/model/header', 'lib/view/header'], function(Header, HeaderView) {
	var header = new Header(<?=json_encode( array(
		'username' => signedIn() ? $_SESSION['un'] : null,
		'userid' => signedIn() ? $_SESSION['id'] : null,
		'language' => $env->locale()->name(),
		'currenturl' => Routing::currentUrl(),
    'datestr' => $env->locale()->date( time(), "E d MMM,"),
		'timestamp' => time()
	))?>
	);
	var headerView = new HeaderView({model: header});
	$('header').html(headerView.$el);
});
</script>

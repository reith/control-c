<!--REITH:  THIS IS MAIN FRAME VISITOR SEES-->
<!-- News... -->
<div id="announcementsContainer" class="span5"></div>
<!-- about -->

<div class="span7 tr-dir">

<div>
<?=_('^C is a judgment system designed with programming courses requirements in mind')?>.<br>
<hr>

<ul>
<li><?=_('Using useful options to configure grading strictness')?></li>
<li><?=_('Fuzzy Grading')?></li>
<li><?=_('Easy and Structural course management')?></li>
<li><?=_('Easy reporting and charting')?></li>
</ul>

<a class="btn btn-mini btn-success" href="http://www.reith.ir/code/%5EC/">
<?=_('More about ^C')?></a>
</div>

<div class="row-fluid" style="margin-top: 50px">
<div class="tr-dir span6">
	<h4>
	<?=_('Current system statistics')?>
	</h4><hr>
	<?php require 'layout/stats/summary.php'; ?>
</div>

<div class="span6">
<h4>همکاران</h4>
<hr>
<a href="http://shahroodut.ac.ir/"><img style="width:80px;" src="/img/sut-logo.jpg"></a>
</div>

</div>

</div>

<script type="text/javascript">
require(['jquery', 'lib/view/announcementsColumn'],
function($, AnnouncementsColumn) {

	var page = new AnnouncementsColumn( {collection: <?= json_encode($t['announcements']); ?>, archive: true} );
	$('#announcementsContainer').html(page.render().$el);
});
</script>

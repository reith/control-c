<!--REITH:  THIS IS MAIN FRAME VISITOR SEES-->
<!-- News... -->
<div id="announcementsContainer" class="span5"></div>
<!-- about -->

<div class="well span7 tr-dir">
<h2>What's this?</h2>
<hr>
^C is a judgment system designed with programming courses requirements in mind.<br>
<hr>

<ul>
<li>Using useful options to configure grading strictness</li>
<li>Fuzzy Grading</li>
<li>Easy and Structural course management</li>
<li>Easy reporting and charting</li>
</ul>

<a class="btn btn-mini btn-success" href="http://12eith.com/code/%5EC/">More on ^C</a>
</div>

<script type="text/javascript">
require(['jquery', 'lib/view/announcementsColumn'],
function($, AnnouncementsColumn) {

	var page = new AnnouncementsColumn( <?= json_encode($t['announcements']); ?>,
		$('#announcementsContainer') );
});
</script>

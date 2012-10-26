<div class="span7">
	<div id="monitor" class="well" style="display: none"></div>
	<div id="selectMenu"></div>

</div>
<div class="span1"></div>
<div id="rightMenu" class="span4"></div>

<script type="text/javascript">
require(['jquery', 'lib/view/announcementsColumn', 'lib/view/announcementsControl'],
function($, AnnouncementsColumn, AnnouncementsControl) {
	var list = new AnnouncementsColumn({
		collection: <?= json_encode($t['announcements']); ?>,
		selectable: true,
		$monitor: $('#monitor')
	});

	$('#rightMenu').html(list.render().$el);
	var control = new AnnouncementsControl();
	$('#selectMenu').html(control.render().$el);
	control.on('filteringsChanged', function() {
		list.filterByType( control.filterings );
	}, control);

});
</script>

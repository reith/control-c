<div class="span2"></div>
<div id="rightMenu" class="span8"></div>

<script type="text/javascript">
require(['jquery', 'lib/view/announcementsColumn'],
function($, AnnouncementsColumn) {

	var page = new AnnouncementsColumn( <?= json_encode($t['announcements']); ?>,
		$('#rightMenu') );
});
</script>

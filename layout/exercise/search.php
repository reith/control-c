<?php
require './libcc/course.php';
?>

<div id="resultBox" class="span6"></div>
<div id="searchContainer" class="span6"></div>

<script type="text/javascript">
<?php
echo 'var request_data = '.json_encode( $_REQUEST ).';';
?>

require(['lib/view/exerciseSearch', 'lib/view/exerciseSearch', 'backbone'],
function(ExerciseView, ExerciseSearchView, Backbone) {
	var prepare_fields = function() {
		if( request_data.course ) {
			$('#courseSelect > option').each( function(n, e) {
				if( $(this).attr('value') == request_data.course )
					$(this).attr('selected', '1');
			});
		}
	};

	var Page = Backbone.View.extend({
		initialize: function() {
			this.searchView = new ExerciseSearchView();
			this.searchView.on('ready', function() {
				prepare_fields();
				if (request_data.auto_search)
					this.searchView.search();
			}, this);
		},

		render: function() {
			this.$el.html(this.searchView.$el);
			this.searchView.render($('#resultBox'));
		},

	});

	var page = new Page();
	$('#searchContainer').html(page.$el);
	page.render();

});
</script>

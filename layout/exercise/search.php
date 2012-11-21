<?php
require './libcc/course.php';
?>

<div id="searchContainer"></div>
<div id="resultBox" class="span6">
</div>

<script type="text/javascript" src="/script/sprintf-0.7-beta1.js"></script>
<script type="text/javascript">
<?php
echo 'var request_data = '.json_encode( $_REQUEST ).';';
?>

require(['lib/view/exerciseSearch', 'lib/view/exerciseSearch', 'backbone'],
function(ExerciseView, ExerciseSearchView, Backbone) {
	var Page = Backbone.View.extend({
		initialize: function() {
			this.searchView = new ExerciseSearchView();
		},

		render: function() {
			this.$el.html(this.searchView.$el);
			this.searchView.render();
		},

	});

	var page = new Page();
	$('#searchContainer').html(page.$el);
	page.render();
});
/*
require(['jquery'], function($) {

prepare_fields = function() {
	if( request_data.course ) {
		$('#courseSelect > option').each( function(n, e) {
			if( $(this).attr('value') == request_data.course )
				$(this).attr('selected', '1');
			}
		);
	}
}

$('form').bind('submit', function(e) {
		e.preventDefault();
		$.ajax({
		url: $('form').attr('action'),
		type: $('form').attr('method'),
		dataType: 'json',
		data: $('form').serialize(),
		context: $('#resultBox'),
		success: function(j) {
			$(this).empty();
			for( i in j.rows ) {
				var r = j.rows[i];
				$(this).append( $('<div class="tr-dir alert alert-success"/>').html(
				sprintf("%s - <a href='%s/%s'>%s</a> <br> %s",
				//FIXME: this section suck, I should write another router with javascript.
				Locale._number(r.id), '<?=Routing::url('exercise/')?>', r.id,
				r.title, r.explain)
				));
			}

			set_dir_from_content( $('.tr-dir') );

			}
		});
	});
prepare_fields();

if( request_data.as )
	$('form').submit();
});

*/
</script>

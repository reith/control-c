<?php
require './libcc/course.php';
?>

<div class="span6">
<form class="form-horizontal" action="<?=Routing::url('exercise/search')?>" method="post">

<div class="control-group">
<label class="control-label" for="courseSelect">Course</label>
<div class="controls">
<select name="courses[]" id="courseSelect" multiple="multiple">
<?php
foreach( get_courses_names() as $row )
	printf( '<option value="%d">%s - %d</option>', $row[0], $row[1], $row[0] );
?>
</select>
<label class="checkbox"><input type="checkbox" value="jjc">Select joined courses</label>
<label class="checkbox"><input type="checkbox" value="jjc">Select My Own courses</label>
</div>
</div>

<div class="control-group">
<label class="control-label">Grading status</label>
<div class="controls">
<label class="checkbox inline"><input value="1" name="ge" checked="1" type="checkbox">Graded exercies.</label>
<label class="checkbox inline"><input value="1" name="nge" checked="1" type="checkbox">Not graded exercies.</label>
</div>
</div>

<div class="control-group">
<label class="control-label">Content</label>
<div class="controls" id="contentSeaech">
<input id="keywordsInput" placeholder="Some keywords seperated by space" type="text" name="keywords">
<div>
<label class="inline checkbox"><input name="skt" type="checkbox" checked="1">Title</label>
<label class="inline checkbox"><input name="ske" type="checkbox">Explaination</label>
</div>
</div>
</div>

<hr>

<div class="control-group">
<label class="control-label">Sort result by</label>
<div class="controls">
<label class="radio inline"><input name="sort" value="ed" type="radio">Deadline</label>
<label class="radio inline"><input name="sort" value="ea" type="radio">Added date.</label>
<label class="radio inline"><input checked=1 name="sort" value="et" type="radio">Title</label>
</div>
</div>

<hr>
<?php
if(! signedIn() )
	printf('<span class="alert alert-info">%s</span>',
	_('Login to see more options according to your membership.')
	);
?>

<button type="submit" class="btn btn-info">Search</button>
</form>
</div>

<div id="resultBox" class="span6">
</div>

<script type="text/javascript" src="/script/sprintf-0.7-beta1.js"></script>
<script type="text/javascript">
<?php
echo 'var request_data = '.json_encode( $_REQUEST ).';';
?>

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
//		$('button[type=submit]').attr('disabled', '1');

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
</script>

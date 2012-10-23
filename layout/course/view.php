<div id='courseWrapper' class='span9' ></div>
<div id='rightMenu' class='span3'></div>

<script type="text/javascript">

require(['backbone', 'lib/model/course', 'lib/view/course'], function(Backbone, Course, CourseProfileView) {
	var PageApp = Backbone.View.extend ({

		defaults: {
			course: undefined,
			courseView: undefined,
		},

		initialize: function( courseData ) {
			this.setCourse( new Course(courseData) );

			this.setCourseView( new CourseProfileView({
				model: this.course
			}));
			this.courseView.setButtonsPlaceHolder( $('#rightMenu') );
			this.courseView.render();
			this.setViewer(this.courseView.$el);
		},

		setViewer: function( elem ) {
			$('#courseWrapper').html(elem);
		},

		setCourse: function (courseModel) {
			this.course = courseModel;
		},

		setCourseView: function( courseView ) {
			this.courseView = courseView;
		}
	});

	var courseData =  <?=json_encode($t['course']);?> ;
	var pageApp = new PageApp( courseData );
});

/*
 * var courseView = new CourseView({'model':course});
 * $('#courseWrapper').append(courseView.$el);
 * setTimeout(function(){course.fetch({success:function(j) {}})}, 2000);
 * console.log(course);
 */
// console.log(courseView.$el);
</script>

<?php
/*
error_reporting( E_ALL );
echo <<<EOD

<h3>{$t['course']['name']}</h3>
<hr>
<dl>
<dt>Teacher</dt>
<dd>{$t['course']['teacherName']}</dd>
<dt>Problem Sets count</dt>
<dd>{$t['course']['seriCount']}</dd>
<dt>Semester Start</dt>
<dd>{$t['course']['semester']}</dd>
<dt>Programming Language</dt>
<dd>{$t['course']['language']}</dd>
<dt>Number of students</dt>
<dd>{$t['course']['sc']}</dd>
<dt>Average grade</dt>
<dd>{$t['course']['sag']}</dd>
<dt>Best grade</dt>
<dd>{$t['course']['smg']}</dd>
</dl>

</div>

<div class="span2">
EOD;
/*
if( $t['course']['lock'] === '1' )
	echo '<div class="label">Locked</div><br>';

printf( '<a href="%s" class="btn btn-success">%s</a> ', Routing::url('exercise/search').'/?course='.$t['course']['id'].'&as=1',  _( 'Course Problems' ) );
*/

// echo '</div>';
?>



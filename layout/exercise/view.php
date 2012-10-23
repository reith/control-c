<div class="span8" id="exerciseContainer"></div>
<div class="span4" style="height: 150px" id="exerciseStats"></div>

<script type="text/javascript">
require(['backbone', 'jquery', 'lib/model/exercise', 'lib/view/exercise'],
	function(Backbone, $, Exercise, View){

	var exercise = new Exercise(<?=json_encode($t['e']);?>);
	exercise.fetch();
	var ev = new View({model:exercise});
	$('#exerciseContainer').append(ev.$el);

	if( exercise.get('graded') ) { // For Statistics
		require(['lib/chart/exercise'], function(ExerciseStats) {
			var stats = new ExerciseStats({id: exercise.get('id')});

			stats.on('change', function() {
				this.generate('exerciseStats');
			});
		});
	}
});

</script>

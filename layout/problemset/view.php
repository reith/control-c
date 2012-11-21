<div class="row-fluid" id="profileContainer">
<div class="span8" id="summaryContainer"></div>
<div class="span4" style="height:400px;" id="statsContainer"></div>
</div>

<script type="text/javascript">
require(['backbone', 'lib/model/problemset', 'lib/view/problemset', 'router'], 
function( Backbone, Set, SetView, Router ) {
	var PageView = Backbone.View.extend({
		$el: $('#profileContainer'),

		setProblemset: function() {
			this.set = new Set(
				<?=json_encode($t['problemset']);?>
			);
			this.setView = new SetView({
				model: this.set
			});
		},

		showProblemset: function() {
			$('#summaryContainer').html(this.setView.$el);
			this.setView.render();
		},

		render: function() {
			this.showProblemset();
			this.expandExercises();
		},

		initialize: function() {
			this.setProblemset();
			this.setView.bind('showExercises', this.expandExercises, this);
			this.render();
			if( this.set.get('graded') ) {
				setTimeout(this.makeCharts, 2000);
			}
			Backbone.history.start({pushState: true});
		},

		minimizeProfile: function($container) {
			var page = this;
			if (page.miniSetView)
				return page.miniSetView.show();

			require(['lib/view/problemsetMini'], function(View){
				page.miniSetView =  new View({model: page.set});
				$container.prepend( page.miniSetView.$el );
				page.miniSetView.render();
			});
		},

		expandExercises: function() {
			var page = this;
			require(['lib/model/exercise', 'lib/view/exerciseItem'],
				function( Exercise, ExerciseItemView ) {

				this.exerciseList = new Backbone.Collection(page.set.get('exercise'), {model: Exercise});
				page.exerciseItemViews = [];
				page.$exerciseContainer = $('<div class="well item-container" />');

				this.exerciseList.each(function(e) {
					var eiv = new ExerciseItemView({
						model: e
					});
					page.$exerciseContainer.append(eiv.$el);
					page.exerciseItemViews.push( eiv );
					eiv.render();
				});

				_.each(page.exerciseItemViews, function(exerciseItemView) {
					exerciseItemView.on('exerciseLoaded', function() {
						page.showExercise(this.model, this);
					}, exerciseItemView );
				}, this);

				$('#statsContainer').html(page.$exerciseContainer);
			});
		},

		showExercise: function(exerciseModel, exerciseItemView) {
			var page = this;
			require(['lib/view/exercise', 'router'], function(ExerciseProfileView, Router) {

				if( page.showingExercise ) {
					page.showingExercise.unselect();
					if( exerciseItemView === page.showingExercise ) {
						page.showProblemset();
						Router.navigate(page.set.url());
						page.showingExercise = undefined;
						page.miniSetView.hide();
						return;
					}
				} else {
					page.minimizeProfile(page.$exerciseContainer);
				}
				var epv = new ExerciseProfileView({model: exerciseModel});
				page.showingExercise = exerciseItemView;
				$('#summaryContainer').html(epv.$el);
				Router.navigate(exerciseModel.url());
			});
		},

		makeCharts: function() {
			if( this.showingExercise ) return;
			require(['lib/chart/problemset'], function(Chart) {
				var chart = new Chart({id: page.set.id});
				$('#summaryContainer').append('<div class="well" style="height: 200px;" id="statsPlaceholder"/>');
				chart.on('change', function(){ chart.generate('statsPlaceholder')});
			});
		}

	});

	var page = new PageView();

});
</script>

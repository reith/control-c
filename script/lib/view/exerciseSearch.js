define(['backbone',
	'underscore',
	'lib/model/exerciseSearch',
	'lib/collection/courses',
	'lib/view/courseSelectItem',
	'lib/model/exercise',
	'lib/view/exerciseSummary',
	'text!lib/template/form/exerciseSearch.html',
	'i18n!nls/dict'],
function(Backbone, _, Search, Courses, CourseSelectItem, Exercise, ExerciseView, Template, Dictionary) {
	var SearchBoxView = Backbone.View.extend({
		initialize: function() {
			this.pageNumber = 0;
		},

		render: function($resultContainer) {
			this.$formDiv = $('<div />');
			this.$result = $resultContainer;
			this.$el.append(this.$formDiv);

			this.$formDiv.html(_.gtemplate(Template, {}, Dictionary));
			this.$form = this.$formDiv.find('form');
			this.$error = this.$('#errorContainer');
			this.load_courses();

			this.delegateEvents({'submit': function(e) {e.preventDefault(); this.search()}});
		},
		
		reset: function() {
			this.$error.hide();
			this.$result.empty();
		},

		load_courses: function() {
			$courses = this.$formDiv.find('#courseSelect');
			var allCourses = new Courses();
			
			allCourses.fetch({context: this}).done(function() {
				allCourses.each(function(model, index) {
					var view = new CourseSelectItem({model: model});
					view.render();
					view.$el.appendTo($courses);
				});
				this.trigger('ready');
			});
		},

		search: function() {
			this.reset();
			var search = new Search({
				query: this.$form.serialize()
			});

			search.fetch({type: 'POST', success: function(model, response) {
				if (response.error)  {
					this.$error.text(response.error).show();
					return;
				}
				var collection = new Backbone.Collection(response['rows'], {model: Exercise});
				collection.each(function(model, index) {
					new ExerciseView({model: model}).render().$el.appendTo(this.$result);
				}, this);
				if (response.found_rows + response.rows.length > response.limit_rows)
					console.log('has next', response);
			}, context: this});

		}
	});

	return SearchBoxView;
});

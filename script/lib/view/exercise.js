define(['backbone', 'underscore', 'text!lib/template/exercise/profile.html', 'i18n!nls/dict'],
function(Backbone, _, tmpl, Dict) {
	var ExerciseView = Backbone.View.extend({
		tagName: 'div',

		initialize: function() {
			this.render();
		},

		render: function() {
			this.$el.html(_.gtemplate(tmpl, this.model.toJSON(), Dict));
		}
	});

	return ExerciseView;
});


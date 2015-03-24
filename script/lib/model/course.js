define(['backbone'], function(Backbone) {
	var Course = Backbone.Model.extend({
		urlRoot: function() {
			return App.env.locale + '/course/';
		}

	});

	return Course;
});

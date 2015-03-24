define(['backbone', 'lib/model/course'], function(Backbone, Course) {
	return Backbone.Collection.extend({
		model: Course,
		url: function() {
			return App.env.locale + '/course';
		},
	});
})

define(['backbone', 'app'], function(Backbone, App){
	var Exercise = Backbone.Model.extend({
		urlRoot: function() {
			return App.env.locale + '/exercise';
		}
	});
	return Exercise;
});

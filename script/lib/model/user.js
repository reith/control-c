define(['backbone'], function(Backbone) {
	var User = Backbone.Model.extend({
		urlRoot: function() {
			return App.env.locale + '/user/';
		}
	});

	return User;
});

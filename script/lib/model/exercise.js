define(['backbone', 'app'], function(Backbone, App){
	var Exercise = Backbone.Model.extend({
		url: function() {
			return App.env.locale + '/exercise/' + this.get('id');
		}
	});
	return Exercise;
});

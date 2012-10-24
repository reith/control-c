define(['backbone'], function(Backbone) {
	var Announcement = Backbone.Model.extend({
		defaults: {
			type: null
		},

		url: function() {
			var prefix = App.env.locale;
			switch (this.get('type')) {
				case 'pa':
				case 'pg': return prefix + '/problemset/' + this.get('subject');
				case 'ca':
				case 'ce': return prefix + '/course/' + this.get('subject');
				case null: return prefix + '/announcement/' + this.get('id');
			}
		}

	});

	return Announcement;
});

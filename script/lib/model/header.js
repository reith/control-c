define(['backbone', 'underscore'], function(Backbone, _) {
	var Header = Backbone.Model.extend({
		defaults: {
			username: null,
			userid: null,
			timestamp: null
		},

		incrementSecond: function() {
			this.set('timestamp', this.get('timestamp') + 1);
		},

		initialize: function() {
			_.bindAll(this, 'incrementSecond');
			this.startClock();

		},

		startClock: function() {
			setInterval(this.incrementSecond, 1000);
		},

		signedin: function() {
			return (this.attributes['userid'] !== null) ? true : false;
		}
	});

	return Header;
});

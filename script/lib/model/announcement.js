define(['backbone'], function(Backbone) {
	var Announcement = Backbone.Model.extend({
		defaults: {
			type: null
		},

		hide: function() {
			this.trigger('hide');
		},

		select: function() {
			this.fetch({success: _.bind(function() {
				this.trigger('selected', this.id);
			}, this)});
		},

		show: function() {
			this.trigger('show');
		},

		initialize: function() {
			if(! this.has('type') ) {
				this.set({'type': 'other'});
			 };
		},

		url: function() {
			var prefix = App.env.locale;
			switch (this.get('type')) {
				case 'pa':
				case 'pg': return prefix + '/problemset/' + this.get('subject');
				case 'ca':
				case 'ce': return prefix + '/course/' + this.get('subject');
				case 'other': return prefix + '/announcement/' + this.get('id');
			}
		}

	});

	return Announcement;
});

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
		},

		initialize: function() {
			var type = this.get('type');
			if ( type != null ) {
				var sbj = this.get('subject');
				switch( type ) {
					case 'pa': this.set('title', 'Problemset #'+sbj+ ' added'); break;
					case 'pg': this.set('title', 'Problemset #'+sbj+' graded'); break;
					case 'ca': this.set('title', 'Course '+sbj+' added'); break;
					case 'ce': this.set('title', 'Course '+sbh+' closed'); break;
				}
			}
		}

	});

	return Announcement;
});

define(['backbone', 'i18n!nls/dict', 'text!lib/template/form/announcements.html'],
function(Backbone, Dict, Template) {
	var Form = Backbone.View.extend({
		tagName: 'div',
		className: 'well',

		events: {
			'change input': 'updateFilterings'
		},

		render: function() {
			this.$el.html(_.template(Template, Dict));
			return this;
		},

		initialize: function() {
			this.filterings = {};
		},

		updateFilterings: function(event) {
			if( event.target.checked )
				this.filterings[event.target.id] = true;
			else
				delete this.filterings[event.target.id];

			this.trigger('filteringsChanged');
		}

	});

	return Form;
});

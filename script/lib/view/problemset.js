define(['backbone', 'underscore', 'text!lib/template/problemset/profile.html', 'i18n!nls/dict'], function(Backbone, _, tmpl, Dict) {
	var ProblemsetView = Backbone.View.extend({
		tagName: 'div',

		defaults: {
			editable: false
		},

		events: {
			'click #editProblemset': 'edit',
		},

		render: function() {
			this.$el.html( _.gtemplate(tmpl, this.model.toJSON(), Dict ));
			return this;
		},

		edit: function() {
			this.template = _.template( $('#editProblemsetTemplate').html() );
			this.trigger('edit','problemset',this.model.get('id'));
			this.render();
		},

		initialize: function() {}
	});

	return ProblemsetView;
});


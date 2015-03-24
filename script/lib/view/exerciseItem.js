define(['backbone', 'underscore'], function(Backbone, _) {
	var View = Backbone.View.extend({
		tagName: 'div',
		className: 'tr-dir clickable',

		render: function() {
			this.$el.html(
				_.template(
					'<%- Locale._number(number) %> - <%- title %> [<%- Locale._number(wage) %>]',
					this.model.toJSON()
				)
			);
			this.unselect();
			return this;
		},

		events: {
			'click': function() {
				var eiv = this;
				this.model.fetch({
					success: function() {
						eiv.trigger('exerciseLoaded');
						eiv.select();
					}
				});
			}
		},

		select: function() {
			this.$el.addClass('selected');
		},

		unselect: function() {
			this.$el.removeClass('selected');
		}

	});
	return View;
});

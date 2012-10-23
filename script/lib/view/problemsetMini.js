define(['backbone', 'i18n!nls/dict'], function(Backbone, Dict) {
	var View = Backbone.View.extend({
		tagName: 'div',

		render: function() {
			this.$el.html(
				_.gtemplate('<h4><%* problemset %> #<%= Locale._number(id) %></h4>', this.model.toJSON(), Dict)
			);
		},

		hide: function() {
			this.$el.hide();
		},

		show: function() {
			this.$el.show();
		}

	});

	return View;
});

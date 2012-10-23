define(['backbone', 'text!lib/template/user/profile.html', 'i18n!nls/dict'],
function(Backbone, Template, Dictionary) {
	var View = Backbone.View.extend({
		tagName: 'div',
		initialize: function(){
			this.render();
		},

		render: function() {
			this.$el.html( _.gtemplate(Template, this.model.toJSON(), Dictionary));
			return this;
		}
	});

	return View;
});

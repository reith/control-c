define(['backbone', 'lib/model/course'], function(Backbone, Course) {
	return Backbone.View.extend({
		tagName: 'option',
		render: function() {
			return this.$el.attr('value', this.model.get('id')).text(
				this.model.get('name') + ' - ' + Locale._number(this.model.get('id'))
			);
		}
	});
});

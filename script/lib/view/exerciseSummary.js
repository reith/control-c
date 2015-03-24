define(['backbone', 'underscore', 'lib/model/exercise', 'text!lib/template/exercise/summary.html', 'i18n!nls/dict'], 
function(Backbone, _, Exercise, tmpl, Dict) {
	return Backbone.View.extend({
		tagName: 'div',
		render: function() {
			this.$el.html(_.gtemplate(tmpl, _.extend(this.model.toJSON(), {url: this.model.url()}), Dict));
			return this;
		}
	});
})

define(['backbone', 'underscore', 'lib/model/announcement'],
function(Backbone, _, Announcement) {
	var View = Backbone.View.extend({
		tagName: 'div',
		className: 'tr-dir',

		render: function() {
			if( this.model.get('title') ) {
				var linkhtml = '<a href="' + this.model.url() +'">view</a>';
				this.$el.html(_.template('<%- title %><br><span style="font-size: smaller;color: #C8C8C8;"><%- date %> | '+ linkhtml +'</span>', this.model.toJSON()));
			}

		}
	});
	return View;
});

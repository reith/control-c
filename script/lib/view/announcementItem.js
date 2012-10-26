define(['backbone', 'underscore', 'lib/model/announcement'],
function(Backbone, _, Announcement) {
	var View = Backbone.View.extend({
		tagName: 'div',
		className: 'tr-dir',

		render: function() {
			if( this.model.get('title') ) {
				this.$el.html(_.template(
					'<%- title %><br><span style="font-size: smaller;color: #C8C8C8;"><%- date %></span> | <a href="<%- url %>"><i class="icon-fullscreen"> </i></a>',
					_.extend( this.model.toJSON(), {url: this.model.url()} )
				));
			}
			this.options.clickable && this.$el.addClass('clickable');
			return this;
		},

		initialize: function() {
			this.model.bind('hide', this.hide, this);
			this.model.bind('show', this.show, this);
			if( this.options.clickable ) this.delegateEvents({'click': function() {this.model.select()}});
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

define(['backbone', 'lib/model/announcement', 'lib/view/announcementItem'],
function(Backbone, Announcement, AnnouncementItemView) {
	var PageApp = Backbone.View.extend({
		defaults: {
			announcements: null,
		},

		initialize: function( announcementsData, $placeHolder ) {
			var Collection = Backbone.Collection.extend({ model: Announcement });
			this.announcements = new Collection();
			this.$rightMenu = $('<div class="well item-container"/>');
			$placeHolder.append(this.$rightMenu);
			_.each(announcementsData, function(data) {
				var model = new Announcement( data );
				this.announcements.add( model );
				var view = new AnnouncementItemView({model: model});
				view.render();
				this.$rightMenu.append(view.$el);

			}, this);
		}
	});

	return PageApp;
});


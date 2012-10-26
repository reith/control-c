define(['backbone', 'lib/model/announcement', 'lib/view/announcementItem', 'i18n!nls/dict'],
function(Backbone, Announcement, AnnouncementItemView, Dictionary) {
	var PageApp = Backbone.View.extend({
		initialize: function( announcementsData ) {
			this.hiddens = [];
			var Collection = Backbone.Collection.extend({ model: Announcement });
			this.announcements = new Collection(this.collection);
			if( this.options.selectable ) {
				this.$monitor = this.options.$monitor.addClass('wall');
				this.announcements.on('selected', function(id) {
					this.$monitor.html( this.announcements.get( id ).get('body') ).show();
				}, this);
			}
		},

		filterByType: function( type ) {
			var mustHide = this.announcements.filter( function(item) {
					return _.isObject(type) ? ! _.has(type, item.get('type')) : item.get('type') != type;
			});
			var mustShow = _.without(this.hiddens, mustHide);
			_.each(mustShow, function(item) {
				item.show();
			})
			_.each(mustHide, function(item) {
				item.hide();
			});
			this.hiddens = mustHide;
		},

		render: function(options) {
			this.$el = $('<div class="well item-container"/>');
			this.announcements.each(function(model) {
				var view = new AnnouncementItemView({
					'model': model,
					'clickable': this.options.selectable && (model.get('type') == 'other')
				});
				this.$el.append(view.render().$el);
			}, this);
			this.options.archive && this.$el.append( this.archiveButton() )
			return this;
		},

		archiveButton: function() {
			$archiveButton = $("<div />").addClass("item").css({'text-align':'center', 'padding':'0px'});
			$link = $('<a />').addClass('btn btn-warning').css({
				'width': '100%',
				'color': 'white',
				'font-weight': '900',
				'border-width': '0px',
				'padding': '0px',
				'-moz-border-radius-bottomright': '10px',
				'-moz-border-radius-bottomleft': '10px',
				'border-bottom-right-radius': '10px',
				'border-bottom-left-radius': '10px',
				'-moz-border-radius-topright': '0px',
				'-moz-border-radius-topleft': '0px',
				'border-top-left-radius':'0px',
				'border-top-right-radius':'0px',
			}).html(Dictionary.ar).attr('href', App.env.locale + '/announcement');

			return this.$archiveButton = $archiveButton.append($link);
		}

	});

	return PageApp;
});


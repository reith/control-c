define(['jquery', 'backbone', 'i18n!nls/dict', 'i18n!nls/formatters'], function($, Backbone, Dict, Formatter) {
	var HeaderView = Backbone.View.extend({
		tagName: 'div',

		initialize: function() {
			this.render();
			this.model.on('change:timestamp', this.updateTime, this);
		},

		render: function() {
			this.$links = $('<ul/>');
			if(this.model.signedin()) {
				this.appendProfileLink();
				this.appendSignoutLink();
			} else {
				this.appendSigninLink();
				this.appendRegisterLink();
			}
			this.$el.append(this.$links);
			
			/*
			 * TODO: move these to main css file
			 */
			this.$links.css({
				'max-width': '200px',
				'padding-left': '5px',
				'padding-right': '5px',
			});
			
			this.$links.children('li').css({
				'padding-left': '3px',
				'padding-right': '3px',
				'background-color': '#E3AA00',
				'border-radius': '4px',
				'margin-left': '5px',
				'margin-right': '5px',
			});

			this.$timestr = $('<span />');
			this.$el.append('<span>'+Dict.svt+' : '+this.model.get('datestr')+'</span> ').append(this.$timestr).css({
				'color': 'white',
				'padding-right': '100px'
			});
			this.updateTime();

			/*
			 * this.delegateEvents({
			 *   'click #signin': function(event) {
			 *     event.preventDefault();
			 *     this.showSigininForm();
			 *   }
			 * });
			 */
			return this;
		},

		showSigininForm: function() {
			$form = $('<form class="form-inline"><input type="text"></form>');
			$form.insertAfter($('#signin').parent());
		},

		updateTime: function() {
			var d = new Date;
			d.setTime(this.model.get('timestamp') * 1000);
			this.$timestr.html(
				Formatter._number(d.getUTCHours()) + ':' +
				Formatter._number(d.getUTCMinutes()) + ':' +
				Formatter._number(d.getUTCSeconds())
			);
		},

		addToList: function( htmlelem ) {
			this.$links.append($('<li />').html(htmlelem));
		},

		appendProfileLink: function() {
			this.addToList('<a href="/' + App.env.locale + '/user/'+this.model.get('userid')+'">'+this.model.get('username')+'</a>');
		},

		appendRegisterLink: function() {
			this.addToList('<a href="' + App.env.locale + '/signup">' + Dict.rgs + '</a>');
		},

		appendSigninLink: function() {
			this.addToList('<a id="signin" href="/' + App.env.locale + '/login">' + Dict.sgin + '</a>');
		},

		appendSignoutLink: function() {
			this.addToList($('<a/>').attr('href', '/' + App.env.locale + '/signout').text(Dict.sgot));
		}
	});

	return HeaderView;
});

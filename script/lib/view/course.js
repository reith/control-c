define(['backbone','underscore', 'text!lib/template/course/profile.html', 'i18n!nls/dict'],
function(Backbone, _, Template, Dictionary) {
	var CourseView = Backbone.View.extend({
		defaults: {
			$buttonsPH: null //If null use $el
		},

		render: function() {
			this.$el.html( _.gtemplate(Template, this.model.toJSON(), Dictionary)  );
			this.chooseButtonsPlaceHolder();
			$('<a/>').attr('href', '/' + App.env.locale + '/exercise/search?auto_search=1&course=' + this.model.get('id'))
				.addClass('btn').addClass('btn-success').text(Dictionary['fce']).appendTo(this.$buttonSet);
			return this;
		},

		setButtonsPlaceHolder: function( $parelem ) {
			this.$buttonsPH = $parelem;
		},

		/*
		 * $buttonsSet is actual place holder where $buttonsPH is parent 
		 */
		chooseButtonsPlaceHolder: function() {
			var $buttonSet = $('<div id="buttons-set" />');
			(this.$buttonsPH || this.$el).append($buttonSet);
			this.$buttonSet = $buttonSet;
		}
	});

	return CourseView;
});

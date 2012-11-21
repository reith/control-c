define(['backbone', 'lib/model/exerciseSearch', 'text!lib/template/form/exerciseSearch.html', 'i18n!nls/dict'],
function(Backbone, Search, Template, Dictionary) {
	var SearchBoxView = Backbone.View.extend({
		render: function() {
			this.$formDiv = $('<div />');
			this.$result = $('<div />');
			this.$el.append(this.$formDiv);
			this.$el.append(this.$result);

			this.$formDiv.html(_.gtemplate(Template, {}, Dictionary));
			this.$form = this.$formDiv.find('form');

			this.delegateEvents({'submit': function(e) {e.preventDefault(); this.search()}});
		},

		search: function() {
			var search = new Search({
				query: this.$form.serialize()
			});

			var that = this;
			search.fetch({type: 'POST', success: function(model, responce) {
				// that.showExercise(responce);
				console.log(responce);
			}});

		},

		initialize: function() {
			_.bindAll(this, 'showExercise');
		},

		showExercise: function() {
			console.log(arguments);
		}

	});

	return SearchBoxView;
});

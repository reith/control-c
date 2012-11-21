/*
 * Search box model for exericise section.
 * A generic search model could be generalized, probably.
 */

define(['backbone'], function(Backbone) {
	var Search = Backbone.Model.extend({
		defaults: {
			query: null
		},

		'url': function() {
			return App.env.locale + '/exercise/search/?' + this.get('query');
		}

	});

	return Search;
})

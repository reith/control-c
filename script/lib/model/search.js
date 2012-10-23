/*
 * Module for search operations
 */

define(['backbone'], function() {
	var Search = Backbone.Model.extend({
		defaults: {
			subject: 'exercise'
		},

		url: {
			App.env.locale + '/exercise/search';
		}
	});
});

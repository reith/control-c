/*
 * Backbone routing object (returns instiated module)
 */

define( ['backbone'], function (Backbone) {
	var Router = Backbone.Router.extend({
		routes: {
			':lang/problemset/:id/edit' : 'editProblemset'
		},

		defaultAction: function() {
			console.log(arguments);
		},

		editProblemset: function() {
			console.log('route => edit');
		}
	});

	var r = new Router();
	Backbone.history.start ({pushState: true} );
	return r;
});

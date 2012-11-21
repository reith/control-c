/*
 * Backbone routing object (returns instiated module)
 */

define( ['backbone'], function (Backbone) {
	var Router = Backbone.Router.extend({
		routes: {
			':lang/problemset/:id/edit' : 'editProblemset',
			':lang/problemset/:id' : 'viewProblemset',
			':lang/exercise/:id' : 'viewExercise'
		},

		defaultAction: function() {
			console.log('router *d', arguments);
		},

		editProblemset: function() {
			console.log('route => edit');
		},

		viewProblemset: function() {
			console.log('view problemset');
		}

	});

	var r = new Router();
	// Backbone.history.start({pushState: true});
	return r;
});

/*
 * Module for general data
 */

define(['jquery'], function($) {
	window.App = {
		'env':null,
		'setEnv': function( obj ) {
			this.env = obj;
		}
	};
	return window.App;
});

/*
 * Localiztioan Formatting root module
 */
define( function() {
	// set global Locale instance to use _number inside templates easy
	window.Locale = {
		_number: function(num) {
			return num+'';
		}
	};

	// return object to make bundles possible
	return { 'root': window.Locale, 'fa': true };
});

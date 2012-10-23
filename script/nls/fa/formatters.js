/*
 * Localization Foramating module for Persian
 */

define(function() {
	var fa_num_offset = '۰'.charCodeAt(0)-'0'.charCodeAt(0);
	window.Locale = {
		_number: function( num ) {
			var fa_str='';
			numstr = num +'';
			for (var i = 0, c=numstr.length; i<c; i++)
				fa_str += String.fromCharCode(numstr.charCodeAt(i)+fa_num_offset);
			return fa_str;
		}
	};
	return window.Locale;
});

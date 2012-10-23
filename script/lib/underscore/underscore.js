define(['lib/underscore/underscore-min'], function() {
	// Localized template
	_.gtemplate = function( templateString, data, formatter, settings ) {
		var settings = settings || {};
		var localizedTemplateString = _.template( templateString, formatter, {
			interpolate: /<%\*([\s\S]+?)%>/g,
			escape: false,
			evaluate: false
		});
		return _.template( localizedTemplateString, data );
	};
	return _;
});

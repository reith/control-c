define(['i18n!nsl/dict'], function(Dict) {
	var map = {
		'exercise': 'exercise',
		'course': 'course',
		'exercise_search', 'exercise/search'
	};

	return {
		generate: function(section) {
			return '/' + App.env.locale + map['section'];
		}
	};
});

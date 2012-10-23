define( ['backbone'], function(Backbone) {

	var Problemset = Backbone.Model.extend({
		urlRoot: function() {
			return App.env.locale + '/problemset/';
		},

		effectiveWage: function() {
			val = this.get('wage') / this.get('courseSetsWageSum');
			this.set('effectiveWage', val);
		},

		initialize: function() {
			this.effectiveWage();
			this.set('editable', true);
		},

		getStats: function() {
			this.stats = new ProblemsetStats({
				'id': this.get('id'),
				'locale': this.get('locale')
			});

			this.stats.bind('change', function(){
				this.makeChart();
			}, this.stats);

			this.stats.fetch();

		}
	});
	return Problemset;
});

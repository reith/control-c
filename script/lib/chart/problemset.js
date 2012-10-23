define( ['backbone', 'highcharts'], function(Backbone, Highcharts) {
	var Chart = Backbone.Model.extend({

		url: function() {
			return App.env.locale + '/problemset/' + this.get('id') + '/stats';
		},

		initialize: function() {
			this.fetch();
		},

		generate: function(placeholder) {

			var marks = _.map(this.get('grades'), function(v) { return v[0]});
			var	freq = _.map(this.get('grades'), function(v){ return v[1]});

			var chart = new Highcharts.Chart({
				chart: {
					renderTo: placeholder,
					type: 'line'
				},
				credits: {
					enabled: false
				},
				title: {
					text: ''
				},
				xAxis: {
					categories: marks
				},
				yAxis: {
					title: {
						text: 'Frequency'
					}
				},
				series: [{
					name: 'grades',
					data: freq
				}]
			});
		}
	});
	return Chart;
});

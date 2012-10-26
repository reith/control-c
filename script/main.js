/*
 * RequireJS main setup
 * don't put it on data-main if layouts using require by themselves.
 */


var require_cfg = {
	// urlArgs: "bust=" + (new Date()).getTime(),
	baseUrl: '/script/',
	paths: {
		'app': 'app',
		'jquery': 'lib/jquery/jquery',
		'livequery': 'lib/jquery/jquery.livequery-min',
		'underscore': 'lib/underscore/underscore',
		'backbone': 'lib/backbone/backbone',
		'highcharts': 'lib/highcharts/highcharts-min'
	},
	shim: {
		'backbone': {
			deps: ['underscore', 'jquery'],
			exports: 'Backbone'
		},
		'underscore': {
			exports: '_'
		},
		'highcharts': {
			deps: ['jquery'],
			exports: 'Highcharts'
		},
		'livequery': {
			deps: ['jquery']
		},
		'app': {
			exports: 'App'
		}
	}
};

if (typeof require_pre_cfg == 'object')
	for (attr in require_pre_cfg )
		require_cfg[attr] = require_pre_cfg[attr];

requirejs.config(require_cfg);
require( ['jquery', 'livequery'], function($) {

	$('div.tr-dir').livequery(function() {
		set_form_from_content( $(this) );
	});

	$('a.entity').livequery(function() {

		var href_pre = App.env.locale;
		var icon = null;
		if( $(this).hasClass('user') ) {
			href_pre += '/user';
			icon = 'user'
		}
		else if( $(this).hasClass('course') ) {
			href_pre += '/course';
			icon = 'book';
		}
		$(this).attr('href', href_pre + $(this).attr('href') );
		$(this).prepend('<i class="icon-'+ icon +'"></i>  ');

	});

	$(function() {
		$loading = $('#loading');
		$.ajaxSetup({ beforeSend: function() { $loading.slideDown('fast') },
			statusCode: {
				404: function() { showError('Error 404') }
			},
			complete: function() {
				$loading.delay(500).slideUp('fast');
				},
			fail: function() { console.log('fail called'); },
			error: function(e,t,x){ console.log('error called'); showError ('<?=_("Sorry, Communication with server faileds.");?>'+x); $loading.slideUp('slow');},
			success: function() {console.log('successs called')},
			});
		jsonError=function(json){if (json.error) { showError (json.error) ; return true }};


		});
});

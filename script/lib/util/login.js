define(['jquery', 'lib/util/login'], function($) {
		return {
			sendForm: function($form) {
				$(document).ready(function() {
						$form.children(':submit').attr('disabled', false);
						$form.submit( function(e) {
								e.preventDefault();
								$.ajax({
									url: $form.attr('action'),
									cache: false, type: 'POST',
									data: $form.serialize(),
									dataType:'json', 
									success: function (json) {
										if (json.error) {
											showError (json.error);
											if (json.errcode=='AUTH' || json.errcode=='SECCODE') {
												$('#captchaImg').attr('src', '/libcc/captcha/captcha.php?'+Math.random());
												$('.captcha').show();
											}
										} 
										else 
											showCongratulation('Welcome', 1, function() { window.location.replace(json.go);} );
										}
								}); //end of ajax
						}); //end of submit
				}); //end of ready

		}
	};
});

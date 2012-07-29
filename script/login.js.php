<?php require "../libcc/session.php"; ?>
<?php require "../libcc/config.php"; ?>

var login=function(form, url){
$(document).ready(function(){
  $(form+' :submit').attr('disabled', 0);
  $(form).submit(function(e){
    e.preventDefault();
    $.ajax({url: url, cache: false, type: 'POST', data: $(form).serialize(), dataType:'json', success: function (json) {
	if (json.error){
	  showError (json.error);
	  if (json.errcode=='AUTH' || json.errcode=='SECCODE') {
	    $('#captchaImg').attr('src', '<?=__url__?>/libcc/captcha/captcha.php?'+Math.random());
	    $('.captcha').show();
	  }
	} else showCongratulation('<?php echo _("Welcome!"); ?>', 1, function() { window.location.replace(json.go);} );
      }
    }); //end of ajax
  }); //end of submit
}); //end of ready
};
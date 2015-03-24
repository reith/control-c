var expanded=true;
$(document).ready(function() {
  var $txtsrc = $('#txtsrc');
  var $isrc = $('#isrc');
  var $sendFile= $('#sendFile');
  var $result = $('#result');
  var $fsrc = $('#fsrc');
  $result.hide();

  $txtsrc.keypress(function(e) {
    if (e.keyCode === 9)
      e.preventDefault();
  });
  $txtsrc.focus(function () {
    if (!expanded) {
      $(this).animate({height: '+=200'}, 50);
      expanded=true;
    }
    
  });
  $isrc.click(function () {
    $sendFile.attr({disabled: 1});
    $txtsrc.show();
  });
  $fsrc.click(function () {
    $sendFile.removeAttr('disabled');
    $txtsrc.hide();
  });
  $('#ff').submit(function(e) {
    $('#loading').removeClass('hidden');
    var frm=document.getElementById('reswp').contentWindow;
    frm.document.body.innerHTML='w';
    var timer=setInterval(function()  {
      if (frm.document.body.innerHTML!='w') {
	$('#loading').addClass('hidden');
	if (expanded) {
	  $txtsrc.animate({height: '-=200'}, 50);
	  expanded=false;
	}
	// $result.html(frm.document.body.innerHTML).show();
	// above should work and was working, now it returns encoded text, don't know why
	$result.html($(frm.document.body).text()).show();
	clearInterval(timer);
      }
    }, 100);
   });
});

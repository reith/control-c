var expanded=true;
$(document).ready(function() {
  $txtsrc=$('#txtsrc');
  $isrc=$('#isrc');
  $sendFile=$('#sendFile');
  $result=$('#result');
  $fsrc=$('#fsrc');
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
    $('#sendFile').attr({disabled: 0});
    $('#txtsrc').hide();
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
	$result.html(frm.document.body.innerHTML).show();
	clearInterval(timer);
      }
    }, 100);
   });
});
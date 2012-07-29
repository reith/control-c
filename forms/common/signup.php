<!--REITH: SIGN UP NEW STUDENT FORM
-->
<style type="text/css">
input[type=text], input[type=password]
{
    width: 100px;
}
</style>
<!--[if IE]>
<script type="text/javascript">
$(document).ready(function() {
    re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
    if (re.exec(navigator.userAgent) != null)
      rv = parseFloat( RegExp.$1 );
    if (rv < 9)
        showError(<?php echo _("This page doesn't rendered good in your browser. try to upgrade it.") ?>);
});
</script>
<![endif]-->
<form id="signupForm" style=" text-align:right; width: 95%">
  <fieldset>
  <legend><?php echo _("Sign up"); ?></legend>
  <table>
    <tr>
    <td width="150px;"><label><?php echo _("Student Nmber"); ?>: </label></td>
    <td><input type="text" name="sn" id="i0" maxlength="7" /><span id='d0' class='vm hide' /></td>
    </tr>
    <tr>
    <td><label><?php echo _("First Name"); ?>: </label></td>
    <td><input type="text" name="fn" id="i1" maxlength="25" /><span id='d1' class='vm hide' /></td>
    </tr>
    <tr>
    <td><label><?php echo _("Last Name"); ?>: </label></td>
    <td><input type="text" name="ln" id="i2"  maxlength="25" /><span id='d2' class='vm hide' /></td>
    </tr>
    <tr>
    <td><label><?php echo _("E-mail");?>: </label></td>
    <td><input type="text" name="email" id="i3" value="" style="width: 200px;"/><span id='d3' class='vm hide' /></td>
    </tr>
    <tr>
    <tr>
    <td><label><?php echo _("Username")?>: </label></td>
    <td><input type="text" name="un" id="i4" maxlength="15" /><span id='d4' class='vm hide' /></td>
    </tr>
    <tr>
    <td><label><?php echo _("Password")?>: </label></td>
    <td><input type="password" name="psw" id="i5" /><span id='d5' class='vm hide' /></td>
    </tr>
    <tr>
    <td><label><?php echo _("Repeat Password") ?>: </label></td>
    <td><input type="password" name="pswc" id="i6" /><span id='d6' class='vm hide' /></td>
    <tr/>
    <tr>
    <td><label><?php echo _("Group") ?>: </label></td>
    <td><input type="checkbox" value="1" id="prv_s" name="prv_s" checked=1><?php echo _("Student"); ?></input>
        <input type="checkbox" value="1" name="prv_c"><?php echo _("Contestant"); ?></input>
        <input type="checkbox" value="1" name="prv_t"><?php echo _("Teacher"); ?></input>
        <input type="checkbox" value="1" name="prv_ca"><?php echo _("Contest Admin"); ?></input>
    </td>
    </tr>
  </table>
    <input type="submit" id="submit" disabled=1 value="<?php echo _("Sign up"); ?>"/>
  </fieldset>
</form>

<script type="text/javascript">

validate = function(n,e) {
  switch (n) {
    case 0: if ( /^[1-9]+[0-9]{6}$/.test(e.value) ) return true;
    $('#d'+n).html("شماره دانشجویی عددی هفت رقمی است"); return false;

    case 1:
    case 2: if (/^[^a-zA-Z]+$/.test(e.value) && e.value.replace(/^\s*(.*?)\s*$/, "$1").length>0) return true;
    $('#d'+n).html("از حروف فارسی برای پر کردن فیلد استفاده کنید"); return false;

    case 3: if (/^\w+(?:\.[\w-]+)*@(?:[\w-]+\.)+[a-zA-Z]+$/.test(e.value)) return true;
    $('#d'+n).html("آدرس وارد شده معتبر نیست"); return false;

    case 4: if (e.value===null) return; var res=0;
	ajaxArg={url:'<?=Routing::genProc("check_username")?>', type:'post', data:'un='+e.value, dataType:'json', async:false};
	ajaxArg.success=function(j){j.error?showError(j.error):res=j.s};
	$.ajax(ajaxArg);
	switch (res) {case 'r': $('#d'+n).html("قبلا این نام کاربری انتخاب شده است"); return false; case 'n': return true};
	return true; //if error, bypass this...

    case 5: if (e.value.replace(/^\s*(.*?)\s*$/, "$1").length>=6) return true;
    $('#d'+n).html("کلمه‌ی عبور امن‌تری انتخاب کنید"); return false;

    case 6: if (e.value == $('#i'+(n-1)).val()) return true;
    $('#d'+n).html("کلمه‌ی عبور با تکرار آن مطابقت ندارد"); return false;
  }
}

$(document).ready(function(){

  formerr=127;
  $('#prv_s').click(function() {
    if ($(this).is(':checked'))
    {
        $('#i0').attr({'disabled':0});
        formerr|=1;
    }
    else
    { 
        $('#i0').attr({'disabled':1});
        formerr&=127-1;
    }
  });

  $.each($('#signupForm :input'), function (n,e) {
    $(this).bind('change', function() {
      if ( n<7 )
        if (validate(n, e)) {
            formerr&=127-(1<<n);
            $('#i'+n).removeClass('error').addClass('grntxt');
            $('#d'+n).hide();
        } else {
            formerr|=(1<<n);
            $('#d'+n).addClass('error').show();
            $('#i'+n).removeClass('grntxt');
        };
      ( formerr != 0 ) ? $('#submit').attr({'disabled': 1}) : $('#submit').attr({'disabled': 0});
    })
  });

  $('#signupForm').submit(function (e) {
    e.preventDefault();
    $.ajax({type: 'POST', url: '<?=Routing::genProc('signup');?>)', data: $('#signupForm').serialize(), dataType: 'json',
	    success: function (j) {
	      if (j.error != null )
		showError(j.error);
	      else
		showCongratulation (j.msg, 2, function() { window.location.replace('<?=Routing::genURL('signin', true)?>'); });
	  }});
  });
})
</script>
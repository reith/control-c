<!--REITH: STUDENT SIGN IN FORM-->
<?php
if (signedIn())
  header("Location: ".__url__."/home");

$captchaStyle="";

if (!isset($_SESSION['failed_login']))
      $captchaStyle='style="display: none;"';
?>
<script src='/script/login.js'></script>
  <form id='loginForm' action="<?=Routing::url('login')?>" method="post">
  <fieldset>
  <legend><?php echo _("Login"); ?></legend>
  <table style="width: 400px;">
    <tr><td><label><?php echo _("Username"); ?>: </label></td>
    <td><input dir="ltr" name="un" type="text" size="15" /></td></tr>
    <tr><td><label><?php echo _("Password"); ?>: </label></td>
    <td><input dir="ltr" name="pw" type="password" value="" size="15"/></td></tr>
    <tr class="captcha" <?php echo $captchaStyle; ?>>
	  <td colspan="2"><label><?php _("Please enter the code in the image"); ?><label></td></tr>
    <tr class="captcha" <?php echo $captchaStyle; ?>>
	  <td><img src="/libcc/captcha/captcha.php" id="captchaImg"></td>
	  <td><input dir='ltr' name="captcha" type="text" autocomplete="off" size=15></td>
    </tr>
  </table><br />


  </fieldset>
    <input type="submit" value="<?php echo _("Login"); ?>" disabled=1 />
</form>
    <ul style="font-size: smaller; margin-top: 20px;">
    <li><?php echo _("Have not any account yet?")?>&nbsp;<a href='<?=Routing::url('signup')?>' ><?php echo _("Sign up");?></a></li>
    <li><?php echo _("Forgot your password?") ?>&nbsp;<a id="add_recovery_link" href="javascript:void(0)"><?php echo _("Password recovery");?></a></li>
    </ul>
<div id="password_recovery_wrapper" style="margin-top: 20px; display: none;"><?php require __root__."/forms/common/password_recovery.php"; ?></div>

<script type="text/javascript">


$(document).ready(function () {
login($('#loginForm'));
 $('#add_recovery_link').click(function () {
        $('#add_recovery_link').remove();
        $('#password_recovery_wrapper').show('slow');
  });
    
});

</script>

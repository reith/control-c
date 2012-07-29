<!--REITH: ADMINISTRATOR SIGN-IN FORM-->
<?php
  if(checkSignIn("a"))
    header("Location: ".$siteURL."?wa&g=home");
?>
<form action="javascript:sendForm('./admin.signin.php', 'formChild', './?go=home');">
  <fieldset>
    <legend>ورود</legend>
    <label>نام کاربری:  </label> 
    <input type="text" name="formChild" size="10" value=""/>
    <br/>
    <label>کلمه‌ی عبور: </label>
    <input type="password" name="formChild" value="" size="10"/>
    <br/>
    <input type="submit" value="ورود" />
  </fieldset>
</form>
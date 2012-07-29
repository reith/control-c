<!--REIHT: ADMINISTRATOR TEACHER MANAGEMENT-->
<?php
  if (!checkSignIn("a")) header("Location: ".$siteURL."?who=admin&go=signin");
?>
  <form action="javascript:sendForm('./admin.add.teacher.php', 'addFormChild');" method="post">
    <fieldset>
      <legend>اضافه کردن استاد</legend>
      <label>نام:</label>
      <input type="text" name="addFormChild" size="15" maxlength="30"/>
      <br/>
      <label>نام خانوادگی:</label>
      <input type="text" name="addFormChild" size="15" maxlength="30"/>
      <br/>
      <label>پست الکترونیک:</label>
      <input type="text" name="addFormChild" size="20" maxlength="30"/>
      <br/>
      <label>کلمه‌ی عبور:</label>
      <input type="password" name="addFormChild" size="15"/>
      <br/>
      <label>تکرار کلمه‌ی عبور:</label>
      <input type="password" name="addFormChild" size="15"/>
      <br/>
      <input type="submit" value="اضافه شود"/>
    </fieldset>
  </form>
<br/>
<br/>
<form action="javascript:sendForm('./admin.del.teacher.php', 'delFormChild');" method="post">
  <fieldset>
    <legend>حذف استاد</legend>
    <label>شناسه</label>
    <input type="text" name="delFormChild" maxlength="3" size="3"/>
    <br/>
    <input type="submit" value="حذف شود"/>
  </fieldsey>
</form>
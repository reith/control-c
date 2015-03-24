<!--REITH: TEACHER'S FORM FOR MANAGE MEMBERSHIP REQUESTS-->
<?php
  signInFirst("t");
?>
<!-- Box... -->
<?php
$errors="";
if (!checkNewMembershipRequest() and empty($errors))
  echo "در  این لحظه در خواست جدیدی برای شرکت در دروس شما وجود ندارد";
elseif(!empty($errors))
  showAlert ("error", "خطا در چک کردن درخواست عضویت رخ داد: <br/> $errors", 0, null, "./?g=home");
else
{
?>
  <form id="tableForm" action="<?=Routing::genProc('teacher_membership_requests')?>">
	<input type='hidden' id='vm_action' value='<?=Routing::genProc('teacher_verify_membership')?>' />
    <fieldset>
    <legend>نمایش درخواست‌ها</legend>
    <label>درخواست‌های </label>
    <select name="course" size="1">
      <option value="all" selected="1">تمام دروس</option>
      <?php ownCoursesMenu(); ?>
    </select>
    <label> را مرتب شده بر اساس </label>
    <select name="sort" size="1">
      <option value="id">شناسه‌ی درخواست</option>
      <option value="course" selected="1">نام درس</option>
      <option value="firstName">نام متقاضی</option>
      <option value="lastName">فامیل متقاضی</option>
      <option value="number">شماره‌ی دانشجویی</option>
    </select>
    <label> به صورت </label>
    <select name="order" size="1">
      <option value="ASC" selected="1">صعودی</option>
      <option value="DESC">نزولی</option>
    </select>
    <input type="submit" value="نشان بده"/>
  </fieldset>
</form>

<form id="manageBox" class='hidden'>
  <fieldset>
    <legend>حذف و تایید درخواست‌ها</legend>
    <table class="monitor" id="tableMonitor" style="width:90%; left:10%;" border="1"></table>
    <div class="comment"> لغو: بررسی به درخواست بعدا انجام می‌شود <br />حذف: درخواست مورد پذیرش نیست <br />مسدود: حذف شود و دیگر درخواستی از دانشجو ثبت نشود</div>
    <br/>
    <input type="submit" value="انجام" />
  </fieldset>
</form>

<script type="text/javascript">
require(['jquery', 'lib/util/tableForm', 'lib/util/teacherMembershipRequests'], function($, TableForm, clbks) {
$(document).ready(function () {
  var retrieveTabe = new TableForm('tableForm', 'tableMonitor');
  $("#tableForm").submit(function(e) {
    e.preventDefault();
    retrieveTabe.submitForm(e, $('#tableForm').attr('action'), "#tableForm");
  });
  $('#manageBox').submit(function(e) {
    e.preventDefault();
    $.ajax ({type: 'POST', cache: false, dataType:'json', url: $('#vm_action').val(),
	    data: $('#manageBox').serialize(),
	    success: function(j) {return clbks.receive(j);}
	    });
  });
});
});
</script>
<?php } ?>

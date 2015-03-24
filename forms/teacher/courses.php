	<!--REITH:
	this panel shows teacher or all courses.-->
<?php
	require_once 'libcc/general.functions.php';
	signinFirst('t');
?>
<script type="text/javascript" src="script/TableForm.js.php"></script>
<form id="tableForm">
  <fieldset>
    <legend>نمایش دروس</legend>
    <select name="view" size="1">
    <option value="all" >تمام دروس</option>
    <option value="own" selected="1">دروس خود</option>
    </select>
    <label> را مرتب شده بر اساس </label>
    <select name="sort" size="1">
	<option value="id">شناسه‌</option>
	<option value="name" selected="1">نام درس</option>
	<option value="teacherF">نام استاد</option>
	<option value="teacherL">فامیل استاد</option>
	<option value="lock">وضعیت</option>
    </select>
    <label> به صورت </label>
    <select name="order" size="1">
	<option value="ASC" selected="1">صعودی</option>
	<option value="DESC">نزولی</option>
    </select>
    <label> و در هر صفحه</label>
    <input type="text" size="2" maxlength="2" value="10" name="limit" id="limit" />
    <label> رکورد را </label>
    <input type="submit" value="نشان بده"/>
  <table id="tableMonitor" class="monitor" dir="rtl" style="width:90%; left:10%;" border="1" align="center"></table>
  </fieldset>
</form>

<form id='ecf'>
  <fieldset>
    <legend>عملیات روی درس</legend>
    <label>درس شماره</label>
    <input type="text" size="3" maxlength="3" id="courseID" name="course" />
    <label>را</label>
    <select name="action">
      <option value="active">فعال</option>
      <option value="lock" selected="1">قفل</option>
      <option value="delete" disabled="1">حذف</option>
    </select>
    <input type="submit" value="کن"/>
    
    <label>یا در این درس تمرین</label>
    <input type="button" value="اضافه کن" onclick="location='/exercises?addto='+document.getElementById('courseID').value;"/>
  </fieldset>
</form>

<form id='ac'>
  <fieldset>
    <legend>اضافه کردن درس</legend>
    <label>نام درس: </label>
    <input type="text" maxlength="40" size="20" name="cn" />
    <label>زبان برنامه‌سازی: </label>
    <select class='en' name="lang">
      <option value="c">ANSI C</option>
      <option value="c++">C++</option>
      <option value="pas">Pascal</option>
    </select>
    <input type="submit" value="اضافه کن"/>
  </fieldset>
</form>

<script type="text/javascript">
TableForm.prototype.customOperations = function() {
  var tjson=this.json;
  var $courseID = $('#courseID');
  var $table = $('#tableMonitor');
  $.each(tjson.tr, function(i,tr) {
    $('<tr />').html(tr.r).data('id', tr.id).appendTo($table);
  });
  $('#tableMonitor tr td:nth-child(1)').addClass('action_td').click(function(){
	$courseID.val( $(this).parent().data('id') );
   });
}


$(document).ready(function () {
  var retrieveTabe = new TableForm('tableForm', 'tableMonitor');
  $('#ac').submit(function (e) {
    e.preventDefault();
    $.ajax({url: '<?=Routing::genProc('add_course')?>', type:'POST', data:$('#ac').serialize(), dataType: 'json', success: function(json) { json.error!=null ? showError (json.error) : showCongratulation(json.message, 10); } });
  });
  $('#tableForm').submit(function(e) {
    retrieveTabe.submitForm(e, '<?=Routing::genProc('teacher_courses')?>', "#tableForm");
  }); //submit
  
  $ecf = $('#ecf');
  $ecf.submit(function(e){
    e.preventDefault();
    $.ajax({
      url: '<?=Routing::genProc('edit_course')?>',
      type: 'POST',
      data: $ecf.serialize(),
      dataType: 'json',
      success: function (json) { json.error!=null ? showError (json.error) : showAlert('s', json.message, 3); },
    });
  })
}); //doc
</script>

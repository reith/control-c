	<!--REITH:
	working with students for teachers.-->
<?php
  require_once 'libcc/general.functions.php';
  signInFirst("t");
  $errors="";
?>
		<!-- Box... -->
<form id='tableForm'>
  <fieldset>
    <legend>نمایش دانشجو</legend>
    <label>دانشجوهای عضو</label>
    <select name="course" id="course" size="1">
      <option value="all" >تمام دروس</option>
      <option value="own" selected="1">دروس خود</option>
      <option value="">======</option>
      <?php ownCoursesMenu(); ?>
    </select>
    <label> را مرتب شده بر اساس </label>
    <select name="sort" id="sort" size="1">
      <option value="sNumber" selected=1>شماره دانشجویی</option>
      <option value="sLastName">فامیلی دانشجو</option>
      <option value="sFirstName">نام دانشجو</option>
      <option value="cName">نام درس عضو</option>
      <option value="mGrade">نمره</option>
    </select>
    <label> به صورت </label>
    <select name="order" id="order" size="1">
	<option value="ASC" selected="1">صعودی</option>
	<option value="DESC">نزولی</option>
    </select>
    <input type="submit" value="نشان بده"/>
  <table class="monitor" id="tableMonitor" style="width:90%; left:10%;" border="1"></table>
  </fieldset>
</form>

<!--
<form action="javascript:sendForm('./teacher.edit_course.php', 'editCourseForm');" >
  <fieldset>
    <legend>عملیات روی عضو</legend>
    <label>درس شماره‌ی </label>
    <input type="text" size="3" maxlength="3" id="courseID" name="editCourseForm" />
    <label>را</label>
    <select name="editCourseForm">
      <option value="delete">حذف</option>
      <option value="active">فعال</option>
      <option value="lock" selected="1">قفل</option>
    </select>
    <input type="submit" value="کن"/>
  </fieldset>
</form>
-->

  <fieldset>
    <legend>نمایش نتایج تمرینات</legend>
    <label>شناسه‌ی دانشجو</label>
    <input type="text" size="5" id="studentID" />
    <label>شناسه‌ی درس</label>
    <input type="text" size="3" id="courseID" />
    <input type="button" id="studentLogBtn" value="نشان بده"/>
  </fieldset>

<script type="text/javascript">
require(['jquery', 'lib/util/tableForm'], function($, TableForm) {
	var $studentID = $('#studentID');
	var $courseID = $('#courseID');

	TableForm.prototype.customOperations = function() {
	  var tjson=this.json;
	  $.each(tjson.tr, function(i,tr) {
	    $('<tr />').html(tr.r).data('id', tr.id).data('cid', tr.cid).appendTo('#tableMonitor');
	  });
	  $('#tableMonitor tr td:nth-child(1)').addClass('action_td').click(function(){
		$courseID.val( $(this).parent().data('cid') );
		$studentID.val( $(this).parent().data('id') );
	   });
	}

	$(document).ready(function () {
	  var retrieveTabe = new TableForm('tableForm', 'tableMonitor');
	  $('#tableForm').submit(function(e) {
	    retrieveTabe.submitForm(e, '<?=Routing::genProc('teacher_students')?>', '#tableForm');
	  }); //submit

	  $('#studentLogBtn').click(function(e) {
	    e.preventDefault();
	    window.location = '/' + App.env.locale + '/logs?course=' + $courseID.val() + '&student=' + $studentID.val();
	  });

	}); //doc
});
</script>

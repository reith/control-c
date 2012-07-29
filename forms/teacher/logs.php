<!-- REITH: SHOWS HOMEWORK LOGS TO TEACHERS -->
<?php
  signinFirst('t');
?>
<script type="text/javascript" src="js/tableForm"></script>
<form id="tableForm" name="amir">
  <fieldset>
    <label>نتایج</label>
    <legend>جستجو</legend>
    <select name="view" id="view">
      <option value="single">تمرینات مجزا</option>
      <option value="seri">سری تمرینات</option>
    </select>
    <label> دانشجویان عضو </label>
    <select name="course" id="course">
      <option value="all">تمام دروس خود</option>
      <?php ownCoursesMenu(); ?>
    </select>
    <label> را مرتب شده بر اساس </label>
    <select name="sort" id="sort" size="1">
      <option value="studentL">اسم فامیل دانشجو</option>
      <option value="studentF">نام دانشجو</option>
      <option value="courseName" selected="1">نام درس</option>
      <option value="seriNum">سری تمرین</option>
    </select>
    <label> به صورت </label>
    <select name="order" id="order" size="1">
	<option value="ASC" selected="1">صعودی</option>
	<option value="DESC">نزولی</option>
    </select>
    <label> و در هر صفحه</label>
    <input type="text" size="2" maxlength="2" value="10" name="limit" id="limit" />
    <label> رکورد را </label>
    <input type="submit" value="نشان بده"/>
    <table id="tableMonitor" class="monitor" style="width:90%; left:10%;" border="1"></table>
    <div id="result" class="resultBox">
      جاوا اسکریپت باید فعال باشد.
    </div>
  </fieldset>
</form>

<script type="text/javascript" >
processResult = function(j) {};

TableForm.prototype.customOperations = function () {
    tjson=this.json; //table json data
    table=this; //TableForm object
    $result=$('#result');
    (tjson.type=='seri') ?
    //j argument is json data received
    processResult = function(j) {
      t=j.time==null?'دریافت نشده':j.time;
      g=j.grade==null?'تصحیح نشده':j.grade;
      switch (j.zip)
      {
	case 'd': g+='<strong class="redtxt"> [ تقـلب ]</strong>'; break;
	case 'n': g+='<strong> [ فایل زیپ ارسال نشده ]</strong>'; break;
	case 'e': g+='<strong class="redtxt"> [ فایل زیپ ارسال شده خراب است ]</strong>'; break;
      }
      j.s==null ?
	$result.html('زمان دریافت تمرین: ' + t + '<br />' + 'نمره‌ی کل سری: ' + g)
	:
	$result.html('<strong class="redtxt">'+j.s+'</strong>');
      } :
    processResult = function(j) {
      if (j.s==null)
      {
	var grade=j.grade;
	
	if (j.cheat==1)
	  grade='<strike>'+grade+'</strike><strong class="redtxt"> [ تقلب تشخیص داده شد. نمره ۱۰۰- داده شد. ]</strong>';
	
	$result.html('نمره‌ی تمرین: '+ grade + '<br />' + 'خطای کمپایل: ').append(j.compileError==='0'?'خیر':'بله')
	.append('<br />' + 'زمان اجرا: ' + j.time + '<br />' + 'خطای زمان اجرا: ').append( j.re.length?'برای ورودی‌های '+ j.re.join(','):'خیر');
      }
      else
	$result.html('<strong class="redtxt">'+j.s+'</strong>');
    }

    $.each(tjson.tr, function(i, tr) { //now iterate on table rows from table json data
      $('<tr/>').html(tr.r).click(function(){
	$(this).appendTo(table.$table);
	$result.slideDown('slow').html('لطفا کمی صبر کنید...');

	var data= {student: tr.s, type: tjson.type, id: tr.id};

	$.ajax({
	type: "POST",
	url: '<?=Routing::genProc('view_detailed_logs')?>',
	dataType: "json",
	data: data,
	success: function (j){processResult(j)}
	});
      }).appendTo(table.$table);
    });
    $result.width($('#tableMonitor tr:first').width()-20);
}

$(document).ready(function()
{
  retrieveTabe = new TableForm('tableForm', 'tableMonitor');
  $result = $('#result');
  $result.html('لطفا کمی صبر کنید...').hide();
  $("#tableForm").submit(function(e){
    $result.fadeOut('normal');
    retrieveTabe.submitForm(e, '<?=Routing::genProc('teacher_logs')?>', "#tableForm");
  });
});
</script>
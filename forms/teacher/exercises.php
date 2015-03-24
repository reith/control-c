  <!--REITH:
  this panel shows teacher seri exercises.-->
<?php
	require_once 'libcc/general.functions.php';
  signInFirst("t");
  $errors="";
?>

<link rel="alternate stylesheet" type="text/css" media="all" href="<?=__jalcal_url__?>/skins/calendar-green.css" title="green" />
<script type="text/javascript" src="<?=__jalcal_url__?>/jalali.js"></script>
<script type="text/javascript" src="<?=__jalcal_url__?>/calendar.js"></script>
<script type="text/javascript" src="<?=__jalcal_url__?>/calendar-setup.js"></script>
<script type="text/javascript" src="<?=__jalcal_url__?>/lang/calendar-fa.js"></script>
<script type="text/javascript">
  var oldLink = null;
  function setActiveStyleSheet(link, title) {
  var i, a, main;
  for(i=0; (a = document.getElementsByTagName("link")[i]); i++) {
    if(a.getAttribute("rel").indexOf("style") != -1 && a.getAttribute("title")) {
    a.disabled = true;
    if(a.getAttribute("title") == title) a.disabled = false;
    }
  }
  if (oldLink) oldLink.style.fontWeight = 'normal';
  oldLink = link;
  if (link.style) link.style.fontWeight = 'bold';
  return false;
}
</script>
<?php
$listCoursesQuery="SELECT `id`, `name` FROM `$dbCourseTable` WHERE teacher='{$_SESSION['id']}'";
if (!isset($_GET['addto']))
{
$listCoursesQuery.=";";
?>
<form id="tableForm" >
  <fieldset>
    <legend><?=_('Show Exercises')?></legend>
    <select name="view" size="1">
      <option value="single"><?=_('Single Exercises')?></option>
      <option value="seri"><?=('Exercise Series')?></option>
    </select>
    </select>
    <select name="course" size="1">
      <option value="all" ><?=_('All Courses')?></option>
      <option value="own" selected="1"><?=('Own Courses')?></option>
      <option value="">======</option>
    <?php
    //GET LIST OF COURCES
    ownCoursesMenu();
    ?>
    </select>
    <label><?=_('Sorted By')?></label>
    <select name="sort" size="1">
	<option value="courseName" selected="1"><?=_('Course Name')?></option>
	<option value="seriNum"><?=_('Seri Number')?></option>
	<option value="teacherF">نام استاد</option>
	<option value="teacherL">فامیل استاد</option>
	<option value="cDate">تاریخ ساخت</option>
	<option value="dDate">موعد تحویل</option>
	<option value="expire">وضعیت انقضا</option>
    </select>
    <label> به صورت </label>
    <select name="order" size="1">
	<option value="ASC" selected="1">صعودی</option>
	<option value="DESC">نزولی</option>
    </select>
    <input type="submit" value="نشان بده"/>
    <div class='error' id='nldlg'>لطفا کمی صبر کنید...</div>
    <table class='monitor' id="tableMonitor" style="width:90%; left:10%;" border="1"></table>
  </fieldset>
</form>
<!--
<form>
  <fieldset>
    <legend>عملیات روی تمرین</legend>
    <label>تمرین شماره‌ی </label>
    <input type="text" size="3" maxlength="3" id="courseId" name="editCourseForm" />
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
<?php
}
else	//don't show all courses
  $listCoursesQuery.=" AND id='{$_GET['addto']}';"
?>

<form action="/<?=$env->locale()->name(); ?>/add_exercise" method="POST" >
  <fieldset>
    <legend>اضافه کردن سری تمرین</legend>
    <label>درس: </label>
    <select name="courseID" id="courseID" size="1">
      <?php
      $dbRes = execQuery($listCoursesQuery);
      if ($dbRes)
      {
	while($row = $dbRes->fetch_row())
	  echo "<option value='$row[0]'>$row[1]</option>";
      }
      ?>
    </select>
    <label>تعداد تمرین‌ها: </label>
    <input type="text" size="2" maxlength="2" name="exerciseCount">
    <label> ضریب سری: </label>
    <input type="text" value="1" size="2" maxlength="2" name="seriWage"><br/>
    <label>مهلت تحویل:  </label>
    <input type="text" value="" name="deadlineDate" id="deadlineDate" size=15>
    <img id="cal_1" src="<?=__jalcal_url__?>/cal.png" style="vertical-align: top;" />
    <script type="text/javascript">
      Calendar.setup({
      inputField     :    "deadlineDate",
      button         :    "cal_1",
      ifFormat       :    "%Y-%m-%d %H:%M",
      showsTime      :    true,
      dateType	   :	'jalali',
      timeFormat     :    "24",
      weekNumbers    : false,
      ifDateType : 'gregorian'});
    </script><br/>
    <label>زمان تصحیح: </label>
    <input type="text" value="" name="correctionDate" id="correctionDate" size="15"/>
    <img id="cal_2" src="<?=__jalcal_url__?>/cal.png" style="vertical-align: top;" />
    <script type="text/javascript">
      Calendar.setup({
      inputField     :    "correctionDate",
      button         :    "cal_2",
      ifFormat       :    "%Y-%m-%d %H:%M",
      showsTime      :    true,
      dateType	   :	'jalali',
      timeFormat     :    "24",
      weekNumbers    : false,
      ifDateType : 'gregorian'});
    </script>
    <input type="submit" value="اضافه کردن تمرینات" />
  </fieldset>
</form>

<?php
if (!empty($errors))
  showMsg("<div class='errorText'>"."خطاهای زیر در نمایش این صفحه رخ داد: <br/> $errors </div>"."<br/> لطفا خطا را با مسئول در میان بگذارید.",
  "./home");
?>
<script type="text/javascript">
require(['jquery', 'lib/util/tableForm'], function($, TableForm) {
	TableForm.prototype.customOperations = function() {
		var tjson=this.json;
		$.each(tjson.tr, function(i,tr) {
			$('<tr />').html(tr.r).click(function(){
				$('#courseId').val(tr.id);
			}).appendTo('#tableMonitor');
		});
	};

	$(document).ready(function(){
		setActiveStyleSheet(this, 'green');
		retrieveTabe = new TableForm('tableForm', 'tableMonitor', $('#nldlg'));
		$('#tableForm').submit(function(e){
			retrieveTabe.submitForm(e, '<?=Routing::genProc('teacher_exercises')?>', '#tableForm');
		});

		//show new exercises
		$('#tableForm').trigger('submit');
	});
});
</script>

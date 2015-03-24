<!--REITH: VIEW COURSE FOR STUDENTS-->
<?php
	require_once 'libcc/general.functions.php';
  signinFirst('s');
?>

<!-- <script type="text/javascript" src="/script/lib/util/tableForm.js"></script>
-->
<script type="text/javascript" src="/script/student/exercises.js.php"></script>

<iframe name="if" id="if" style="display: none;">
</iframe>
<form id='stf'>
  <fieldset>
  <legend><?=_("Search for exercises")?></legend>
  <select name="view" size="1">
    <option value="single" selected="1"><?=_("Single Exercises");?></option>
    <option value="seri"><?=_("Exercise Series")?></option>
  </select>
    <select name="expired" size="1">
    <option value="no" ><?=_("New")?></option>
    <option value="yes"><?=_("Old")?></option>
    <option value="all" selected="1"><?=_("All")?></option>
  </select>
  <label><?=_("and")?></label>
  <select name="solved" size="1">
    <option value="yes"><?=_("Solved")?></option>
    <option value="no" ><?=_("Not Solved")?></option>
    <option value="all" selected="1"><?=_("All")?></option>
  </select>
  <label><?=_("In")?></label>
  <select name="course" size="1">
    <option value="all"><?=_("All Courses")?></option>
    <option value="own" selected="1"><?=_("Registered Courses")?></option>
    <option value="">======</option>
    <?php ownCoursesMenu(); ?>
  </select>
    <label> <?=_("Sorted On")?> </label>
  <select name="sort" size="1">
    <option value="eSeri"><?=_("Exercise Seri")?></option>
    <option value="cName"><?=_("Course Name")?></option>
    <option value="cDate"><?=_("Creation date")?></option>
    <option value="dDate" selected="1"><?=_("Deadline Date")?></option>
    <option value="expired"><?=_("Course Status")?></option>
  </select>
  <label> <?=_("By")?> </label>
  <select name="order" size="1">
    <option value="ASC"><?=_("Ascending")?></option>
    <option value="DESC" selected="1"><?=_("Descending")?></option>
  </select>
  <input type="submit" value="<?=_("Show")?>" />

  <table id="stm" class='monitor' style="width:90%; left:10%;" align="center"></table>
  <div class="error" id='res'></div>  
  </fieldset>
</form>
<?=jsConfig();?>

<script type="text/javascript">
require(['lib/util/studentExercisesTable'], function(build) {
	console.log('build', build);
	build('stf', 'stm', '<?=Routing::url("problemset")?>/', '<?=Routing::url("exercise")?>/', '<?=Routing::url("course")?>/', '<?=Routing::genProc('view_detailed_logs')?>', '<?=__url__?>/?prc=send_exercise', '<?=Routing::genProc('student_exercises')?>')
});

</script>

<!--REITH: VIEW COURSE FOR STUDENTS-->
<?php
  signinFirst('s');
?>

<script type="text/javascript" src="js/tableForm"></script>
<script type="text/javascript" src="js/student.exercises"></script>

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

<script type="text/javascript">buildStudentExercisesForm('stf', 'stm', '<?=Routing::genURL("seri")?>/', '<?=Routing::genURL("exercise")?>/', '<?=Routing::genURL("course")?>/', '<?=Routing::genProc('view_detailed_logs')?>', '<?=__url__?>/?prc=send_exercise', '<?=Routing::genProc('student_exercises')?>')</script>
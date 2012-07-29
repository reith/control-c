<!--REITH: VIEW COURSE FOR STUDENTS-->
<?php
  signinFirst('s');
?>

<script type="text/javascript" src='js/tableForm'></script>
<script type="text/javascript" src='script/student/courses.js'></script>

<form id="ctf">
  <fieldset>
  <legend>نمایش دروس</legend>
  <select name="view" size="1">
    <option value="nreg">دروس عضو نشده</option>
    <option value="reg">دروس عضو شده</option>
    <option value="all" selected="1">تمام دروس</option>
  </select>
    <label> را مرتب شده بر اساس </label>
  <select name="sort" size="1">
    <option value="id" selected="1">شناسه</option>
    <option value="name">درس</option>
    <option value="teacherF">استاد (نام)</option>
    <option value="teacherL">استاد (فامیل)</option>
    <option value="year">سال</option>
    <option value="lock">وضعیت درس</option>
  </select>
  <label> به صورت </label>
  <select name="order" size="1">
    <option value="ASC" selected="1">صعودی</option>
    <option value="DESC">نزولی</option>
  </select>
  <input type="submit" value="نشان بده"/>
<table id="ctm" class="monitor" style="width:90%; left:10%;" align="center"></table>  
  </fieldset>
</form>
<form id="cmr">
  <fieldset>
    <legend>عملیات برای درس</legend>
    <label>‌برای درس شماره‌ی </label>
    <input type="text" id='courseId' size="3"/>
    <label>درخواست عضویت</label>
    <input type="submit" value="بفرست"/>
  </fieldset>
</form>

<?php
jsConfig( array ('form'=>'ctf', 'table'=>'ctm', 'send'=>'cmr', 'course_id'=>'courseId', 'std_crs_action'=>Routing::genProc('student_courses'), 'std_join_crs_action'=>Routing::genProc('student_join_course')) );
?>
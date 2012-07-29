<!--REITH: TEACHER ADD EXERCISE-->
<?php
  signInFirst('t');
//   require "./config.php";
  if (!$con=newMySQLi())
    die('خطا در برقراری ارتباط با پایگاه داده');
?>
<!-- Box... -->
<script type="text/javascript" src="js/teacher.add_exercise"></script>
<script type="text/javascript">
$(document).ready(function () {
  $('#f').submit(function(e){
    e.preventDefault();
    $('#loading').removeClass('hidden');
    $.ajax({url: '<?=Routing::genProc('teacher_add_exercise')?>', type: 'POST', dataType:'json', data: $('#f').serialize(), success: function(j) {
      if (j.error)
	showError ('<ul>'+j.error+'</ul>');
      else
	showMessage ('<ul>'+j.msg+'</ul>');
    }
    });
  });
});
</script>
<?php
$errors="";

$courseID = $_POST['courseID'];
$exerciseCount = $_POST['exerciseCount'];
$seriWage = $_POST['seriWage'];
$deadlineDate = $_POST['deadlineDate'];
$correctionDate = $_POST['correctionDate'];
$seriCount="";


if ($courseID == "" || $exerciseCount == "" || $deadlineDate == "" || $correctionDate =="")
  dieJSON('EMPFRM');

// get seriCount
//next query do 2 things:
//1. get seriCount,
//2. validate ownership if course id, so don't join it to next query.

($stmt=$con->prepare("SELECT  `seriCount` FROM `$dbCourseTable` WHERE `id`=? AND `teacher`=?")) || errorLogger("DB", $con->error, true);
$stmt->bind_param("dd", $courseID, $_SESSION['id']);
($stmt->execute()) || errorLogger("DB", $stmt->error, true);
$stmt->bind_result($seriCount);
if ($stmt->fetch())
  $seriCount++;
else
{
  dieJSON('DB');
}

$stmt->close();
$con->close();

 $now = date("Y-m-d H:i:s");
?>
<form id="f" name='exDetails'/>
  <input type='hidden' name='ExC' value='<?php echo $exerciseCount ?>'/>
  <input type='hidden' name='sriW' value='<?php echo $seriWage ?>'/>
  <input type='hidden' name='ExS' value='<?php echo $seriCount ?>'/>
  <input type='hidden' name='crsID' value='<?php echo $courseID ?>'/>
  <input type='hidden' name='nDate' value='<?php echo $now ?>'/>
  <input type='hidden' name= 'dDate' value='<?php echo $deadlineDate ?>'/>
  <input type='hidden' name= 'cDate' value='<?php echo $correctionDate ?>'/>
  <script type="text/javascript" >
    for (var i=1; i<=document.getElementsByName('ExC')[0].value;i++)
    addExercise(i);
  </script>
  <fieldset>
    <legend>توضیحات</legend>
    <div>
می‌توانید توضیحی در مورد سری تمرین اضافه کنید؛ توضیحاتی در مورد گزینه‌های تصحیح می‌تواند مفید باشد: 
    </div>
    <br />
    <textarea rows='4' cols='60' name="seriComment"></textarea>
  </fieldset>
  <input type="submit" value="انجام علیات" />
</form>
<br/>
<br/>

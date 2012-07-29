<?php
require_once 'libcc/db.class.php';

$errors="";
$course=$_REQUEST['id'];
if (empty($course) || (int)$course!=$course)
  redirect404();
else
{
  $con = DB::instance();
  $stmt = $con->prepare( 'call get_course_data(?)' );
  $stmt->bindParam(1, $course, PDO::PARAM_INT);
  
  if (! $stmt->execute() )
      errorLogger('DB', $con->error.' IN: '.$query, true);
      
  if (! ($courseInfo = $stmt->fetch(PDO::FETCH_OBJ)) )
      redirect404();
}

echo<<<EOF
  <fieldset>
    <legend>
      درس $courseInfo->name
    </legend>
    <label>نام استاد: </label><a href="$siteURL/profile/{$courseInfo->teacher}">{$courseInfo->teacherName}</a><br />
    <label>تعداد تمرینات: </label> $courseInfo->seriCount <br />
    <label>زبان برنامه نویسی: </label> <span dir='ltr'>{$courseInfo->language}</span> <br />
    <label>تعداد دانشجویان: </label> {$courseInfo->sc} <br />
    <label>میانگین نمرات: </label> <span dir='ltr'>{$courseInfo->sag}%</span> <br />
    <label>بیشینه‌ی نمره: </label> <span dir='ltr'>{$courseInfo->smg}%</span> <br />
EOF;
    if ($courseInfo->explain)
      printf ("<label>توضیحات: </label> <div class='comment'>%s</div>", $courseInfo->explain);

    $topQuery="call get_top_students(?)";
    
    $stmt = $con->prepare($topQuery);
    $stmt->bindParam( 1, $course, PDO::PARAM_INT );
    $stmt->execute() || errorLogger('DB', $con->error.' IN: '.$query, true);
    $stmt->bindColumn(1, $grade, PDO::PARAM_INT);
    $stmt->bindColumn(2, $id, PDO::PARAM_INT);
    $stmt->bindColumn(3, $username, PDO::PARAM_STR);
    $first_flag = true;
    while ( $stmt->fetch( PDO::FETCH_BOUND ) )
    {
		if ($grade > 0)
		{
			if ( $first ) {
				echo "<label>بالاترین نمرات: </label><br /> <ul>";
				$first=false;
			}
			printf ("<li>%%%s توسط <a href='%s'>%s</a></li>", fa_number($grade), Routing::genURL('profile').'/'.$id, $username);
		}
    }
    echo "</ul>";
	echo "</filedset>";
?>
<?php

  //REITH: ALL PROCESS OF ADDING NEW EXERCISE TO DATABASE AND FILES
  //NOTE: VALIDATE PROCESS MUST TAKEN HERE, NO IN PREVIOUS FORMS.

  require_once "libcc/formating.php";
  require_once "libcc/date.php";

  signInFirst('t', true);
  
  $exCount = $_POST["ExC"];
  $sriW = isset($_POST["sriW"])? $_POST["sriW"] : 1;
  $exSerie = $_POST["ExS"];
  $courseId = $_POST["crsID"];
  $nDate = $_POST["nDate"];
  $dDate = $_POST["dDate"];
  $cDate = $_POST["cDate"];
  $lang = "";
  $seriID = "";
  $courseName = "";
  $seriComment = nl2br($_POST["seriComment"]);
  $sep = DIRECTORY_SEPARATOR;
  $output=array('error'=>0);

  //VALIDATE
 if ($exCount == "" || $exSerie== "" || $courseId== "" || $nDate== "" || $dDate== "" || $cDate== "")
    dieJSON('EMPFRM');

 //READ NOW FOR VALIDATE
 //SAVE EXERCISE ELEMENTS TO ARRAYS
  $fail="";
  for ($i=1; $i<=$exCount; $i++)
  {
    $title[$i] = trim($_POST["title_".$i]);
    empty($title[$i]) && $title[$i]="تمرین ".fa_number($i.'');
    $delBlank[$i] = $_POST["delBlank_".$i];
    $delMultiBlank[$i] = $_POST["delMultiBlank_".$i];
    $delBlankFL[$i] = $_POST["delBlankFL_".$i];
    $delBlankEL[$i] = $_POST["delBlankEL_".$i];
    $delEmptyL[$i] = $_POST["delEmptyL_".$i];
    $caseSens[$i] = $_POST["caseSens_".$i];
    $exW[$i] = $_POST["exW_".$i];
    $maxRT[$i] = $_POST["maxRT_".$i];
    $TCCount[$i] = $_POST["tcCount_".$i];
    if ($TCCount[$i]==0)
      $fail.="<li>بایستی حداقل یک ورودی برای تمرین $i تعیین کنید</li>";

    $explain[$i] = nl2br(trim($_POST['explain_'.$i]));
    if (empty($explain[$i]))
      $fail.="<li>شرح تمرین $i خالی است.</li>";
  }
  if (!empty($fail))
    dieJSON(43, 'خطاهای زیر را برطرف کنید: '.$fail);

  //1. EXERCISE SERI PROCESS
  //INSERT NEW EXERCISE SERI
  //UPDATE NUMBER OF EXERCISE SERIES IN COURSE
  //GET ID OF INSERTED EXERCISE SERI
  $con=newMySQLi();
  $insSe=$con->prepare("INSERT INTO `$dbExerciseSeriTable` ".
		      "(`id`, `course`, `seri`, `exerciseCount`, `createDate`, `deadlineDate`, `correctionDate`, `wage`, `comment`)".
		      " VALUES ( NULL, ?, ?, ?, ?, ?, ?, ?, ?) ;");
  $insSe->bind_param("dddsssds", $courseId, $exSerie, $exCount, $nDate, $dDate, $cDate, $sriW, $seriComment);

  $updCrs=$con->prepare("UPDATE `$dbCourseTable` SET `seriCount`= `seriCount`+1, `sumSeriWage` = `sumSeriWage`+?  WHERE `id` = ?");
  $updCrs->bind_param("dd", $sriW, $courseId);

  $dbRes=($insSe->execute() && $updCrs->execute()) ? $con->query("SELECT LAST_INSERT_ID();") : false;

  if (!$dbRes)
    dieJSON(79, 'در تغییرات پایگاه داده خطا رخ داد. حتما با مدیر سیستم تماس بگیرید.');
  else
    $output['msg'].="<li>رکورد سری تمرین اضافه شد</li>";

  $insSe->close();
  $updCrs->close();

  $row=$dbRes->fetch_row();
  $seriID=$row[0];
  $exerciseIds = array();

  //2. SINGLE EXERCISES PROCESS
  //INSERT SINGLE EXERCISES TO EXERCISE TABLE
  //GET IT's ID AND TESTCASE COUNT OF EACH EXERCISE
  $insEx=$con->prepare("INSERT INTO `$dbExerciseTable` (`id`, `seri`, `number`, `title`, `wage`, `tcCount`, `explain`) VALUES (NULL,?,?,?,?,?,?)");
  for ($i=1; $i<=$exCount; $i++) {
    $insEx->bind_param("ddsdds", $seriID, $i, $title[$i], $exW[$i], $TCCount[$i], $explain[$i]);
    $insEx->execute();
  }
  $insEx->close();

  $exIDsRes=$con->query("SELECT `id`, `tcCount` FROM `$dbExerciseTable` WHERE `seri`='$seriID' ORDER BY `id` ASC;");
  if (!$dbRes || !$exRec = $exIDsRes->fetch_row()) { 
    dieJSON(80, 'در تغییرات پایگاه داده خطا رخ داد. حتما با مدیر سیستم تماس بگیرید.');
  }

  //3. EXERCISE RESULTS PROCESS
  //MAKE TABLE FOR RESULT OF EACH EXERCISE
  //RESULT IS GRADE OF STUDENT HOMEWORKS AND MORE INFORMATIONS
  $addExerciseResultQ="";
  do
  {
    $exerciseIds[] = $exRec[0];
    $addExerciseResultQ="CREATE TABLE `$dbName`.`{$dbExerciseResultPrefix}{$exRec[0]}` (".
			 " `id` int(11) NOT NULL auto_increment,".
			 " `student` int(11) UNSIGNED NOT NULL,".
			 " `compileError` tinyint(1) NOT NULL,".
			 " `runtimeError` tinyint(4) UNSIGNED NOT NULL,".
			 "  `time` tinyint(4) UNSIGNED NOT NULL, ";
			 for ($j=1; $j<=$exRec[1]; $j++)
			   $addExerciseResultQ.=" `tc{$j}grade` tinyint(1) UNSIGNED NOT NULL,";
    $addExerciseResultQ.=" `grade` tinyint(3) NOT NULL,".
			 " `cheat` tinyint(1) NOT NULL DEFAULT '0', ".
			 " PRIMARY KEY  (`id`),".
			 " KEY `student` (`student`),".
			 " CONSTRAINT `{$dbExerciseResultPrefix}{$exRec[0]}_ibfk_1` FOREIGN KEY (`student`) REFERENCES `user` (`id`) ON UPDATE CASCADE".
			 ") ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1; ";
    if (!$con->query($addExerciseResultQ))
    {
      errorLogger('DB', $con->error);
      dieJSON(81, 'در تغییرات پایگاه داده خطا رخ داد. حتما با مدیر سیستم تماس بگیرید.');
    }
    $output['msg']="<li>جدول تمرینات برای تمرین {$exRec[0]} اضافه شد</li>";
  } while ($exRec=$exIDsRes->fetch_row());

  //get course name and language
  ($crsDt = $con->prepare("SELECT `name`, `language` FROM `$dbCourseTable` WHERE `id`=?;")) || errorLogger('DB', $con-error);
  $crsDt->bind_param("d", $courseId);
  ($crsDt->execute()) ||  errorLogger('DB', $con-error);
  $crsDt->bind_result($courseName, $lang);
  $crsDt->fetch();
  $crsDt->close();

  //4. FILE PROCESS
  //MAKE DATA FILES FOR RUNNING STUDENTS HOMEWORKS
  $seriDataPath = $exercisePath.$sep.$seriID.$sep."data";
  @ mkdir($seriDataPath, 0770, true);

    $fileH = fopen($seriDataPath.$sep."seri.conf","w");
    $newLineChr = "\n";
    $fileOutPut = "";
    $fileOutPut .= "exNum=".$exCount.$newLineChr;
    $fileOutPut .= "crsId=".$courseId.$newLineChr;
    $fileOutPut .= "lang=$lang";
    fwrite($fileH, $fileOutPut.$newLineChr);
    fclose($fileH);
    //FIXME: ADD CONDITION AND ERROR CHECKER FOR FILE WRITING
    $output['msg'].="<li>فایل تنظیمات برای سری جدید تمرینات ساخته شد</li>";

    $tcSumW = array();
    for ($exTracer = 1; $exTracer<=$exCount; $exTracer++)
    {
      $confFile = fopen($seriDataPath.$sep.($exTracer).".conf","w");
      $newLineChr = "\n";
      $put2Conf = "";
      $put2Conf .= "exId={$exerciseIds[$exTracer-1]}".$newLineChr;
      if ($delBlank[$exTracer]) {
	$put2Conf .= "rmmultiblanks=true".$newLineChr;
	$put2Conf .= "rmblanks=true".$newLineChr;
	$put2Conf .= "rmfblanks=true".$newLineChr;
	$put2Conf .= "rmlblanks=true".$newLineChr;
	$put2Conf .= "rmblanksln=true".$newLineChr;
      } else {
      $put2Conf .= "rmblanks=false".$newLineChr;
      $put2Conf .= "rmmultiblanks=" . ($delMultiBlank[$exTracer]?"true":"false"). $newLineChr;
      $put2Conf .= "rmfblanks=" . ($delBlankFL[$exTracer]?"true":"false") . $newLineChr;
      $put2Conf .= "rmlblanks=" . ($delBlankEL[$exTracer]?"true":"false") . $newLineChr;
      $put2Conf .= "rmblanksln=" . ($delEmptyL[$exTracer]?"true":"false") . $newLineChr;
      }
      $put2Conf .= "casesense=" . ($caseSens[$exTracer]?"true":"false") . $newLineChr;
      if ($_POST["maxRTB_".$exTracer])
	$put2Conf .= "maxtime=" . $maxRT[$exTracer] . $newLineChr;
      $put2Conf .= "wage=".$exW[$exTracer].$newLineChr;
      $put2Conf .= "TCnum=" . $TCCount[$exTracer].$newLineChr;
      for ($tcTracer=1; $tcTracer<=$TCCount[$exTracer]; $tcTracer++)
      {
	$tcSumW[$exTracer]+=$_POST["tcW_".$exTracer."_".$tcTracer];
	$put2Conf .= "tc".$tcTracer."w=".$_POST["tcW_".$exTracer."_".$tcTracer].$newLineChr;
      
	$inFile = fopen($seriDataPath.$sep.$exTracer.".".$tcTracer.".in", "a");
	$put2In = $_POST["tcI_".$exTracer."_".$tcTracer];
	fwrite($inFile, $put2In);
	fclose($inFile);
      
	$OutFile = fopen($seriDataPath.$sep.$exTracer.".".$tcTracer.".out", "a");
	$put2Out = $_POST["tcO_".$exTracer."_".$tcTracer];
	fwrite($OutFile, $put2Out.$newLineChr);
	fclose($OutFile);      
      }
      $put2Conf .= "TCSUMW=".$tcSumW[$exTracer].$newLineChr;
      fwrite($confFile, $put2Conf.$newLineChr);
      fclose($confFile);

      //FIXME: ADD ERROR CHECKING
      $output['msg'].="<li>فایل‌های تمرین ".$exTracer." اضافه شدند</li>";
    }
  
    //RUNNUNG EXERCISES
  $cronTime=substr($cDate,14,2)."\t".substr($cDate,11,2)."\t".substr($cDate,8,2)."\t".substr($cDate,5,2)."\t";
  $file = fopen ($cronsFile, "a");
  fwrite($file, "$cronTime * $shellsPath/automate.sh $seriID \n");
  fclose($file);
  
  $out=array();
  $return="";
  $log = "";
  exec ("crontab $cronsFile 2>&1", $out, $return);
  if ($out)
  {
    foreach ($out as $row)
      $log.="$row"."\n";
  }
  if ($return!=0)
  {
    $output['error'].="<li>بروز رسانی تصحیح تمرینات انجام نشد! هرچه سریعتر log چک شود. <br/> تمرینات شما در موعد تصحیح، تصحیح نخواهند شد.</li>";
    $file = fopen ($logsFile, "a");
    fwrite ($file, date ("Y-m-d H:i").":\tUnsuccsessfull crontab:\n".$log);
    fclose ($file);
  }
  else

  //5 
  //add notices
  {
    $output['msg'].="<li>زمان بند تصحیح برنامه به روز شد</li>";

    $message="";
    $message.="<label>نام درس: </label>";
    $message.="<a href='/course/$courseId'> $courseName</a>"."<br />";

    $message.="<label>تعداد تمرینات: </label>";
    $message.=fa_number($exCount)."<br />";
    
    $message.="<label>مهلت ارسال: </label>";
    $message.=jl_date($dDate)."<br />";

    $message.="<label>زمان تصحیح: </label>";
    $message.=jl_date($cDate)."<br />";

    $message.="<br /> <a href='/seri/$seriID'>مشاهده‌ی صفحه‌ی سری تمرینات</a>";
    addNotice('تمرین سری '.fa_number($exSerie).' '.$courseName.' اضافه شد', $message);
  }

  //6
  //send mail to students
  if ($mailServer==true)
    exec ("php ./send_mail_exercise.php $seriID $courseId $exSerie $courseName &>/dev/null &");
  
  echo json_encode($output);
?>

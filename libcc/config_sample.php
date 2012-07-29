<?php
  // ^C php config file
  $currentPath=getcwd();
  $ccpath = "/var/www/localhost/htdocs/cc";
  $exercisePath = "$ccpath/files";
  $userFiles="$ccpath/files/users";
  $shellsPath = "$ccpath/shellz";
  $cronsFile = "$ccpath/crons.cron";
  $logsFile = "$ccpath/logs/logs.log";

  define ('__root__', "/var/www/localhost/htdocs/cc");
  define ('__js_path__', __root__.'/scripts');
  define ('__libcc_path__', __root__."/libcc");
  define ('__forms_path__', __root__."/forms");
  define ('__exercises_path__', __root__."/files");
  define ('__user_files_path__', __exercises_path__."/users");
  define ('__shells_path__', __root__."/shellz");
  define ('__cron_file__', __root__."/crons.cron");
  define ('__logs_file__', __root__."/logs/logs.log");
  
  $exerciseSizeLimit = 1000000;
  
  $siteURL = "http://localhost/cc";

  define ('MD5KEY', 'YOUR_SECRET');
  define ('DB_HOST', 'YOUR_DB_HOST');
  define ('DB_USER', 'YOUR_DB_USER');
  define ('DB_PASSWORD', 'YOUR_DB_PASSWORD');
  define ('DB_NAME', 'YOUR_DB_NAME');


  define ('DB_COURSE_TABLE', 'course');
  define ('DB_EXERCISE_TABLE', 'exercise');
  define ('DB_MEMBERSHIP_TABLE', 'membership');
  define ('DB_NOTICE_TABLE', 'notice');
  define ('DB_USER_TABLE', 'user');
  define ('DB_STUDENT_TABLE', 'student');
  define ('DB_TEACHER_TABLE', 'teacher');
  define ('DB_ADMIN_TABLE', 'admin');
  define ('DB_EXERCISE_FILE_UPLOAD_TABLE', 'student_upload');
  define ('DB_CONFIRM_TABLE', 'confirmation_waitinglist');
  define ('DB_EXERCISE_PREFIX', 'exercise_result_');
  define ('DB_EXERCISE_SERI_TABLE', 'exercise_seri');

  define ('__url__', 'http://localhost/cc');
  define ('__js_dispatch__', __url__.'/js');
  define ('__js_url__', __url__.'/scripts');
  define ('__img_url__', __url__.'/img');
  define ('__libcc_url__', __url__.'/libcc');
  define ('__jalcal_url__', __url__.'/dependencies/jalaliCal');


  $md5key = "RANDOM_STRING";
  $dbLocation = "HOST";
  $dbUsername = "MYSQL_USER";
  $dbPassword = "MYSQL_USER'S_PASSWORD";
  $dbName = "DATABASE_NAME";


  $dbAdminTable = "admin";
  $dbCourseTable = "course";
  $dbExerciseResultPrefix = "exercise_result_";
  $dbExerciseSeriTable = "exercise_seri";
  $dbExerciseTable = "exercise";
  $dbMembershipTable = "membership";
  $dbNoticeTable = "notice";
  $dbStudentTable = "student";
  $dbStudentUploadTable = "student_upload";
  $dbTeacherTable = "teacher";
  $dbUserTable = "user";
  $dbConfirmTable = "confirmation_waitinglist";

  $fpc_path="/usr/bin/fpc";
  $gcc_path="/usr/bin/gcc";
  $gpp_path="/usr/bin/g++";
  $javac_path="/usr/bin/javac";
  $gcj_path="/usr/bin/gcj";

  $mailServer=true;
  $mailHeader="From: ^C Staff <no-reply@yourdomain>\r\n"
	."Return-Path: ^C Staff <no-reply@yourdomain>\r\n"
        ."'MIME-Version: 1.0\r\nContent-type: text/plain; charset=UTF-8'\r\n"
	."X-Mailer: PHP/".phpversion()."\r\n";

  $mailFooter="Please DONT reply to this.. This is just a robot!";

  define ('CHEAT_SERI_GRADE', -100);
  define ('CHEAT_EXERCISE_GRADE', -100);

  error_reporting(0);
  error_reporting(E_ERROR | E_WARNING | E_PARSE);
  ini_set ('short_open_tag', true);
?>

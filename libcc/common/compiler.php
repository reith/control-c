<?php
  header ("Cache-Control: no-cache, must-revalidate");
  //ename executable file name
  //ename.SUFFIX fname
  //ename.s assembly file
  //ename.b java byte code
  //ename.o object file

  while (file_exists($dirname='/tmp/cc_'.rand()));
  mkdir($dirname);
  $ename = $dirname.'/executable';
  $oname = $dirname.'/output';

  if ($_POST['src_send']=="i") {
    if (empty($_POST['source'])) {
      printf ("<strong style='color:red'>%s</strong>", _("No source code received"));
      rmdir($dirname);
      die();
    }

    if ($_POST['lang']=="java")
    {
        rmdir($dirname);
        die($ERROR['BADDATA']);
    }

    $fname=$dirname.'/code.'.$_POST['lang'];

    $fh = fopen ($fname, 'w');
    fputs ($fh, $_POST['source']);
    fclose($fh);
  }
  else {
    if ($_FILES['src_file']['size']==0) {
      printf ("<strong style='color:red'>%s</strong>", _("No file selected"));
      rmdir($dirname);
      die();
    }
    $fname=$dirname.'/'.$_FILES['src_file']['name'];
    move_uploaded_file($_FILES['src_file']['tmp_name'], $fname);
  }

  $command="";
  $opts="";

  
  switch ($_POST['lang']) {
    case 'pas':
	if (isset($_POST['view_s'])) $opts.=" -a ";
	if (isset($_POST['view_e'])) $opts.=" -ve ";
	if (isset($_POST['view_w'])) $opts.=" -vw ";
	if ($_POST['stage']=='cmp') { $opts.=" -Cn "; $oname=$ename.".o"; }
	$opts.=" -Se9 ";
	$command="$fpc_path $opts $fname -o$oname"; break;
	break;
	
    case 'java':
// 	if (isset($_POST['view_s'])) $command="$gcj_path $fname -S -o $ename.s";
// 	$oname=$ename;
        $oname = preg_replace('/\.java$/i', '', $fname, 1).'.class';
// 	switch ($_POST['stage']) {
// 	  case 'asm': $command="$gcj_path -S -o $ename.s"; break;
// 	  case 'cmp': $command="$gcj_path $fname -C $oname";
// 	  default: if (!empty($command)) $command.=" && ";
// 	  if (isset($_POST['view_w'])) $opts=" -Wall ";
// 	  $command.="$gcj_path $fname $opts -o $oname";
          $command="$javac_path $fname"; break;
// 	};
// 	break;

    default:	//using c and c++ same
	if (isset($_POST['view_w'])) $opts.=" -Wall ";
	if ($_POST['lang']=='cpp')
	  $gcc_path=$gpp_path;
	$command="$gcc_path $opts $fname -S -o $ename.s";
	$oname=$ename;
	switch ($_POST['stage']) {
	  case 'asm': $oname=$ename.".s"; break;
	  case 'cmp': $opts.=" -c "; $oname=$ename.'.o'; break;
	  case 'ld': $opts.=" -lm "; break;
	};
	if ($_POST['stage']!='asm') //must do more
	  $command.=" && $gcc_path $opts $ename.s -o $oname";
	break;
  }

  exec ("( $command ) 2>&1", $output, $return);
  if (!$return)
    printf ("<strong class='grntxt'>%s</strong>. %s: %d %s", _("Compiled Successfully"), _("Produced Code size"), (int)exec("du -b $oname"), _("Bytes"));
  else
    echo _("Your program has errors.. please fix the errors before send");

  echo "<br />";
  if (count($output)) {
    echo _("Compiler messages").":<br />";
    echo "<div style='direction: ltr; text-align: left;'>";
      foreach ($output as $line)
    echo ($line.'<br />');
  }
  echo "</div>";

  
  if (isset($_POST['view_s']) && !$return) {
  $fh=fopen("$ename.s", 'r');
  echo _("Assembly Code").":<br />";
    echo "<div style='direction: ltr; text-align: left;'>";
      while ($line=fread($fh, 100))
	echo nl2br($line)."<br />";
  echo "</div>";
  fclose($fh);
  }

  $files = scandir ($dirname);
  foreach ( $files as $file)
    if ( ($file != '..') and ($file != '.') )
      unlink ($dirname.'/'.$file);
  rmdir($dirname);
  
?>
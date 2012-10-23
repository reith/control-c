<!--REITH:  THIS IS MAIN FRAME VISITOR SEES-->
<!-- NEED NO TRANSLATE SO FAR -->
<!-- News... -->
<div class="span4">
<ul>
  <?php
	require 'libcc/announcement.php';
    $announcements = get_newest_announcements(10);
	foreach( $announcements as $row )  {
		echo '<li>';
		switch ($row['type']) {
			case 'pa':
				$hl = 'Problem-set #'.$row['subject'].' Added';
				$url = Routing::url('problemset/-', array( 'id'=>$row['subject']) );
				break;
			case 'pg': 
				$hl = 'Problem-set #'.$row['subject'].' Graded';
				$url = Routing::url('problemset/-', array( 'id'=>$row['subject'] ) );
				break;
			case 'ca':
				$hl = 'Course #'.$row['subject'].' Added';
				$url = Routing::url('course/-', array( 'id'=>$row['subject']) );
				break;
			case 'ce':
				$hl = 'Course #'.$row['subject'].' Expired';
				$url = Routing::url('course/-', array( 'id'=>$row['subject']) );
				break;
			case null:
				$hl = $row['title'];
				$url = Routing::url('announcement/-', array( 'id'=>$row['id']) );
				break;
		}
		echo $env->locale()->date( $row['time'] );
		printf( "<br><a href='%s'>%s</a>", $url, $hl );
		echo '</li>';
	}
  ?>
  <li><a href="<?=Routing::url('/announcement')?>" ><?php echo _('Archive'); ?></a></li>
</ul>
</div>
<!-- about -->

<div class="well span8">
<h2>What's this?</h2>
<hr>
^C is a judgment system designed with programming courses requirements in mind.<br>
<hr>

<ul>
<li>Using useful options to configure grading strictness</li>
<li>Fuzzy Grading</li>
<li>Easy and Structural course management</li>
<li>Easy reporting and charting</li>
<li>Semi localized design</li>
</ul>

<a class="btn btn-mini btn-success" href="http://12eith.com/code/%5EC/">More on ^C</a>
</div>

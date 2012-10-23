<!--
FIXIT: use twk
-->

<div class="span5">
<?php
foreach ( $t['announcements'] as $row ) { ?>
	<div class="alert alert-inline">
		<?php
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
	printf( "<br><a href='%s'>%s</a>", $url, $hl ); ?>
		</div>
		<?php
}
?>
</div>

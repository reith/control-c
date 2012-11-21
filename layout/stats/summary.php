<table class="table-striped" style="width: 100%;">
<?php
foreach($t['stats'] as $stat) {
	printf("<tr><td>%s</td><td>%s</td></tr>",
	_($stat['name']), $env->locale()->number($stat['count'])
	);
}
?>
</table>

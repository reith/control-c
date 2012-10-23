<?php
require_once 'libcc/db.class.php';

function get_custom_announcement( $id ) {
	$query = <<<'EOQ'
SELECT
	`a`.id, `a`.`time`, `ca`.`title`, `ca`.`body`
FROM
	`announcement` as `a`, `custom_announcement` as `ca`
WHERE
	`ca`.`id` = `a`.id AND `ca`.id = ?
LIMIT 1
EOQ;
	$con = DB::instance();
	$stmt = $con->prepare( $query );
	$stmt->bindParam( 1, $id, PDO::PARAM_INT );
	if( !$stmt->execute() ) return false;
	$res_t = array(
		'id' => 'i',
		'time' => 'i',
		'title' => 's',
		'body' => 's'
		);
	$res = array();
	DB::bindColumns( $stmt, $res_t, $res );
	$stmt->fetch( PDO::FETCH_BOUND );
	return $res;
}

function get_newest_announcements( $count ) {
	$con = DB::instance();
	$query = <<<'EOT'
SELECT
	`a`.`id`, `time`, `type`, `subject`, `ca`.`title`
FROM
	`announcement` as `a` LEFT JOIN `custom_announcement` as `ca` ON `a`.id = `ca`.id
ORDER BY `time` DESC
LIMIT ?
EOT;

	$stmt = $con->prepare($query);
	$stmt->bindParam( 1, $count, PDO::PARAM_INT );
	if( !$stmt->execute() ) return false;
	$announcement = array();
	$types = array(
			'id'=>'i', 'time'=>'i', 'type'=>'s', 'subject'=>'i', 'title'=>'s'
			);
	DB::bindColumns( $stmt, $types, $announcement );
	$resultset = array();
	while( $stmt->fetch( PDO::FETCH_BOUND ) ) {
		$resultset[] = unserialize(serialize($announcement));
	}

	return $resultset;
}

?>

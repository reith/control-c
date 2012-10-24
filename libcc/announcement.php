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


/**
 * get_newest_announcements 
 * 
 * @param mixed $count 
 * @access public
 * @return resultset of {id, time, possible type, possibe subject, possible title}.
 */
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
	DB::fetchAllBound($stmt, $announcement, $resultset);
	return $resultset;
}

/**
 * formalize_announcements 
 * 
 * @param array $announcements 
 * @param mixed $locale 
 * @access public
 * @return formalized (see get_newest_announcements @return) resultset
 */
function formalize_announcements ( array $announcements, $locale ) {
	foreach ( $announcements as &$announcement) {
	$announcement['date'] = $locale->date($announcement['time']);
		if( empty( $announcement['title'] ) ) {
				$subject = $locale->number($announcement['subject']);
				switch($announcement['type']) {
					case 'pa': $announcement['title'] =
						sprintf('%s #%s %s', _('Problemset'), $subject, _('was added') );
						break;
					case 'pg': $announcement['title'] =
						sprintf('%s #%s %s', _('Problemset'), $subject, _('was graded') );
						break;
					case 'ca': $announcement['title'] =
						sprintf('%s #%s %s', _('Course'), $subject, _('was started') );
						break;
					case 'ca': $announcement['title'] =
						sprintf('%s #%s %s', _('Course'), $subject, _('was ended') );
						break;
			}
		}
	}
	return $announcements;
}
?>

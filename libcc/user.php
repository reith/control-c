<?php
require_once 'libcc/db.class.php';

function get_user( $id ) {
	$query = 'SELECT `username`,  CONCAT(`firstName`, " ", `lastName`) AS `name`, `email`, `lastLogin`, `message`, `privilege` FROM `user` WHERE `id` = ? LIMIT 1';
	$con = DB::instance();
	$stmt = $con->prepare($query);
	$stmt->bindParam(1, $id, PDO::PARAM_INT);
	$stmt->execute();
	$record = array();
	DB::bindColumns($stmt, array(
		'username' => 's',
		'name' => 's',
		'email' => 's',
		'lastLogin'=> 'i',
		'message' => 's',
		'privilege' => 's'
	), $record);

	return $stmt->fetch(PDO::FETCH_BOUND) ? $record : false;
}

?>

<?php
/*
 * FIXIT: use bindColumns. id and other integer values treathed as string
 */

if(! $env->isJSON()) {
	redirect404($env);
}

require_once 'libcc/query.class.php';
require_once 'libcc/db.class.php';

$request_filter = array(
	'courses' => array( 'filter' => FILTER_VALIDATE_INT, 'flags' => FILTER_FORCE_ARRAY ),
	'keywords' => array( 'filter' => FILTER_CALLBACK, 'options' => 'trim' ),
	'ge' => array( FILTER_VALIDATE_INT ),
	'nge' => array( FILTER_VALIDATE_INT ),
	'skt' => array( FILTER_VALIDATE_INT ),
	'ske' => array( FILTER_VALIDATE_INT ),
	'sort' => NULL
);

$rd = filter_input_array( INPUT_POST, $request_filter );
if( !$rd || !isset( $rd['courses'][0] ) ) {
	dieJSON(1, _('Select at least one course.') );
}

$o = array( 'rows'=>array() );

$q = new Query();
$q->calcFoundRows();
$q->addSelect('`e`.id');
$q->addSelect('`e`.title');
$q->addSelect('`e`.number');
$q->addSelect('`e`.explain');
$q->addSelect('`c`.name');

$q->addFrom('Exercise', 'e');
$q->addFrom('Problemset', 'ps');
$q->addFrom('Course', 'c');

$q->addWhere('`e`.set = `ps`.id AND `ps`.course = `c`.id');

if( count($rd['courses']) < 1) {
	dieJSON(1, _('Select at least one course.') );

}
$q->addWhere('AND `ps`.course IN (');
$q->addWhere( implode(',', array_fill(0, count($rd['courses']), '?' ) ).')');

if( $rd['ge'] ^ $rd['nge'] ) {
	//It somewhat sucks. maybe exercises are grading..
	if( $rd['ge'] )
		$q->addWhere('AND `ps`.checkDate < UNIX_TIMESTAMP()');
	else
		$q->addWhere('AND `ps`.checkDate > UNIX_TIMESTAMP()');
} else if(! ($rd['ge'] | $rd['nge']) ) {
	dieJSON(1, _('You must select at graded or not graded exercises') );
} //else we dont check grading status. It's selected.

$KEYWORD_SEARCH = 0;
if( isset( $rd['keywords']) && ( $rd['keywords'] !== "" )  ) {
	$ksq = 'AND (';
	if($rd['skt']) {
		$KEYWORD_SEARCH++;
		$ksq .= '`e`.title RLIKE ?';
	}
	if($rd['ske']) {
		if( $KEYWORD_SEARCH ) { //ksq is not empty
			$ksq .= ' OR ';
		}
		$KEYWORD_SEARCH++;
		$ksq .= '`e`.explain RLIKE ?';
	}
	$ksq .= ')';

	if( $KEYWORD_SEARCH ) {
		$prepared_keywords = preg_replace('/( +)/', '|', $rd['keywords'] );
		$q->addWhere( $ksq );
	}
}

$query = $q->generate();
switch( $rd['sort'] ) {
	case 'ed': $query.=' ORDER BY `ps`.deadlineDate'; break;
	case 'ea': $query.=' ORDER BY `ps`.createDate'; break;
	case 'et': $query.=' ORDER BY `e`.title'; break;
	default: dieJSON( 'BADDATA' );
}
$query.=' Limit 5';
$con = DB::instance();
$stmt = $con->prepare($query);

$optcount = 0;
foreach( $rd['courses'] as &$cid ) {
	$stmt->bindParam(++$optcount, $cid, PDO::PARAM_INT );
}


for( $i=0; $i < $KEYWORD_SEARCH; $i++ )
	$stmt->bindParam( ++$optcount, $prepared_keywords, PDO::PARAM_STR );

$stmt->execute();

while( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) {
	$o['rows'][] = $row;
}

$o['found_rows'] = DB::foundRows();

$env->setContext( 'json' );
$env->setHeaders();
die(json_encode($o));
?>

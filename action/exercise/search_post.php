<?php
/*
 * FIXIT: use bindColumns. id and other integer values treathed as string
 * FIXIT: Js part really sent request with POST method but data is available
 * URL so _POST is empty and _GET works!
 */

if(! $env->isJSON()) {
	Routing::notFound( $env );
}

require_once 'libcc/query.class.php';
require_once 'libcc/db.class.php';
require_once 'libcc/general.functions.php';

$request_filter = array(
	'courses' => array( 'filter' => FILTER_VALIDATE_INT, 'flags' => FILTER_FORCE_ARRAY ),
	'keywords' => array( 'filter' => FILTER_CALLBACK, 'options' => 'trim' ),
	'ge' => FILTER_VALIDATE_INT,
	'nge' => FILTER_VALIDATE_INT,
	'skt' => FILTER_VALIDATE_INT,
	'ske' => FILTER_VALIDATE_INT,
	'sort' => NULL,
	'from' => FILTER_VALIDATE_INT
);

$rd = filter_input_array( INPUT_GET, $request_filter );
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

$q->addFrom(DB_EXERCISE_TABLE, 'e');
$q->addFrom(DB_EXERCISE_SERI_TABLE, 'ps');
$q->addFrom(DB_COURSE_TABLE, 'c');

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
} // else we dont check grading status. It's selected.

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

$LIMIT=5;
$START= empty($rd['from']) ? 0 : $rd['from'];
$query.=" Limit $START, $LIMIT";

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
$o['limit_rows'] = $LIMIT;

$env->setHeaders();
die(json_encode($o));
?>

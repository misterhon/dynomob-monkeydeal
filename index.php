<?php

include 'lib/Business.php';
include 'lib/Deal.php';

$req = Business::getAll();

$arr = array();

foreach( $req as $b ) {
	array_push( $arr, new Business( $b ) );
}

// TO DO: Implement sorting in client-side JS.
$biz = Business::sortByPopularity( $arr );

if ( isXHR() && isset( $_POST['business'] ) ) {
	echo json_encode( $biz );
	return;
}

if ( isXHR() && isset( $_POST['business_search'] ) ) {
	echo json_encode( Business::findByName( $_POST['business_search'] ) );
	return;
}

if ( isset( $_POST['business_search'] ) ) {
	$businesses = Business::findByName( $_POST['business_search'] );
}

if ( isXHR() && isset( $_POST['business_id'] ) ) {
	echo json_encode( array( Business::findById( $_POST['business_id'] ) ) );
	return;
}

include 'views/index.tmpl.php';
<?php

include 'lib/Business.php';
include 'lib/Deal.php';
include 'lib/User.php';

if ( isXHR() && isset( $_POST['business'] ) ) {
	echo json_encode( Business::getAll() );
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
	echo json_encode( array( Business::getBusiness( $_POST['business_id'] ) ) );
	return;
}

$req = Business::getAll();

include 'views/index.tmpl.php';
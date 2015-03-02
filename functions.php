<?php

// Store credentials outside of the project, and "deny from all" via .htaccess to prevent external access.
require '../config.inc';

// Check if browser supports AJAX. Returns boolean
function isXHR() {
	return isset( $_SERVER['HTTP_X_REQUESTED_WITH'] );
}

// Connect to the database
function connect() {
	try {
		global $pdo;
		$pdo = new PDO( DB_TYPE.":host=".$_ENV['DATABASE_SERVER'].";dbname=".DB_NAME, DB_USER, DB_PASS );
		$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	} catch ( PDOException $e ) {
		echo "Error: ".$e->getMessage()."<br>";
	}
}

?>
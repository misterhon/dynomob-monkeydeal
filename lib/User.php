<?php

require_once 'functions.php';

class User {

	public $id;
	public $name;
	public $gender;
	public $photo;
	public $role;
	
	private static function isAdmin($id) {
		// hardcoding admin userIDs since there are just a few:
		// (SELECT id FROM dmusers)
		return in_array( $id, array( 3, 55, 56, 57, 68, 115, 116 ) );
	}

	public function __construct( stdClass $config ) {
		try {
			
			$this->id = (int) $config->id;
			$this->name = $config->name;
			$this->gender = $config->sex;
			$this->photo = "http://graph.facebook.com/{$config->fbid}/picture?type=large";
			$this->role = ( self::isAdmin( $this->id ) ) ? 'admin' : 'user';
			
		} catch ( Exception $e ) {
			echo "Error: " . $e->getMessage() . "<br>";
			die();
		}
	}
	
	public static function getAll() {
		try {
			
			global $pdo;
			connect();
			
			$stmt = $pdo->prepare('SELECT id, username AS name, sex, fbid FROM user');
			$pdo = null;
			$stmt->execute();
			
			return $stmt->fetchAll( PDO::FETCH_OBJ );
			
		} catch ( PDOException $e ) {
			echo "Error: " . $e->getMessage() . "<br>";
			return array();
		}
	}
	
}
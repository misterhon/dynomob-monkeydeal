<?php

require_once 'functions.php';

class User {

	public $id;
	private $fbid;
	public $name;
	public $gender;
	public $photo;
	public $role;

	public function __construct() {
		try {
			
			$this->id    = intval( $this->id );
			$this->photo = "http://graph.facebook.com/{$this->fbid}/picture?type=large";
			$this->role  = ( self::isAdmin( $this->id ) ) ? 'admin' : 'user';
			
		} catch ( Exception $e ) {
			echo "Error: " . $e->getMessage() . "<br>";
			die();
		}
	}
	
	private static function isAdmin($id) {
		// hardcoding admin userIDs since there are only a handful:
		// (SELECT id FROM dmusers)
		return in_array( $id, array( 3, 55, 56, 57, 68, 115, 116 ) );
	}

	public static function getAll() {
		try {
			
			global $pdo;
			connect();
			
			$stmt = $pdo->prepare('SELECT id, username AS name, sex AS gender, fbid FROM user');
			$pdo = null;
			$stmt->execute();
			
			return $stmt->fetchAll( PDO::FETCH_CLASS, 'User' );
			
		} catch ( PDOException $e ) {
			echo "Error: " . $e->getMessage() . "<br>";
			return array();
		}
	}

	public static function getUser( $id ) {
		try {
			
			global $pdo;
			connect();
			
			$stmt = $pdo->prepare('SELECT id, username AS name, sex AS gender, fbid FROM user WHERE id = :id');
			$pdo = null;

			$stmt->setFetchMode( PDO::FETCH_CLASS, 'User' );
			$stmt->execute( array( 'id' => $id ) );
			
			return $stmt->fetch( PDO::FETCH_CLASS );
			
		} catch ( PDOException $e ) {
			echo "Error: " . $e->getMessage() . "<br>";
			return array();
		}
	}
	
}
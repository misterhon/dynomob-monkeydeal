<?php

require_once 'functions.php';

class Deal {

	public $id;
	public $bid;
	public $promo;
	public $sponsor;
	public $photo;
	public $isActive;

	public function __construct() {
		try {

			$this->id       = intval( $this->id );
			$this->bid      = intval( $this->bid );
			$this->isActive = (bool) $this->isActive;
			$this->photo    = $this->getPhoto( $this->bid ) ?: 'http://www.dynomob.com/app/images/defaultpic.png';

			$biz = Business::getBusiness( $this->bid );

			$this->sponsor = $biz->name;

		} catch ( Exception $e ) {
			echo "Error: " . $e->getMessage() . "<br>";
		}
	}

	public function getUsersWhoClaimed() {
		try {

			global $pdo;
			connect();

			$stmt = $pdo->prepare('SELECT u.id, u.username AS name, u.sex AS gender, u.fbid FROM user u INNER JOIN claimed c ON c.promId = :id AND u.id = c.userId');
			$stmt->execute( array( 'id' => $this->id ) );

			$pdo = null;

			return $stmt->fetchAll( PDO::FETCH_CLASS, 'User' );

		} catch ( Exception $e ) {
			echo "Error: " . $e->getMessage() . "<br>";
		}
	}

	private function getPhoto() {
		try {

			global $pdo;
			connect();

			$stmt = $pdo->prepare('SELECT promophoto FROM business WHERE id = :id');
			$stmt->execute( array( 'id' => $this->bid ) );

			$pdo = null;

			return $stmt->fetch( PDO::FETCH_COLUMN, 0 );
			
		} catch ( Exception $e ) {
			echo "Error: " . $e->getMessage() . "<br>";
		}
	}
	
	public static function getAll() {
		try {
			
			global $pdo;
			connect();
			
			$stmt = $pdo->prepare('SELECT id, name AS promo, businessid AS bid, STRCMP("inactive", status) AS isActive FROM promotions');
			$pdo = null;
			$stmt->execute();
			
			return $stmt->fetchAll( PDO::FETCH_CLASS, 'Deal' );
			
		} catch ( PDOException $e ) {
			echo "Error: " . $e->getMessage() . "<br>";
			return array();
		}
	}
	
	public static function getAllActive() {
		try {
			
			global $pdo;
			connect();
			
			$stmt = $pdo->prepare('SELECT MAX(p.id), name AS promo, businessid AS bid, status FROM promotions p INNER JOIN business b ON p.status = "active" AND p.businessId = b.id');
			$pdo = null;
			$stmt->execute();
			
			return $stmt->fetchAll( PDO::FETCH_CLASS, 'Deal' );
			
		} catch ( PDOException $e ) {
			echo "Error: " . $e->getMessage() . "<br>";
			return array();
		}
	}
	
	public static function isActive($id) {
		try {
			
			global $pdo;
			connect();
			
			$stmt = $pdo->prepare('SELECT 1 FROM promotions WHERE id = :id AND status = "active"');
			$pdo = null;
			$stmt->execute( array( 'id' => $id ) );
			
			return (bool) $stmt->fetch( PDO::FETCH_COLUMN, 0 );
			
		} catch ( PDOException $e ) {
			echo "Error: ".$e->getMessage()."<br>";
			return null;
		}
	}

	public static function getDealIdByBusinessId($bid) {
		try {
			
			global $pdo;
			connect();
			
			$stmt = $pdo->prepare('SELECT MAX(id) FROM promotions WHERE businessId = :bid AND status = "active"');
			$pdo = null;
			$stmt->execute( array( 'bid' => $bid ) );
			
			return intval( $stmt->fetch( PDO::FETCH_COLUMN, 0 ) );
			
		} catch ( PDOException $e ) {
			echo "Error: ".$e->getMessage()."<br>";
			return null;
		}
	}

	public static function getDeal( $id ) {
		try {

			global $pdo;
			connect();
			
			$stmt = $pdo->prepare('SELECT id, name, businessid AS bid, STRCMP("inactive", status) AS isActive FROM promotions WHERE id = :id');
			$pdo = null;

			$stmt->setFetchMode( PDO::FETCH_CLASS, 'Deal' );
			$stmt->execute( array( 'id' => $id ) );
			
			return $stmt->fetch( PDO::FETCH_CLASS );

		} catch ( PDOException $e ) {
			echo "Error: ".$e->getMessage()."<br>";
			return null;
		}
	}

}
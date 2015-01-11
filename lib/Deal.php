<?php

require_once 'functions.php';

class Deal {

	public $id;
	public $promo;
	public $sponsor;
	public $photo;
	public $isActive;

	public function __construct( stdClass $config ) {
		try {
		
			$biz = Business::findByID( $config->bid );
			
			global $pdo;
			connect();
			
			$stmt = $pdo->prepare('SELECT promophoto AS photo FROM business WHERE id = :id');
			$pdo = null;
			$stmt->execute( array( 'id' => $biz->id ) );
			
			$this->id = (int) $config->id;
			$this->promo = $config->name;
			$this->sponsor = $biz->name;
			$this->photo = ( $stmt->fetch( PDO::FETCH_COLUMN, 0 ) ) ?: 'http://www.dynomob.com/app/images/defaultpic.png';
			$this->isActive = ( 'active' === $config->status );
			
		} catch ( Exception $e ) {
			echo "Error: " . $e->getMessage() . "<br>";
		}
	}
	
	public static function getAll() {
		try {
			
			global $pdo;
			connect();
			
			$stmt = $pdo->prepare('SELECT id, name, businessid AS bid, status FROM promotions');
			$pdo = null;
			$stmt->execute();
			
			return $stmt->fetchAll( PDO::FETCH_OBJ );
			
		} catch ( PDOException $e ) {
			echo "Error: " . $e->getMessage() . "<br>";
			return array();
		}
	}
	
	public static function getAllActive() {
		try {
			
			global $pdo;
			connect();
			
			$stmt = $pdo->prepare('SELECT MAX(p.id) FROM promotions p INNER JOIN business b ON p.status = "active" AND p.businessId = b.id');
			$pdo = null;
			$stmt->execute();
			
			return $stmt->fetchAll( PDO::FETCH_OBJ );
			
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
			return false;
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

}
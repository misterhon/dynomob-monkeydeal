<?php

require_once 'functions.php';

class Business {
	
	public $id;
	public $name;
	public $type;
	public $bananaCount;
	public $photo;
	public $promoId;
	
	public function __construct( stdClass $config ) {
		try {
			
			$this->id    = (int) $config->id;
			$this->name  = $config->name;
			$this->photo = $config->photo ?: 'http://www.dynomob.com/app/images/defaultpic.png';
			$this->type  = $config->type;
			
			$this->bananaCount = self::getBananaCount( $config->id );
			$this->promoId     = ( ( self::hasActiveDeal( $config->id ) ) ? Deal::getDealIdByBusinessId( $config->id ) : null );
			
		} catch ( Exception $e ) {
			echo "Error: " . $e->getMessage() . "<br>";
			die();
		}
	}
	
	public static function getAll() {
		try {
			
			global $pdo;
			connect();
			
			$stmt = $pdo->prepare('SELECT id, name, type, photo FROM business');
			$pdo = null;
			$stmt->execute();
			
			return $stmt->fetchAll( PDO::FETCH_OBJ );
			
		} catch ( PDOException $e ) {
			echo "Error: " . $e->getMessage() . "<br>";
			return array();
		}
	}
	
	public static function getAllOfType( $type ) {
		return false;
	}
	
	// Search Business by name, partials are accepted
	public static function findByName( $srch = '' ) {
		
		if ( $srch ) {
			try {
			
				global $pdo;
				connect();
				
				$stmt = $pdo->prepare('SELECT id, name, type, photo FROM business WHERE name LIKE :name ');
				$pdo = null;
				$stmt->execute( array( 'name' => "%{$srch}%" ) );
				
				return $stmt->fetchAll( PDO::FETCH_OBJ );
				
			} catch ( PDOException $e ) {
				echo "Error: " . $e->getMessage() . "<br>";
				return array();
			}
		}
		
		return self::getAll();
		
	}
	
	// Check to see how many bananas a Business has
	public static function getBananaCount( $bid ) {
		try {
			
			global $pdo;
			connect();
			
			$stmt = $pdo->prepare('SELECT COUNT(DISTINCT userid) AS follower FROM follow WHERE bussid = :bid');
			$pdo = null;
			$stmt->execute( array( 'bid' => $bid ) );
			
			return intval( $stmt->fetch( PDO::FETCH_OBJ )->follower );
			
		} catch ( PDOException $e ) {
			echo "Error: ".$e->getMessage()."<br>";
			return -1;
		}
	}

	// Checks if the Business has any active deal
	public static function hasActiveDeal( $bid ) {
		try {
		
			global $pdo;
			connect();
			
			$stmt = $pdo->prepare('SELECT 1 FROM promotions WHERE businessid = :bid AND status = "active"');
			$pdo = null;
			$stmt->execute( array( 'bid' => $bid ) );
			
			return (bool) $stmt->fetch( PDO::FETCH_COLUMN, 0 );
			
		} catch ( PDOException $e ) {
			echo "Error: ".$e->getMessage()."<br>";
			return false;
		}
	}
	
	// Sorts an array of Businesses by bananaCount, in descending order
	public static function sortByPopularity( $collection ) {
		usort( $collection, 'sortByBananas' );
		return $collection;
	}

	public static function getBusiness( $id ) {
		try {
			
			global $pdo;
			connect();
			
			$stmt = $pdo->prepare('SELECT id, name, type, photo FROM business where id = :id');
			$pdo = null;
			$stmt->execute( array( 'id' => $id ) );

			$bizConfig = $stmt->fetch( PDO::FETCH_OBJ );

			return new Business( $bizConfig );
			
		} catch ( PDOException $e ) {
			echo "Error: " . $e->getMessage() . "<br>";
			return null;
		}
	}
	
}
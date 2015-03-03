<?php

require_once 'functions.php';

class Business {
	
	public $id;
	public $name;
	public $type;
	public $bananaCount;
	public $photo;
	public $promoId;
	public $address;
	public $location;
	
	public function __construct() {
		try {

			$this->id          = intval( $this->id );
			$this->bananaCount = self::getBananaCount( $this->id );
			$this->address     = self::getBusinessAddress( $this->id );
			$this->location    = self::getBusinessLocation( $this->id );
			$this->photo       = $this->photo ?: 'http://www.dynomob.com/app/images/defaultpic.png';
			$this->promoId     = ( ( self::hasActiveDeal( $this->id ) ) ? Deal::getDealIdByBusinessId( $this->id ) : null );

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
			
			return $stmt->fetchAll( PDO::FETCH_CLASS, "Business" );
			
		} catch ( PDOException $e ) {
			echo "Error: " . $e->getMessage() . "<br>";
			return array();
		}
	}
	
	public static function getAllOfType( $type ) {
		if ( $type ) {
			try {

				global $pdo;
				connect();
				
				$stmt = $pdo->prepare('SELECT id, name, type, photo FROM business WHERE type LIKE :type');
				$pdo = null;
				$stmt->execute( array( 'type' => "%{$type}%" ) );
				
				return $stmt->fetchAll( PDO::FETCH_CLASS, 'Business' );

			} catch ( PDOException $e ) {
				echo "Error: " . $e->getMessage() . "<br>";
				return array();
			}
		}
		return array();
	}
	
	// Search Business by name, partials are accepted
	// @return Array of Business objects, empty array if no results, null on error.
	public static function findByName( $srch = '' ) {
		
		if ( $srch ) {
			try {
			
				global $pdo;
				connect();
				
				$stmt = $pdo->prepare('SELECT id, name, type, photo FROM business WHERE name LIKE :name ');
				$pdo = null;
				$stmt->execute( array( 'name' => "%{$srch}%" ) );
				
				return $stmt->fetchAll( PDO::FETCH_CLASS, 'Business' );
				
			} catch ( PDOException $e ) {
				echo "Error: " . $e->getMessage() . "<br>";
				return null;
			}
		}
		
		return self::getAll();
		
	}

	public static function findByZipcode( $srch = '' ) {

		if ( $srch ) {
			try {
			
				global $pdo;
				connect();
				
				$stmt = $pdo->prepare('SELECT id, name, type, photo FROM business WHERE zip = :zip ');
				$pdo = null;
				$stmt->execute( array( 'zip' => "%{$srch}%" ) );
				
				return $stmt->fetchAll( PDO::FETCH_CLASS, 'Business' );
				
			} catch ( PDOException $e ) {
				echo "Error: " . $e->getMessage() . "<br>";
				return null;
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
			
			return intval( $stmt->fetch( PDO::FETCH_ASSOC )->follower );
			
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

	public static function getBusiness( $id ) {
		try {
			
			global $pdo;
			connect();
			
			$stmt = $pdo->prepare('SELECT id, name, type, photo FROM business WHERE id = :id');
			$pdo = null;
			$stmt->setFetchMode( PDO::FETCH_CLASS, 'Business' );
			$stmt->execute( array( 'id' => $id ) );

			return $stmt->fetch( PDO::FETCH_CLASS );
			
		} catch ( PDOException $e ) {
			echo "Error: " . $e->getMessage() . "<br>";
			return null;
		}
	}

	public static function getBusinessAddress( $id ) {
		try {

			global $pdo;
			connect();

			$stmt = $pdo->prepare('SELECT address, address2, city, state, zip FROM business WHERE id = :id');
			$pdo = null;
			$stmt->execute( array( 'id' => $id ) );

			$addr = $stmt->fetch( PDO::FETCH_ASSOC );

			if ( !count( $addr ) || !$addr['address'] ) {
				return "";
			}

			return $addr['address'] . (( $addr['address2'] ) ? ' ' . $addr['address2'] : '') . ', '
				. $addr['city'] . ', ' . $addr['state'] . ' ' . $addr['zip'];

		} catch ( PDOException $e ) {
			echo "Error: " . $e->getMessage() . "<br>";
			return "";
		}
	}

	// Location is for mapping, otherwise this function is almost identical to getBusinessAddress().
	public static function getBusinessLocation( $id ) {
		try {

			global $pdo;
			connect();

			$stmt = $pdo->prepare('SELECT address, city, state, zip FROM business WHERE id = :id');
			$pdo = null;
			$stmt->execute( array( 'id' => $id ) );

			$addr = $stmt->fetch( PDO::FETCH_ASSOC );

			if ( !count( $addr ) || !$addr['address'] ) {
				return "";
			}

			return $addr['address'] . ', ' . $addr['city'] . ', ' . $addr['state'] . ' ' . $addr['zip'];

		} catch ( PDOException $e ) {
			echo "Error: " . $e->getMessage() . "<br>";
			return "";
		}
	}
	
}
<?php
	class Database {
		private static $dbName = 'id19467814_database_aiot209' ;
		private static $dbHost = 'localhost' ;
		private static $dbUsername = 'id19467814_yjo494512';
		private static $dbUserPassword = 'jVF&Kb)H+2v72NOs';
		 
		private static $cont  = null;
		 
		public function __construct() {
			die('Init function is not allowed');
		}
		 
		public static function connect() {
		  // One connection through whole application
		  if ( null == self::$cont ) {     
        try {
          self::$cont =  new PDO( "mysql:host=".self::$dbHost.";"."dbname=".self::$dbName, self::$dbUsername, self::$dbUserPassword); 
        }
        catch(PDOException $e) {
          die($e->getMessage()); 
        }
		  }
		  return self::$cont;
		}
		 
		public static function disconnect() {
			self::$cont = null;
		}
	}
?>
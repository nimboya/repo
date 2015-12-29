<?php
require 'plugins/NotORM.php';
/* Database Configuration */
$config = array();
// MySQL CONFIG
$config['mysqldbhost']   = 'localhost';
$config['mysqldbuser']   = 'root';
$config['mysqldbpass']   = 'ayo';
$config['mysqldbname']   = 'ssapi';
$config['dbmethod'] = 'mysql:dbname=';
Utility::saveConfig($config);
Utility::mysqlRes();




class Utility {
	static $config = array();
	
	public static function getConfig($key){
		return self::$config[$key];
	}

	public static function saveConfig($config){
		self::$config = $config;
	}

	public static function getConfigAll(){
		return self::$config;
	}
	
	public static function mysqlRes() {
            
            try {
                $dsn = self::getConfig('dbmethod').self::getConfig('mysqldbname');//$dbmethod.$dbname;
		$pdo = new PDO($dsn, self::getConfig('mysqldbuser'), self::getConfig('mysqldbpass'));
		return $db = new NotORM($pdo);
            } catch (Exception $ex) {
                $response = array ('status'=>"failed",'description'=>$ex->getMessage());
                header("Content-Type: application/json");
                die (json_encode($response));
            }
		
	}
}
?>
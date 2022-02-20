<?php

class Database
{
	public function connection(){
		$db_host = "localhost";
		$db_name = "logInOut";
		$db_username = "root";
		$db_password = "Pass@1234";
		
		$dsn_db = 'mysql:host='.$db_host.';dbname='.$db_name.';charset=utf8mb4';
		try{
		   $pdo = new PDO($dsn_db, $db_username, $db_password);
		   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		   return $pdo;

		}catch (PDOException $e){
		   echo $e->getMessage();
		   exit;
		}
	} 
}


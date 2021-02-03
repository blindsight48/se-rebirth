<?php

interface Model {
	public function connect();
}

abstract class Db implements Model {
	const HOST = "localhost";
	const USER = "root";
	const PASS = "password";
	const DB = "user_registration";
	private $host;
	private $user;
	private $pass;
	private $db;

	protected function config($host, $user, $pass, $db){
		$this->host = $host;
		$this->user = $user;
		$this->pass = $pass;
		$this->db = $db;
	}

	protected function getHost(){
		if(isset($this->host)){
			return $this->host;
		}else{
			return self::HOST;
		}
	}

	protected function getUser(){
		if(isset($this->user)){
			return $this->user;
		}else{
			return self::USER;
		}
	}

	protected function getPass(){
		if(isset($this->pass)){
			return $this->pass;
		}else{
			return self::PASS;
		}
	}

	protected function getDb(){
		if(isset($this->db)){
			return $this->db;
		}else{
			return self::DB;
		}
	}
}

class Dbh extends Db{
	public function connect(){
		$host = $this->getHost();
		$user = $this->getUser();
		$pass = $this->getPass();
		$db = $this->getDb();
		$conn = new mysqli($host, $user, $pass, $db);
		if($conn->connect_error){
			echo $conn->connect_error;
		}else{
			echo "Successfully connected!";
		}
	}
}

$conn = new Dbh();
$conn->connect();
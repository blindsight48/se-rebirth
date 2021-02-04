<?php
include_once '../includes/config.inc.php';

interface Model {
	public function connect();
}

abstract class DB implements Model {

  // Set you database credentials here
	const HOST = HOST;
	const USER = USER;
	const PASS = PASS;
	const DB = DB;

  //name of game (to be created)
  const SERVER = SERVER;

  //properties
  private $conn;

  public function connect(){
    //set property $conn to a new mysqli object
    $this->conn = new mysqli(self::HOST, self::USER, self::PASS, self::DB);

    //pass the object
    return $this->conn;
  }
}

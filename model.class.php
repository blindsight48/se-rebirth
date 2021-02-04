<?php

interface Model {
	public function connect();
}

abstract class DB implements Model {

  // Set you database credentials here
	const HOST = "localhost";
	const USER = "root";
	const PASS = "password";
	const DB = "v-0.1alpha";

  //name of game (to be created)
  const SERVER = "test";

  //properties
  private $conn;

  public function connect(){
    //set property $conn to a new mysqli object
    $this->conn = new mysqli(self::HOST, self::USER, self::PASS, self::DB);

    //pass the object
    return $this->conn;
  }
}

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
  const SERVER = "test";
  //properties
  private $conn;
  private $res;
  public $data;

  public function connect(){
    //set property $conn to a new mysqli object
    $this->conn = new mysqli(self::HOST, self::USER, self::PASS, self::DB);
    //pass the object
    return $this->conn;
  }

  protected function query($sql){
    //set property $res to a mysqli->query result (to be changed)
    $this->res = $this->conn->query($sql);
    //set property $data to associative array of property $res
    $this->data = $this->res->fetch_assoc();
    //pass the associative array
    return $this->data;
  }

}

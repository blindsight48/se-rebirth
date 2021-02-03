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

  protected function query(){
    //set property $res to a mysqli->query result (to be changed)
    $this->res = $this->conn->query("SELECT * FROM se_games");
    //set property $data to associative array of property $res
    $this->data = $this->res->fetch_assoc();
    //pass the associative array
    return $this->data;
  }

}

class Dbh extends Db {

  public function __construct(){
    //set property $conn to returned mysqli object through Db::connect() call
    $this->conn = $this->connect();
    //set property $data to returned associative arry through Db::query() call
    $this->data = $this->query();
    //close the connection
    $this->conn->close();
  }
}

//create new Dbh object
$Dbh = new Dbh();
//show results of the array element with key 'game_id'
echo $Dbh->data['game_id'];

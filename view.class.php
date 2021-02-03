<?php
include_once 'model.class.php';

class Dbh extends Db {
  private $sql = "SELECT * FROM user_accounts";

  public function __construct(){
    //set property $conn to returned mysqli object through Db::connect() call
    $this->conn = $this->connect();
    //set property $data to returned associative arry through Db::query() call
    $this->data = $this->query($this->sql);
    //close the connection
    $this->conn->close();
  }
}

//create new Dbh object
$Dbh = new Dbh();
//show results of the array element with key 'game_id'
foreach($Dbh->data as $key => $val){
  echo $key . ": " . $val . "<br>";
}

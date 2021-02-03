<?php
include_once 'model.class.php';

interface View {
	public function query($sql);
  public function prepared_query($sql, $params, $types);
}

abstract class Endpoint extends Db implements View{
  // mysqli response object
  private $res;
  // public access property
  public $data;

  public function query($sql){
    // DB::conn as mysqli connect object
    $this->res = $this->conn->query($sql);
    return $this->res;
  }

  public function prepared_query($sql, $params, $types = ""){
    //either use set $types or default to prepared string type "s" per $param size
    $types = $types ?: str_repeat("s", count($params));
    //set $res property to mysqli prepared object
    $this->res = $this->conn->prepare($sql);
    //bind to nysqli prepared object
    $this->res->bind_param($types, ...$params);
    //execute msqli bound prepared object
    $this->res->execute();
    //return mysqli response object
    return $this->res;
  }

  function prepared_select($sql, $params = [], $types = ""){
    //return mysqli response object from prepared_query method
    return $this->prepared_query($sql, $params, $types)->get_result();
  }

}

class Dbh extends Endpoint {
  //mysqli prepared statement
  private $sql = "SELECT * FROM user_accounts WHERE login_id=? AND login_name=?";
  //mysqli query statement
  private $sql2 = "SELECT * FROM user_accounts";

  public function __construct(){
    //set property $conn to returned mysqli object through Db::connect() call
    $this->conn = $this->connect();
    //query and prepared statement syntax
    //set property $res to mysqli response object
    $this->res = $this->query($this->sql2);
    //$this->res = $this->prepared_select($this->sql, [2, 'Owner'], "is");
    //set public $data property to mysqli response object
    $this->data = $this->res;
    //close the connection
    $this->conn->close();
  }

}

//create new Dbh object
$Dbh = new Dbh();

if($Dbh->data->num_rows > 0){
  //iterate over mysqli response object
  for($i=0; $i<$Dbh->data->num_rows; $i++){
    //show results of the associative array
    foreach($Dbh->data->fetch_assoc() as $key => $val){
      echo $key . ": " . $val . "<br>";
    }
    echo "<br>";
  }
}

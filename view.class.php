<?php
include_once 'model.class.php';

interface View {
	public function close();
}

abstract class Endpoint extends Db implements View{
  // mysqli response object
  private $res;

  protected function query($sql){
    // set property $res to mysqli response object
    $this->res = $this->conn->query($sql);

    //return mysqli response object
    return $this->res;
  }

  protected function prepared_query($sql, $params, $types = ""){
    //either use set parameter $types or default to prepared string type "s" per parameter $param size
    $types = $types ?: str_repeat("s", count($params));

    //set property $res to mysqli prepared object
    $this->res = $this->conn->prepare($sql);
    //bind to mysqli prepared object
    //E.G. - bind_param("sib", ["String", 153, True]);
    $this->res->bind_param($types, ...$params);
    //execute bound mysqli prepared object
    $this->res->execute();

    //return executed mysqli prepared object
    return $this->res;
  }

  protected function prepared_select($sql, $params = [], $types = ""){
    //return mysqli response object
    return $this->prepared_query($sql, $params, $types)->get_result();
  }

  public function close(){
    $this->conn->close();
  }

}

class Dbh extends Endpoint {
  // public access property
  public $data;
  //sql query SELECT statement
  private $sql = "SELECT * FROM user_accounts";
  //sql prepared SELECT statement
  private $sql2 = "SELECT * FROM user_accounts WHERE login_id=? AND login_name=?";

  public function __construct(){
    //set property $conn to returned mysqli object through Db::connect() call
    $this->conn = $this->connect();

    //set property $res to mysqli response object

    //SYNTAX: query proxy method
    //$this->res = $this->query($this->sql);

    //SYNTAX: prepared statement proxy method
    $this->res = $this->prepared_select($this->sql2, [1, 'Admin'], "is");

    //set public $data property to mysqli response object
    $this->data = $this->res;
    //close the connection
    $this->close();
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

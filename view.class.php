<?php
include_once 'model.class.php';

interface View {
	public function close();
}

abstract class Endpoint extends Db implements View{
  // mysqli response object
  private $res;
  // public access property
  public $data;

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

  protected function format(){
    if($this->res->num_rows > 0){
      //create multi-dimensional array
      $arr = array();
      while($data = $this->res->fetch_assoc()){
        $arr[] = $data;
      }
      //set property $data to multi-dimensional array
      $this->data = $arr;

      //return multi-dimensional array
      return $this->data;
    }
  }

  public function close(){
    $this->conn->close();
  }

}

class Dbh extends Endpoint {
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

    //set public $data property to either associative array or multi-dimension if mysqli result num_rows == 1
    $this->data = $this->res->num_rows == 1 ? $this->res->fetch_assoc() : $this->format();
    //close the connection
    $this->close();
  }

}

//create new Dbh object
$Dbh = new Dbh();

//return a single row from a table
//associative array Dbh::prepare_select($sql, $params = [], $types = "")
//echo $Dbh->data['login_id'];

foreach($Dbh->data as $key => $val){
  echo $key . ": " . $val . "<br>";
}


//return entire table
//multi-dimensional array Dbh::query($sql)
//echo $Dbh->data[0]['login_name'];

//iterate over each index of multi-dimensional array
/*
for($i=0; $i<count($Dbh->data); $i++){
  //$Dbh->data[$i][...]
  foreach($Dbh->data[$i] as $key => $val){
    echo $key . ": " . $val . "<br>";
  }
  echo "<br>";
}
*/

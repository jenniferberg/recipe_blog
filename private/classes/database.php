<?php
//Define databaase constants
const DBHOST = '';
const DBUSER = '';
const DBPASSWORD = '';
const DBNAME = 'recipe_blog';

class Database{
  private $connection;

  function __construct(){
    $this->db_open();
  }

  //Open database connection
  public function db_open(){
    //Connect to the database
    $this->connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBNAME);

    //Test connection
    if (mysqli_connect_errno()){
    	die("Database connection failed: " . mysqli_connect_error() . " (Error number:  " . mysqli_connect_errno() . ")"
    	);
      return false;
    }else{
      return true;
    }
  }

  //Query database for given SQL statement
  public function db_query($sql){
    $result = mysqli_query($this->connection, $sql);

    if(!$result){
      die("Database query failed.");
      return false;
    }else{
      return $result;
    }
  }

  //Return data into an associative array
  public function db_fetch_assoc($result){
    return mysqli_fetch_assoc($result);
  }

  //Escape strings to protect against SQL injection
  public function escape_string($string){
    return mysqli_real_escape_string($this->connection, $string);
  }

  //Get most recent inserted record
  public function get_id(){
    return mysqli_insert_id($this->connection);
  }

  //Free SQL result
  public function free_result($result){
    return mysqli_free_result($result);
  }

  //Close database connection
  public function db_close(){
    if(isset($this->connection)){
      mysqli_close($this->connection);
    }
  }
}

//Define database object
$db = new Database();

?>

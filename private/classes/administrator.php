<?php

class Administrator extends DatabaseQueries{
  private $id;
  private $username;
  private $password;
  private $type;
  protected static $tableName = "Administrators";
  protected static $orderBy = "username";
  protected static $id_type = "id";

  //Function to verify log in credentials
  function verify_login($username, $password){
  	global $db;

  	//Verify that username exists
  	$admin = $this->view_admin_by_username($username);

  	if($admin){
  		//If username exists, verify the password is correct
  		if(password_verify($password, $admin["password"])){
  			return $admin;
  		} else{
  			return false;
  		}
  	}else{
  		return false;
  	}
  }

  //Function to view administrator by username
  function view_admin_by_username($username){
  	global $db;

  	//Escape strings to protect against SQL injection
  	$username = $db->escape_string($username);

  	//Query the database for the provided username and password
  	$query  = "SELECT * FROM Administrators ";
  	$query .= "WHERE username = '{$username}' ";

  	$result = $db->db_query($query);
  	$admin = $db->fetch_assoc($result);
  	$db->free_result($result);

  	return $admin;
  }
}

?>

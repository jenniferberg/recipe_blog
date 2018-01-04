<?php

class Recipe extends DatabaseQueries{
  private $id;
  public $recipe_name;
  public $recipe_type;
  public $calories;
  public $active;
  protected static $tableName = "Recipes";
  protected static $orderBy = "recipe_name";
  protected static $id_type = "id";
  //Define list of recipe types
  public static $recipe_types = ["Meal","Snack","Dessert"];

  //Function to view all recipes for selected type
  public function view_recipes($type){
  	global $db;
  	global $admin;

  	//Escape strings to protect against SQL injection
  	$type_safe = $db->escape_string($type);

  	//Query the Recipes table for all recipes with specified type
  	$query  = "SELECT * FROM Recipes ";
  	$query .= "WHERE recipe_type = '{$type_safe}' ";
  	if($admin != 'admin' && $admin != 'superadmin'){
  		$query .= "AND active = 1 ";
  	}
  	$query .= "ORDER BY recipe_name";

    $result = $db->db_query($query);

		$output = [];
		while($row = $db->db_fetch_assoc($result)){
      $this->recipe_name[] = $row["recipe_name"];
      $this->recipe_type[] = $row["recipe_type"];
      $this->calories[] = $row["calories"];
      $this->active[] = $row["active"];
			$output[] = $row;
		}

  	return $output;
  }
}

?>

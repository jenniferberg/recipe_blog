<?php

class RecipeIngredient extends DatabaseQueries{
  public $recipe_id;
  public $ingredient_name;
  public $amount;
  public $unit;
  protected static $tableName = "Recipe_Ingredients";
  protected static $orderBy = "ingredient_name";
  protected static $id_type = "recipe_id";
  //Define list of available units of measure
  public static $units_of_measure = ["cups","gallons","handfuls","liters","milliliters","pieces","pints","tablespoons","teaspoons","units"];

  //Function to get recipe ingredients by id
  public function get_ingredients_by_id($id){
  	global $db;

  	//Escape strings to protect against SQL injection
  	$id_safe = $db->escape_string($id);

  	$query  = "SELECT
  							rec.id as rec_id,
  							rec.recipe_name,
  							rec.recipe_type,
  							rec.calories,
  							ing.id as ing_id,
  							ing.ingredient_name,
  							ing.amount,
  							ing.unit ";
  	$query .= "FROM Recipes rec ";
  	$query .= "LEFT OUTER JOIN Recipe_Ingredients ing ";
  	$query .= "ON rec.id = ing.recipe_id ";
  	$query .= "WHERE rec.id = '{$id_safe}' ";
  	$query .= "ORDER BY ing.id";

  	$result = $db->db_query($query);

  	$ingredients = [];

  	while($row = mysqli_fetch_assoc($result)){
      $this->recipe_id[] = $row["rec_id"];
      $this->ingredient_name[] = $row["ingredient_name"];
      $this->amount[] = $row["amount"];
      $this->unit[] = $row["unit"];
  		$ingredients[] = $row;
  	}

  	$db->free_result($result);
  	return $ingredients;

  }

  //Function to add recipe ingredients
  public function add_recipe_ingredients($ingredients, $rec_id, $end_h){
  	global $db;

  	$rec_id_safe = $db->escape_string($rec_id);

  	for($m=1; $m < $end_h; $m++){
  		$ingredient_name = $db->escape_string($ingredients["ingredient_name{$m}"]);
  		$amount = $db->escape_string($ingredients["amount{$m}"]);
  		$unit = $db->escape_string($ingredients["unit{$m}"]);

  		$query  = "INSERT INTO Recipe_Ingredients(recipe_id, ingredient_name, amount, unit) ";
  		$query .= "VALUES('{$rec_id_safe}','{$ingredient_name}','{$amount}','{$unit}') ";

  		$result = $db->db_query($query);
  	}

  	return $result;
  }

  //Function to update recipe ingredients
  public function update_ingredients($ingredients, $rec_id, $end_h){
  	global $db;

  	$count = count($this->get_ingredients_by_id($rec_id));

  	$rec_id_safe = $db->escape_string($rec_id);

  	for($m=1; $m < $end_h; $m++){
  		$ingredient_id = $db->escape_string($ingredients["ingredient_id{$m}"]);
  		$ingredient_name = $db->escape_string($ingredients["ingredient_name{$m}"]);
  		$amount = $db->escape_string($ingredients["amount{$m}"]);
  		$unit = $db->escape_string($ingredients["unit{$m}"]);

  		$query  = "SELECT count(*) as exist FROM Recipe_Ingredients ";
  		$query .= "WHERE id = '{$ingredient_id}' ";
  		$query .= "AND recipe_id = '{$rec_id_safe}'";

  		$result = $db->db_query($query);

  		$ingredient_exists = $db->db_fetch_assoc($result);

  		//If database line for ingredient already exists, update it, else insert new line
  		if($ingredient_exists['exist'] != 0){
  			$query  = "UPDATE Recipe_Ingredients ";
  			$query .= "SET ingredient_name = '{$ingredient_name}', ";
  			$query .= "amount = '{$amount}', ";
  			$query .= "unit = '{$unit}' ";
  			$query .= "WHERE id = '{$ingredient_id}' ";
  			$query .= "AND recipe_id = '{$rec_id_safe}'";

  			$result = $db->db_query($query);
  		} else{
  			$query  = "INSERT INTO Recipe_Ingredients(recipe_id, ingredient_name, amount, unit) ";
  			$query .= "VALUES('{$rec_id_safe}','{$ingredient_name}','{$amount}','{$unit}') ";

  			$result = $db->db_query($query);
  		}
  	}

  	//If the total lines submitted is less than the current lines in the database, delete remaining db lines
  	if($end_h - 1 < $count){
  		$query  = "DELETE FROM Recipe_Ingredients ";
  		$query .= "WHERE id > '{$ingredient_id}' ";
  		$query .= "AND recipe_id = '{$rec_id_safe}'";

  		$result = $db->db_query($query);
  	}

  	return $result;
  }
}
?>

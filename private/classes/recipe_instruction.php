<?php

class RecipeInstruction extends DatabaseQueries{
  public $recipe_id;
  public $instruction_number;
  public $time;
  public $instruction;
  protected static $tableName = "Recipe_Instructions";
  protected static $orderBy = "instruction_number";
  protected static $id_type = "recipe_id";

  //Function to get recipe instructions by id
  public function get_instructions_by_id($id){
  	global $db;

  	//Escape strings to protect against SQL injection
  	$id_safe = $db->escape_string($id);

  	$query  = "SELECT
  							rec.id as rec_id,
  							rec.recipe_name,
  							rec.recipe_type,
  							rec.calories,
  							ins.id as ins_id,
  							ins.instruction_number,
  							ins.time,
  							ins.instruction
  				";
  	$query .= "FROM Recipes rec ";
  	$query .= "LEFT OUTER JOIN Recipe_Instructions ins ";
  	$query .= "ON rec.id = ins.recipe_id ";
  	$query .= "WHERE rec.id = '{$id_safe}' ";
  	$query .= "ORDER BY ins.instruction_number";

  	$result = $db->db_query($query);

  	$instructions = [];

  	while($row = mysqli_fetch_assoc($result)){
      $this->recipe_id[] = $row["rec_id"];
      $this->instruction_number[] = $row["instruction_number"];
      $this->time[] = $row["time"];
      $this->instruction[] = $row["instruction"];
  		$instructions[] = $row;
  	}

  	$db->free_result($result);
  	return $instructions;
  }

  //Function to calculate total time of recipe
  public function get_total_time($id){
  	$ins_count = count($this->get_instructions_by_id($id));
  	$total_time = [];
  	for($i = 0; $i < $ins_count; $i++){
  		$total_time[] = $this->get_instructions_by_id($id)[$i]['time'];
  	}
  	$sum = array_sum($total_time);
  	return $sum;
  }

  //Function to add recipe instructions
  public function add_recipe_instructions($instructions, $rec_id, $end_i){
  	global $db;

  	$rec_id_safe = $db->escape_string($rec_id);

  	for($n = 1; $n < $end_i; $n++){
  		$time = $db->escape_string($instructions["time{$n}"]);
  		$instruction = $db->escape_string($instructions["instruction{$n}"]);

  		$query  = "INSERT INTO Recipe_Instructions(recipe_id, instruction_number, time, instruction) ";
  		$query .= "VALUES('{$rec_id_safe}','{$n}','{$time}','{$instruction}') ";

  		$result = $db->db_query($query);
  	}

  	return $result;
  }

  //Function to update recipe instructions
  public function update_instructions($instructions, $rec_id, $end_i){
  	global $db;

  	$count = count($this->get_instructions_by_id($rec_id));

  	$rec_id_safe = $db->escape_string($rec_id);

  	for($j = 1; $j < $end_i; $j++){
  		$instruction_id = $db->escape_string($instructions["instruction_id{$j}"]);
  		$ins_num = $db->escape_string($instructions["ins_num{$j}"]);
  		$time = $db->escape_string($instructions["time{$j}"]);
  		$instruction = $db->escape_string($instructions["instruction{$j}"]);

  		$query  = "SELECT count(*) as exist FROM Recipe_Instructions ";
  		$query .= "WHERE id = '{$instruction_id}' ";
  		$query .= "AND recipe_id = '{$rec_id_safe}'";

  		$result = $db->db_query($query);

  		$instruction_exists = $db->db_fetch_assoc($result);

  		//If database line for instruction already exists, update it, else insert new line
  		if($instruction_exists['exist'] != 0){
  			$query  = "UPDATE Recipe_Instructions ";
  			$query .= "SET instruction_number = '{$ins_num}', ";
  			$query .= "time = '{$time}', ";
  			$query .= "instruction = '{$instruction}' ";
  			$query .= "WHERE id = '{$instruction_id}' ";
  			$query .= "AND recipe_id = '{$rec_id_safe}'";

  			$result = $db->db_query($query);
  		} else{
  			$query  = "INSERT INTO Recipe_Instructions(recipe_id, instruction_number, time, instruction) ";
  			$query .= "VALUES('{$rec_id_safe}','{$j}','{$time}','{$instruction}') ";

  			$result = $db->db_query($query);
  		}
  	}

  	//If the total lines submitted is less than the current lines in the database, delete remaining db lines
  	if($end_i - 1 < $count){
  		$query  = "DELETE FROM Recipe_Instructions ";
  		$query .= "WHERE id > '{$instruction_id}' ";
  		$query .= "AND recipe_id = '{$rec_id_safe}'";

  		$result = $db->db_query($query);
  	}

  	return $result;
  }
}

?>

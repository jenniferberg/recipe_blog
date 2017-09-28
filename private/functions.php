<?php
//Define list of available units of measure
$units_of_measure = ["cups","pints","gallons","tablespoons","teaspoons","pieces","liters","milliliters","handfuls","units"];
sort($units_of_measure);

//Define list of recipe types
$recipe_types = ["Meal","Snack","Dessert"];

//Function to verify log in credentials
function verify_login($username, $password){
	global $db;
	
	//Verify that username exists
	$admin = view_admin_by_username($username);
	
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
	$username = mysqli_real_escape_string($db, $username);
	
	//Query the database for the provided username and password
	$query  = "SELECT * FROM Administrators ";
	$query .= "WHERE username = '{$username}' ";
	
	$result = mysqli_query($db, $query);
	
	//Test for errors in query
	if(!$result){
		die("Database query failed");
	}
	
	$admin = mysqli_fetch_assoc($result);
	
	mysqli_free_result($result);
	
	return $admin;
}

//Function to view all administrators
function view_admins(){
	global $db;
	
	$query  = "SELECT id, username, type FROM Administrators ";
	$query .= "ORDER BY username";
	
	$result = mysqli_query($db, $query);
	
	if(!$result){
		die("Database query failed.");
	}
	
	$output = "<table><th class=\"right\">Username</th><th class=\"left\">Access Type</th><th class=\"right\"></th>";
	
	while($row = mysqli_fetch_assoc($result)){
		$output .= "<tr><td class=\"right\">";
		$output .= htmlentities($row["username"]);
		$output .= "</td><td class=\"left\">";
		$output .= htmlentities($row["type"]);
		$output .= "</td><td class=\"right\">";
		$output .= "<a href=\"editAdmin.php?id=".urlencode($row["id"])."\">Edit</a>";
	}
	$output .= "</table>";
	
	mysqli_free_result($result);
	
	return $output;
}

//Function to view administrator by id
function view_admin_by_id($id){
	global $db;
	
	//Escape strings to protect against SQL injection
	$id = mysqli_real_escape_string($db, $id);
	
	$query  = "SELECT id, username, type FROM Administrators ";
	$query .= "WHERE id = '{$id}' ";
	$query .= "LIMIT 1";
	
	$result = mysqli_query($db, $query);
	
	if(!$result){
		die("Database query failed.");
	}
	
	$admin = mysqli_fetch_assoc($result);
	
	mysqli_free_result($result);
	
	return $admin;
}

//Function to add new administrator
function add_admin($admin){
	global $db;
	
	//Escape strings to protect against SQL injection
	$username = mysqli_real_escape_string($db, $admin["username"]);
	$password = mysqli_real_escape_string($db, $admin["password"]);
	$type = mysqli_real_escape_string($db, $admin["type"]);
	
	//Encrypt the password before adding to the database
	$hashed_password = password_hash($password, PASSWORD_BCRYPT);
	
	//Add new admin to database
	$query  = "INSERT INTO Administrators(username, password, type) ";
	$query .= "VALUES('{$username}','{$hashed_password}','{$type}')";
	
	$result = mysqli_query($db, $query);
	
	if(!$result){
		die("Database query failed.");
	}
	
	return $result;
}

//Function to update an administrator
function update_admin($admin, $id){
	global $db;
	
	//Escape strings to protect against SQL injection
	$id = mysqli_real_escape_string($db, $id);
	$username = mysqli_real_escape_string($db, $admin["username"]);
	$password = mysqli_real_escape_string($db, $admin["password"]);
	$type = mysqli_real_escape_string($db, $admin["type"]);
	
	//Encrypt the password before adding to the database
	$hashed_password = password_hash($password, PASSWORD_BCRYPT);
	
	$query  = "UPDATE Administrators ";
	$query .= "SET username = '{$username}', ";
	$query .= "password = '{$hashed_password}', ";
	$query .= "type = '{$type}' ";
	$query .= "WHERE id = '{$id}' ";
	$query .= "LIMIT 1";
	
	$result = mysqli_query($db, $query);
	
	if(!$result){
		die("Database query failed.");
	}
	
	return $result;
	
}

//Function to delete an administrator
function delete_admin($id){
	global $db;
	
	$id = mysqli_real_escape_string($db, $id);
	
	$query  = "DELETE FROM Administrators ";
	$query .= "WHERE id = '{$id}' ";
	$query .= "LIMIT 1";
	
	$result = mysqli_query($db, $query);
	
	if(!$result){
		die("Database query failed.");
	}
	
	mysqli_free_result($result);
	
	//Redirect user to view admins page
	header("Location: ../public/admins.php");
	exit;
	
}

//Function to get basic information about specified recipe
function get_recipe_info($id){
	global $db;
	
	//Escape strings to protect against SQL injection
	$id_safe = mysqli_real_escape_string($db, $id);
	
	//Query the Recipes table for specified id
	$query  = "SELECT * FROM Recipes ";
	$query .= "WHERE id = '{$id_safe}' ";
	$query .= "LIMIT 1";

	$result = mysqli_query($db, $query);
	
	//Test for errors in query
	if(!$result){
		die("Database query failed");
	}
	
	$recipe_info = mysqli_fetch_assoc($result);
	
	mysqli_free_result($result);
	
	return $recipe_info;
}

//Function to view all recipes for selected type
function view_recipes($type){
	global $db;
	global $admin;
	
	//Escape strings to protect against SQL injection
	$type_safe = mysqli_real_escape_string($db, $type);
	
	//Query the Recipes table for all recipes with specified type
	$query  = "SELECT * FROM Recipes ";
	$query .= "WHERE recipe_type = '{$type_safe}' ";
	if($admin != 'admin' && $admin != 'superadmin'){
		$query .= "AND active = 1 ";
	}
	$query .= "ORDER BY recipe_name";
	
	$result = mysqli_query($db, $query);
	
	//Test for errors in query
	if(!$result){
		die("Database query failed");
	}
	
	//Output data
	$output = '';
	
	while($row = mysqli_fetch_assoc($result)){
		$picture = evaluate_name($row["recipe_name"]);

		$output .= "<div class=\"list\">";
		$output .= "<div class=\"center floatLeft\">";
		if(file_exists("images/".$picture.".jpg")){
			$output .= "<div class=\"picture\">";
			$output .= "<a href=\"view.php?id=";
			$output .= urlencode($row["id"])."\">";
			$output .= "<img src=\"images/".$picture.".jpg\"";
			$output .= " alt=".$row["recipe_name"];
			$output .= " height=\"200\" width=\"180\"></a></div>";
		} else {
			$output .="<div class=\"picture border\">No picture</div>";
		}
		$output .= "</div>";
		$output .= "<div class=\"center floatLeft\">";
		$output .= "<div class=\"left colMax\"><a href=\"view.php?id=";
		$output .= urlencode($row["id"]);
		$output .= "\">".htmlentities($row["recipe_name"])."</a>";
		$output .= "</div><div class=\"left\">";
		$output .= htmlentities($row["calories"])." calories";
		$output .= "</div><div class=\"left\">";
		$output .= count(get_ingredients_by_id($row["id"]))." ingredients";
		$output .= "</div><div class=\"left\">";
		$output .= get_total_time($row["id"])." minutes";
		$output .= "</div>";
		if($admin == 'admin' || $admin == 'superadmin'){
			$output .= "<div class=\"left\">";
			$output .= "<a href=\"edit.php?id=";
			$output .= urlencode($row["id"]);
			$output .= "\">Edit</a>";
			$output .= "</div>";
		}
		$output .= "</div>";
		$output .= "</div>";	
	}	
	mysqli_free_result($result);
	
	return $output;
}
 
//Function to get recipe ingredients by id
function get_ingredients_by_id($id){
	global $db;
	
	//Escape strings to protect against SQL injection
	$id_safe = mysqli_real_escape_string($db, $id);
	
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
	
	$result = mysqli_query($db, $query);
	
	//Test for errors in query
	if(!$result){
		die("Database query failed");
	}
	
	$ingredients = [];
		
	while($row = mysqli_fetch_assoc($result)){
		$ingredients[] = $row;
	}
	
	mysqli_free_result($result);
	return $ingredients;
	
}

//Function to view ingredients for specified recipe
function view_recipe_ingredients($id){	
	//Get the ingredients for the selected recipe
	$row = get_ingredients_by_id($id);
	
	$count = count($row);
	
	//Output data
	$output  = "<table class=\"center\">";
	
	for($i=0; $i < $count; $i++){
		$output .= "<tr><td class=\"right colMax\">";
		$output .= htmlentities($row[$i]["ingredient_name"]);
		$output .= "</td><td class=\"left colMax\">";
		$output .= htmlentities($row[$i]["amount"])." ". htmlentities($row[$i]["unit"]);
		$output .= "</td></tr>";
	}	
	
	$output .= "</table>";
	
	return $output;
}

//Function to get recipe instructions by id
function get_instructions_by_id($id){
	global $db;
	
	//Escape strings to protect against SQL injection
	$id_safe = mysqli_real_escape_string($db, $id);
	
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
	
	$result = mysqli_query($db, $query);
	
	//Test for errors in query
	if(!$result){
		die("Database query failed");
	}
	
	$instructions = [];
		
	while($row = mysqli_fetch_assoc($result)){
		$instructions[] = $row;
	}
	
	mysqli_free_result($result);
	return $instructions;
}

//Function to calculate total time of recipe
function get_total_time($id){
	$ins_count = count(get_instructions_by_id($id));
	$total_time = [];
	for($i = 0; $i < $ins_count; $i++){
		$total_time[] = get_instructions_by_id($id)[$i]['time'];
	}
	$sum = array_sum($total_time);
	return $sum;
}

//Function to view instructions for specified recipe
function view_recipe_instructions($id){
	//Get instructions for the selected recipe
	$row = get_instructions_by_id($id);
	
	$count = count($row);
	
	$output  = "<table class=\"center\">";
	$output .= "<th></th><th>Instruction</th><th>Time (Minutes)</th>";
	
	for($i=0; $i < $count; $i++){
		$output .= "<tr><td class=\"left\">";
		$output .= htmlentities($row[$i]["instruction_number"]);
		$output .= "</td><td class=\"left colMin\">";
		$output .= htmlentities($row[$i]["instruction"]);
		$output .= "</td><td class=\"colMax\">";
		$output .= htmlentities($row[$i]["time"]);
		$output .= "</td></tr>";
		
	}	
	$output .= "</table>";
	
	return $output;	
}

//Function to add recipe header record
function add_recipe_header($recipe){
	global $db;
	
	//Escape strings to protect against SQL injection
	$recipe_name = mysqli_real_escape_string($db, $recipe["recipe_name"]);
	$recipe_type = mysqli_real_escape_string($db, $recipe["recipe_type"]);
	$calories = mysqli_real_escape_string($db, $recipe["calories"]);
	$active = mysqli_real_escape_string($db, $recipe["active"]);

	//Add new recipe to the Recipes table
	$query  = "INSERT INTO Recipes(recipe_name, recipe_type, calories, active) ";
	$query .= "VALUES('{$recipe_name}','{$recipe_type}','{$calories}','{$active}')";

	$result = mysqli_query($db, $query);
	
	//Test for errors in query
	if(!$result){
		die("Database query failed");
	}
		
	return $result;
}

//Function to add recipe ingredients
function add_recipe_ingredients($ingredients, $rec_id, $end_h){
	global $db;
	
	$rec_id_safe = mysqli_real_escape_string($db, $rec_id);
	
	for($m=1; $m < $end_h; $m++){
		$ingredient_name = mysqli_real_escape_string($db, $ingredients["ingredient_name{$m}"]);
		$amount = mysqli_real_escape_string($db, $ingredients["amount{$m}"]);
		$unit = mysqli_real_escape_string($db, $ingredients["unit{$m}"]);
		
		$query  = "INSERT INTO Recipe_Ingredients(recipe_id, ingredient_name, amount, unit) ";
		$query .= "VALUES('{$rec_id_safe}','{$ingredient_name}','{$amount}','{$unit}') ";

		$result = mysqli_query($db, $query);
	}
	
	//Test for errors in query
	if(!$result){
		die("Database query failed");
	}
	
	return $result;
}

//Function to add recipe instructions
function add_recipe_instructions($instructions, $rec_id, $end_i){
	global $db;
	
	$rec_id_safe = mysqli_real_escape_string($db, $rec_id);
	
	for($j = 1; $j < $end_i; $j++){
		$time = mysqli_real_escape_string($db, $instructions["time{$j}"]);
		$instruction = mysqli_real_escape_string($db, $instructions["instruction{$j}"]);
		
		$query  = "INSERT INTO Recipe_Instructions(recipe_id, instruction_number, time, instruction) ";
		$query .= "VALUES('{$rec_id_safe}','{$j}','{$time}','{$instruction}') ";

		$result = mysqli_query($db, $query);
	}
	//Test for errors in query
	if(!$result){
		die("Database query failed");
	}
	
	return $result;
}

//Function to add new recipe
function add_recipe($recipe, $ingredients, $instructions, $end_h, $end_i){
	global $db;
	
	//Add recipe header information
	add_recipe_header($recipe);
	
	//Get the id of the new recipe
	$rec_id = mysqli_insert_id($db); 
	
	//Add recipe ingredients
	add_recipe_ingredients($ingredients, $rec_id, $end_h);
	
	//Add recipe instructions
	add_recipe_instructions($instructions, $rec_id, $end_i);
	
	//Reset session line count values to zero
	$_SESSION["i"] = 0;
	$_SESSION["h"] = 0;
	
	//Redirect user to view the newly added recipe
	header("Location: view.php?id=".urlencode($rec_id));
	exit;
}

//Function to update recipe header record
function update_recipe_header($recipe, $id){
	global $db;
	
	//Escape strings to protect against SQL injection
	$id_safe = mysqli_real_escape_string($db, $id);
	$recipe_name = mysqli_real_escape_string($db, $recipe["recipe_name"]);
	$recipe_type = mysqli_real_escape_string($db, $recipe["recipe_type"]);
	$calories = mysqli_real_escape_string($db, $recipe["calories"]);
	$active = mysqli_real_escape_string($db, $recipe["active"]);

	//Add new recipe to the Recipes table
	$query  = "UPDATE Recipes ";
	$query .= "SET recipe_name = '{$recipe_name}', ";
	$query .= "recipe_type = '{$recipe_type}', ";
	$query .= "calories = '{$calories}', ";
	$query .= "active = '{$active}' ";
	$query .= "WHERE id = '{$id_safe}' ";
	$query .= "LIMIT 1";
	
	$result = mysqli_query($db, $query);
	
	//Test for errors in query
	if(!$result){
		die("Database query failed");
	}
		
	return $result;
}

//Function to update recipe ingredients
function update_ingredients($ingredients, $rec_id, $end_h){
	global $db;
	
	$count = count(get_ingredients_by_id($rec_id));
	
	$rec_id_safe = mysqli_real_escape_string($db, $rec_id);
	
	for($m=1; $m < $end_h; $m++){
		$ingredient_id = mysqli_real_escape_string($db, $ingredients["ingredient_id{$m}"]);
		$ingredient_name = mysqli_real_escape_string($db, $ingredients["ingredient_name{$m}"]);
		$amount = mysqli_real_escape_string($db, $ingredients["amount{$m}"]);
		$unit = mysqli_real_escape_string($db, $ingredients["unit{$m}"]);
		
		$query  = "SELECT count(*) as exist FROM Recipe_Ingredients ";
		$query .= "WHERE id = '{$ingredient_id}' ";
		$query .= "AND recipe_id = '{$rec_id_safe}'";
		
		$result = mysqli_query($db, $query);
		
		//Test for errors in query
		if(!$result){
			die("Database query failed");
		}
		
		$ingredient_exists = mysqli_fetch_assoc($result);
		
		//If database line for ingredient already exists, update it, else insert new line
		if($ingredient_exists['exist'] != 0){
			$query  = "UPDATE Recipe_Ingredients ";
			$query .= "SET ingredient_name = '{$ingredient_name}', ";
			$query .= "amount = '{$amount}', ";
			$query .= "unit = '{$unit}' ";
			$query .= "WHERE id = '{$ingredient_id}' ";
			$query .= "AND recipe_id = '{$rec_id_safe}'";

			$result = mysqli_query($db, $query);
		} else{
			$query  = "INSERT INTO Recipe_Ingredients(recipe_id, ingredient_name, amount, unit) ";
			$query .= "VALUES('{$rec_id_safe}','{$ingredient_name}','{$amount}','{$unit}') ";

			$result = mysqli_query($db, $query);
		}
	}
	
	//If the total lines submitted is less than the current lines in the database, delete remaining db lines
	if($end_h - 1 < $count){
		$query  = "DELETE FROM Recipe_Ingredients ";
		$query .= "WHERE id > '{$ingredient_id}' ";
		$query .= "AND recipe_id = '{$rec_id_safe}'";

		$result = mysqli_query($db, $query);
	}
	
	//Test for errors in query
	if(!$result){
		die("Database query failed");
	}
	
	return $result;
}

//Function to update recipe instructions
function update_instructions($instructions, $rec_id, $end_i){
	global $db;
	
	$count = count(get_instructions_by_id($rec_id));
	
	$rec_id_safe = mysqli_real_escape_string($db, $rec_id);
	
	for($j = 1; $j < $end_i; $j++){
		$instruction_id = mysqli_real_escape_string($db, $instructions["instruction_id{$j}"]);
		$ins_num = mysqli_real_escape_string($db, $instructions["ins_num{$j}"]);
		$time = mysqli_real_escape_string($db, $instructions["time{$j}"]);
		$instruction = mysqli_real_escape_string($db, $instructions["instruction{$j}"]);
		
		$query  = "SELECT count(*) as exist FROM Recipe_Instructions ";
		$query .= "WHERE id = '{$instruction_id}' ";
		$query .= "AND recipe_id = '{$rec_id_safe}'";
		
		$result = mysqli_query($db, $query);
		
		//Test for errors in query
		if(!$result){
			die("Database query failed");
		}
		
		$instruction_exists = mysqli_fetch_assoc($result);
		
		//If database line for instruction already exists, update it, else insert new line
		if($instruction_exists['exist'] != 0){
			$query  = "UPDATE Recipe_Instructions ";
			$query .= "SET instruction_number = '{$ins_num}', ";
			$query .= "time = '{$time}', ";
			$query .= "instruction = '{$instruction}' ";
			$query .= "WHERE id = '{$instruction_id}' ";
			$query .= "AND recipe_id = '{$rec_id_safe}'";
			
			echo $query;
			echo "<br />";

			$result = mysqli_query($db, $query);
		} else{
			$query  = "INSERT INTO Recipe_Instructions(recipe_id, instruction_number, time, instruction) ";
			$query .= "VALUES('{$rec_id_safe}','{$j}','{$time}','{$instruction}') ";
			echo $query;
			echo "<br />";
			$result = mysqli_query($db, $query);
		}
	}
	
	//If the total lines submitted is less than the current lines in the database, delete remaining db lines
	if($end_i - 1 < $count){
		$query  = "DELETE FROM Recipe_Instructions ";
		$query .= "WHERE id > '{$instruction_id}' ";
		$query .= "AND recipe_id = '{$rec_id_safe}'";
		echo $query;
		echo "<br />";
		$result = mysqli_query($db, $query);
	}
	
	//Test for errors in query
	if(!$result){
		die("Database query failed");
	}
	
	return $result;
}

//Function to update recipe
function update_recipe($recipe, $ingredients, $instructions, $rec_id, $end_h, $end_i){
	global $db;
	
	//Update recipe header information
	update_recipe_header($recipe, $rec_id);
	
	//Update recipe ingredients
	update_ingredients($ingredients, $rec_id, $end_h);
	
	//Update recipe instructions
	update_instructions($instructions, $rec_id, $end_i);
	
	//Reset session line count values to zero
	$_SESSION["i"] = 0;
	$_SESSION["h"] = 0;
	
	//Redirect user to view the newly added recipe
	header("Location: view.php?id=".urlencode($rec_id));
	exit;
}

//Function to delete recipe instructions
function delete_instructions($id){
	global $db;
	
	$id_safe = mysqli_real_escape_string($db, $id);
	
	//Delete instructions
	$query  = "DELETE FROM Recipe_Instructions ";
	$query .= "WHERE recipe_id = '{$id_safe}'";
	
	$result = mysqli_query($db, $query);
	
	//Test for errors in query
	if(!$result){
		die("Database query failed");
	}

	return $result;
	
}

//Function to delete recipe ingredients
function delete_ingredients($id){
	global $db;
	
	$id_safe = mysqli_real_escape_string($db, $id);
	
	//Delete instructions
	$query  = "DELETE FROM Recipe_Ingredients ";
	$query .= "WHERE recipe_id = '{$id_safe}'";
	
	$result = mysqli_query($db, $query);
	
	//Test for errors in query
	if(!$result){
		die("Database query failed");
	}

	return $result;
}

//Function to delete entire recipe
function delete_recipe($id, $type){
	global $db;
	
	$id_safe = mysqli_real_escape_string($db, $id);
	
	//Delete instructions
	delete_instructions($id_safe);
	
	//Delete ingredients
	delete_ingredients($id_safe);
	
	//Delete recipe header information
	$query  = "DELETE FROM Recipes ";
	$query .= "WHERE id = '{$id_safe}'";
	
	$result = mysqli_query($db, $query);
	
	//Test for errors in query
	if(!$result){
		die("Database query failed");
	}

	mysqli_free_result($result);
	
	//Redirect user to list of recipes for specified type
	header("Location: ../public/index.php?type=".htmlentities(urlencode($type)));
	exit;
}



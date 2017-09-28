<?php
$special_chars = ['!','@','#','$','%','*','~'];
$numbers = ['0','1','2','3','4','5','6','7','8','9'];
//Validation Functions

//Function to create a readable name from form variable name
function readable_name($variable){
	$variable = ucfirst($variable);
	$variable = str_replace('_',' ',$variable);
	$variable = preg_replace('/[0-9]+/','',$variable);
	return $variable;
}

//Function to evaluate field name
function evaluate_name($name){
	$name = strtolower($name);
	$name = str_replace(" ","",$name);
	
	return $name;
}

//Function to see if recipe name already exists
function does_recipe_exist($recipe_name, $id=0){
	global $db;
	$original_name = $recipe_name;
	
	$recipe_name = evaluate_name($recipe_name);
	$recipe_name_safe = mysqli_real_escape_string($db, $recipe_name);
	$id_safe = mysqli_real_escape_string($db, $id);
	
	//Query the Recipes table for specified recipe name
	$query  = "SELECT count(*) as countNames FROM Recipes ";
	$query .= "WHERE lower(replace(recipe_name,' ','')) = '{$recipe_name_safe}' ";
	$query .= "AND id <> '{$id_safe}'";
	
	$result = mysqli_query($db, $query);
	//Test for errors in query
	if(!$result){
		die("Database query failed");
	}
	
	$recipe_exists = mysqli_fetch_assoc($result);

	$message = [];
	if($recipe_exists["countNames"] != 0){
		$message["recipe_name"] = "The recipe name \"{$original_name}\" already exists. Please enter a new name. ";
	}
	
	//mysqli_free_result($result);
	return $message;
}

//Required Fields
function required($array){
	$message = [];
	foreach($array as $field){
		$value = trim($_POST[$field]);
		if(!isset($value) || $value === ""){
			//$list[] = $field;
			$message[$field] =  readable_name($field)." is a required field. ";
		}
	}
	return $message;
}

//Fields with minimum values
function min_value($array){
	$message = [];
	foreach($array as $field){
		$value = (int)trim($_POST[$field]);
		if($value <= 0){
			$message[$field] =  readable_name($field)." must have a value of at least 1. ";
		}
	}
	return $message;
}

//Fields with maximum lengths
function max_length($array){
	$message = [];
	foreach($array as $field=>$limit){
		$length = strlen(trim($_POST[$field]));
		if($length > $limit){
			$message[$field] =  readable_name($field)." cannot exceed ".$limit." characters. ";
		}
	}
	
	return $message;
}

//Fields with mimimum lengths
function min_length($array){
	$message = [];
	foreach($array as $field=>$minimum){
		$length = strlen(trim($_POST[$field]));
		if($length < $minimum){
			$message[$field] = readable_name($field)." must be at least ".$minimum." characters. ";
		}
	}
	return $message;
}

//Function to validate all fields for adding/editing a recipe
function validation($required, $min_value, $max_length){
	$message = [];
	
	foreach($required as $field){
		if(isset(required($required)[$field])){
			if(isset($message[$field])){
				$message[$field].= required($required)[$field];
			}else {
				$message[$field] = required($required)[$field];
			}
		}
	}
	
	foreach($min_value as $field){
		if(isset(min_value($min_value)[$field])){
			if(isset($message[$field])){
				$message[$field].= min_value($min_value)[$field];
			} else {
				$message[$field] = min_value($min_value)[$field];
			}
		}
	}
	
	foreach($max_length as $key=>$field){
		if(isset(max_length($max_length)[$key])){
			if(isset($message[$key])){
				$message[$key].= max_length($max_length)[$key];
			} else {
				$message[$key] = max_length($max_length)[$key];
			}
		}
	}
	
	return $message;
}

//Function to check if username already exists
function does_username_exist($username, $id=0){
	global $db;
	$original_name = $username;
	
	$username = evaluate_name($username);
	$username_safe = mysqli_real_escape_string($db, $username);
	$id_safe = mysqli_real_escape_string($db, $id);
	
	//Query the Recipes table for specified recipe name
	$query  = "SELECT count(*) as countNames FROM Administrators ";
	$query .= "WHERE lower(replace(username,' ','')) = '{$username_safe}' ";
	$query .= "AND id <> '{$id_safe}'";
	
	$result = mysqli_query($db, $query);
	//Test for errors in query
	if(!$result){
		die("Database query failed");
	}
	
	$username_exists = mysqli_fetch_assoc($result);

	$message = [];
	if($username_exists["countNames"] != 0){
		$message["username"] = "The username \"{$original_name}\" already exists. Please enter a new username. ";
	}
	
	return $message;
}

//Function to determine if field contains any spaces
function contains_spaces($array){
	$contains_spaces = [];
	foreach($array as $field){
		if(strpos($_POST[$field],' ') !== false){
			$contains_spaces[$field] = $_POST[$field];
		}
	}
	return $contains_spaces;
}



//Function to determine if password contains required values
function contains_values($values){
	$contains_vals = [];
	foreach($values as $val){
		if(strpos($_POST["password"], $val) !== false){
			$contains_vals["password"] = $val;
		}
	}
	return $contains_vals;
}


<?php
require_once('../private/initialize.php');
include_once("../private/layout/header.php");
$choice = 'Edit';

//Ensure an admin or superadmin is logged in
if($admin != 'superadmin' && $admin != 'admin'){
	header("Location:  index.php");
	exit;
}

//Ensure id is a valid recipe
if(isset($_GET['id'])){
	if(get_recipe_info($_GET['id'])){
		$id = $_GET['id'];
	} else {
		header("Location: index.php");
		exit;
	}
}

$get_recipe = get_recipe_info($id);
$get_ingredients = get_ingredients_by_id($id);
$get_instructions = get_instructions_by_id($id);

//Define variables to track number of rows added for ingredients and instructions
$h = (isset($_SESSION["h"]) && $_SESSION["h"] != 0) ? $_SESSION["h"] : count($get_ingredients); //number of rows of ingredients
$i = (isset($_SESSION["i"]) && $_SESSION["i"] != 0) ? $_SESSION["i"] : count($get_instructions); //number of rows of instructions
$end_h = $h + 1;
$end_i = $i + 1;

//Set variables for POST values if form has been submitted, else GET values
$recipe_name = isset($_POST['recipe_name']) ? $_POST['recipe_name'] : $get_recipe['recipe_name'];
$recipe_type = isset($_POST['recipe_type']) ? $_POST['recipe_type'] : $get_recipe['recipe_type'];
$calories = isset($_POST['calories']) ? (int)$_POST['calories'] : $get_recipe['calories'];
$active = isset($_POST['active']) ? (int)($_POST['active']) : $get_recipe['active'];


$ingredient_name = [];
$amount = [];
$unit = [];
$time = [];
$instruction = [];

for ($m = 1; $m < $end_h; $m++){
	$ingredient_id[$m] = isset($get_ingredients[$m - 1]['ing_id']) ? $get_ingredients[$m - 1]['ing_id'] : 0;
	$ingredient_name[$m] = isset($_POST["ingredient_name{$m}"]) ? htmlentities($_POST["ingredient_name{$m}"], ENT_QUOTES) : $get_ingredients[$m - 1]['ingredient_name'];
	$amount[$m] = isset($_POST["amount{$m}"]) ? htmlentities($_POST["amount{$m}"], ENT_QUOTES) : $get_ingredients[$m - 1]['amount'];
	$unit[$m] = isset($_POST["unit{$m}"]) ? htmlentities($_POST["unit{$m}"]) : $get_ingredients[$m - 1]['unit'];
}

for ($j = 1; $j < $end_i; $j++){ 
	$instruction_id[$j] = isset($get_instructions[$j - 1]['ins_id']) ? $get_instructions[$j - 1]['ins_id'] : 0;
	$time[$j] = isset($_POST["time{$j}"]) ? (int)htmlentities($_POST["time{$j}"]) : $get_instructions[$j - 1]['time'];
	$instruction[$j] = isset($_POST["instruction{$j}"]) ? htmlentities($_POST["instruction{$j}"], ENT_QUOTES) : $get_instructions[$j - 1]['instruction'];
}

//Create empty arrays to add recipe information to before adding to database
$recipe = [];
$ingredients = [];
$instructions = [];

/*If form is submitted, validate form.
  If there are form errors, display error messages.
  If form is filled out correctly, enter new recipe into the database.
*/
if($_SERVER['REQUEST_METHOD'] == 'POST'){	
	//Determine if recipe name already exists
	if(isset($_POST["recipe_name"])){
		$name = does_recipe_exist($_POST["recipe_name"], $id);
	} else {$name = [];}
	
	//Array of fields that are required to be completed
	$required_fields = ["recipe_name","recipe_type","calories","active"];
	
	//Array of fields that have a minimum value of 1
	$min_values = ["calories"];
	
	//Array of fields with maximum character limits
	$max_values = ["recipe_name" => 50, "recipe_type" => 10, "calories" => 11, "active" => 1];

	//Determine how many ingredients are listed - add to required and maximum character limit arrays
	for($m=1; $m < $end_h; $m++){
		$required_fields[] = "ingredient_name{$m}";
		$required_fields[] = "amount{$m}";
		$required_fields[] = "unit{$m}";
		
		$max_values["ingredient_name{$m}"] = 50;
		$max_values["amount{$m}"] = 10;
		$max_values["unit{$m}" ] = 20;
	}
	
	//Determine how many instructions are listed - add to required, minimum value, and max character limit arrays
	for($x=1; $x < $end_i; $x++){
		$min_values[] = "time{$x}";

		$required_fields[] = "time{$x}";
		$required_fields[] = "instruction{$x}";
		
		$max_values["time{$x}"] = 11;
	}
	
	//Define file formats for recipe image
	$directory = "C:/xampp/htdocs/sites/recipe_blog/public/images/";
	$original_file = basename($_FILES["recipeImage"]["name"]);
	$imageFileType = pathinfo($original_file,PATHINFO_EXTENSION);
	$newFileName = evaluate_name($recipe_name);

	$file = $directory.$newFileName.".".$imageFileType;
	echo $file;
	echo "<br />";
	
	
	//Check to make sure file upload is a picture
	if(isset($imageFileType) && strlen($imageFileType) > 0){
		echo "test";
		if(strtolower($imageFileType) == "jpg" || strtolower($imageFileType) == "jpeg" || strtolower($imageFileType) == "png"){
			$uploadOk = 1;
			$picture_error = "";
		}else{
			$uploadOk = 0;
			$picture_error = "This is not a valid file format. Please upload a JPG, JPEG, or PNG file.";
		}
	}else{
		echo $imageFileType;
	}
	
	
	//Validate fields identified in the required, minimum value, and max character limit arrays
	$validate = validation($required_fields, $min_values, $max_values);
	$req = required($required_fields);
	$min = min_value($min_values);
	$max = max_length($max_values);
	
	//If there are no validation errors, insert new recipe into database and redirect user to main page
	if($validate == [] && $name == []){
		//Create array $recipe to add recipe header information
		$recipe["recipe_name"] = $recipe_name;
		$recipe["recipe_type"] = $recipe_type;
		$recipe["calories"] = $calories;
		$recipe["active"] = $active;
		
		//Create array $ingredients to add ingredient information
		for($m=1; $m < $end_h; $m++){
			$ingredients["ingredient_id{$m}"] = $ingredient_id[$m];
			$ingredients["ingredient_name{$m}"] = $_POST["ingredient_name{$m}"];
			$ingredients["amount{$m}"] = $_POST["amount{$m}"];
			$ingredients["unit{$m}"] = $_POST["unit{$m}"];				
		}
		
		//Create array $instructions to add instruction information
		for ($j = 1; $j < $end_i; $j++){
			$instructions["instruction_id{$j}"] = $instruction_id[$j];
			$instructions["ins_num{$j}"] = $j;
			$instructions["time{$j}"] = $_POST["time{$j}"];
			$instructions["instruction{$j}"] = $_POST["instruction{$j}"];
		}
		
		//Update picture, if one has been chosen
		if(isset($uploadOk) && $uploadOk == 1){
			move_uploaded_file($_FILES["recipeImage"]["tmp_name"], $file);
		}
		
		//Update recipe
		update_recipe($recipe, $ingredients, $instructions, $id, $end_h, $end_i);
		$error_warning = "";
	} 
	//If there are validation errors, do not submit recipe to database
	else{
		$error_warning = "Please fix the below errors.";
	}
	
} else {
	$error_warning = "";
	$req = [];
	$min = [];
	$max = [];
	$name = [];
}
?>
<hr />
<div class="centerAlign">
<a href="view.php?id=<?php echo urlencode($id); ?>" class="buttonLink" onclick="return confirm('Viewing the current recipe will discard all changes.  Are you sure you want to view the recipe?')">View Recipe</a></button>

</div>
<?php include_once("form.php"); ?>


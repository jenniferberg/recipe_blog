<?php
require_once('../private/initialize.php');

if($admin != 'superadmin' && $admin != 'admin'){
	header("Location:  ../public/index.php");
	exit;
}elseif(isset($_POST['id'])){
	$id = $_POST["id"];

	$type = $recipe->select_by_id($id)[0]['recipe_type'];

	//Delete instructions
	$recipe_instruction->delete($id);

	//Delete ingredrients
	$recipe_ingredient->delete($id);

	//Delete recipe header
	$recipe->delete($id);

	//Redirect user to list of recipes for specified type
	header("Location: ../public/index.php?type=".htmlentities(urlencode($type)));
	exit;

} else{
	header("Location: ../public/index.php");
	exit;
}

?>

<?php
require_once('../private/initialize.php');

if($admin != 'superadmin' && $admin != 'admin'){
	header("Location:  ../public/index.php");
	exit;
}elseif(isset($_POST['id'])){
	$id = $_POST["id"];
	
	$type = get_recipe_info($id)['recipe_type'];

	delete_recipe($id, $type);	
	
} else{
	header("Location: ../public/index.php");
	exit;
}

?>
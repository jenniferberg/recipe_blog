<?php
require_once('../private/initialize.php');

if($admin != 'superadmin'){
	header("Location:  ../public/index.php");
	exit;
}elseif(isset($_POST['id'])){
	$id = $_POST["id"];
	
	delete_admin($id);
	
} else{
	header("Location: ../public/index.php");
	exit;
}

?>
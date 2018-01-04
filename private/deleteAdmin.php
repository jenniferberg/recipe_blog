<?php
require_once('../private/initialize.php');

if($admin != 'superadmin'){
	header("Location:  ../public/index.php");
	exit;
}elseif(isset($_POST['id'])){
	$id = $_POST["id"];

	$administrator->delete($id);
	
	//Redirect user to view admins page
	header("Location: ../public/admins.php");
	exit;

} else{
	header("Location: ../public/index.php");
	exit;
}

?>

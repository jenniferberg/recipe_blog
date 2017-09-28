<?php
require_once('../private/initialize.php');
include_once("../private/layout/header.php");

$id = isset($_GET['id']) ? $_GET['id'] : 0;

if($admin != 'superadmin'){
	header("Location:  index.php");
	exit;
}elseif(isset($_GET['id'])){
	$view = view_admin_by_id($id);
	if($view == []){
		header("Location:  admins.php");
		exit;
	}	
}

$username = isset($_POST['username']) ? $_POST['username'] : $view['username'];
$password = isset($_POST['password']) ? $_POST['password'] : '';
$type = isset($_POST['type']) ? $_POST['type'] : $view['type'];

$admin = [];

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	//Determine if username already exists
	if(isset($_POST['username'])){
		$name = does_username_exist($_POST['username'],$id);
	} else {$name = [];}
	
	//Array of fields to be validated
	$fields = ["username", "password"];
	
	//Array of fields with maximum character limits
	$max_char = ["username" => 20, "password" => 20];
	
	//Array of fields with mimimum character limits
	$min_char = ["username" => 6, "password" => 6];
	
	//Validate the username and password for requirements
	$max = max_length($max_char);
	$min = min_length($min_char);
	$spaces = contains_spaces($fields);
	$numbers = contains_values($numbers);
	$special_chars = contains_values($special_chars);
	
	//If there are no validation errors, insert new administrator to the database and redirect user to main page
	if($name == [] && $max == [] && $min == [] && $spaces == [] && $numbers != [] && $special_chars != []){
		$error_warning = "<br />"; 
		$admin["username"] = $username;
		$admin["password"] = $password;
		$admin["type"] = $type;
		
		//Update administrator in the database
		update_admin($admin, $id);
		
		//Redirect user to view admins page
		header("Location: admins.php");
		exit;
		
	} else{
		$error_warning = "Please fix the noted errors.";
	}
	
} else {
	$error_warning = "<br />";
}

?>

<hr />
<div class="centerAlign">
<form method="POST" action="../private/deleteAdmin.php" >
	<input type="hidden" name="id" value="<?php echo htmlentities($id); ?>" />
	<input type="submit" name="submit" class="button" id="delete" value="Delete Admin" onclick="return confirm('Are you sure you want to delete this administrator?')"/>
</form>
</div>
<div class="main">
<h3>Edit Administrator</h3>
<?php
include_once("adminForm.php");
?>
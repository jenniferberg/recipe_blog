<?php
require_once('../private/initialize.php');
include_once("../private/layout/header.php");


$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

/*If form is submitted, validate form.
  If there are form errors, display error messages.
  If form is filled out correctly, allow user to log in.
*/
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$verify = $administrator->verify_login($username, $password);

	if(isset($verify) && $verify !== false){
		//Protect against session fixation
		session_regenerate_id();

		$_SESSION['admin'] = $verify['type'];
		$_SESSION['user'] = $verify['username'];
		$_SESSION['last_login'] = time();

		$error_warning = "<br />";

		//Redirect user to main page
		header("Location: index.php");
		exit;

	} else{
		$error_warning = "Invalid username or password.";
	}

} else{
	$error_warning = "<br />";
}

?>
<div class="main">
  <h3>Please enter your username and password.</h3>
  <div class="red"><?php echo $error_warning;?></div>
  <div class="bottom">
	<div class="center">
	  <form method="POST">
		<table>
		  <tr>
			<td class="right">Username:  </td>
			<td class="left"><input type="text" name="username" value="<?php echo htmlentities($username); ?>"></input>
		  </tr>
		  <tr>
			<td class="right">Password:  </td>
			<td class="left"><input type="password" name="password"></input>
		  </tr>
		</table>
		</div>
		<br /><br />
		<div>
		  <input type="submit" name="submit" value="Submit" class="button"></input>
		  <a class="buttonLink block" href="index.php">Cancel</a>
	</div>
	</div>
  </form>
</div>

<?php
include_once("../private/layout/footer.php");
?>

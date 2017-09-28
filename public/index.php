<?php
require_once('../private/initialize.php');
include_once("../private/layout/header.php");
?>


<?php
if(isset($_GET["type"]) && in_array($_GET["type"], $recipe_types)){
	?>
  <div class="recipes">
	<?php
	$type = htmlentities(urlencode($_GET["type"]), ENT_QUOTES);
	echo view_recipes($type);
	?>
  </div>
<?php	
} else { ?>
<div class="main">
  <h1>WELCOME TO RECIPE BLOG!</h1>
  <div class="types">
	<a href="index.php?type=Meal" class="choice meal">Meals</a></button>
	<a href="index.php?type=Snack" class="choice snack">Snacks</a></button>
	<a href="index.php?type=Dessert" class="choice dessert">Desserts</a></button>
  </div>
<?php
  if(isset($_SESSION['user']) && $_SESSION['user'] != ''){
	  ?>
		<div class="bottom red">You are currently logged in as <?php echo $user; ?>. Log in time = <?php echo date("n/j/Y g:i:s a", $last_login); ?></div>
		</div>	
<?php  ;}
  else{
	  ?>
		  <div class="bottom">Click <u><a href="login.php">here</a></u> to log in as an administrator.</div>
		  </div>	
<?php  ;}
?>
	
		
		
<?php }
?>






<?php
include_once("../private/layout/footer.php");
?>
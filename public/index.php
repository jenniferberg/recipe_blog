<?php
require_once('../private/initialize.php');
include_once("../private/layout/header.php");
?>


<?php
if(isset($_GET["type"]) && in_array($_GET["type"], Recipe::$recipe_types)){
	?>
  <div class="recipes">
	<?php
	$type = htmlentities(urlencode($_GET["type"]), ENT_QUOTES);

	$result = $recipe->view_recipes($type);
	$count = count($result);

	//Output data
	$output = '';
	for($i = 0; $i < $count; $i++){
		$picture = evaluate_name($recipe->recipe_name[$i]);

		$output .= "<div class=\"list\">";
		$output .= "<div class=\"center floatLeft\">";
		if(file_exists("images/".$picture.".jpg")){
			$output .= "<div class=\"picture\">";
			$output .= "<a href=\"view.php?id=";
			$output .= urlencode($result[$i]["id"])."\">";
			$output .= "<img src=\"images/".$picture.".jpg\"";
			$output .= " alt=".$recipe->recipe_name[$i];
			$output .= " height=\"200\" width=\"180\"></a></div>";
		} else {
			$output .="<div class=\"picture border\">No picture</div>";
		}
		$output .= "</div>";
		$output .= "<div class=\"center floatLeft\">";
		$output .= "<div class=\"left colMax\"><a href=\"view.php?id=";
		$output .= urlencode($result[$i]["id"]);
		//$output .= "\">".htmlentities($result[$i]["recipe_name"])."</a>";
		$output .= "\">".htmlentities($recipe->recipe_name[$i])."</a>";
		$output .= "</div><div class=\"left\">";
		$output .= htmlentities($recipe->calories[$i])." calories";
		$output .= "</div><div class=\"left\">";
		$output .= count($recipe_ingredient->get_ingredients_by_id($result[$i]["id"]))." ingredients";
		$output .= "</div><div class=\"left\">";
		$output .= $recipe_instruction->get_total_time($result[$i]["id"])." minutes";
		$output .= "</div>";
		if($admin == 'admin' || $admin == 'superadmin'){
			$output .= "<div class=\"left\">";
			$output .= "<a href=\"edit.php?id=";
			$output .= urlencode($result[$i]["id"]);
			$output .= "\">Edit</a>";
			$output .= "</div>";
		}
		$output .= "</div>";
		$output .= "</div>";
	}

	echo $output;


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

<?php
require_once('../private/initialize.php');
include_once("../private/layout/header.php");

//Ensure id is a valid recipe
if(isset($_GET['id'])){
	if($recipe->select_by_id($_GET['id'])){
		$id = htmlentities(urlencode($_GET["id"]), ENT_QUOTES);
	} else {
		header("Location: index.php");
		exit;
	}
}

$recipe_name = $recipe->select_by_id($id)[0]["recipe_name"];
?>
<hr />
<div class="block">
</div>
<?php
if($admin == 'admin' || $admin == 'superadmin'){?>
  <div class="centerAlign">
	<a class="buttonLink block" href="edit.php?id=<?php echo urlencode($id); ?>">Edit Recipe</a>
	<div class="block right">
	<form method="POST" action="../private/delete.php" >
		<input type="hidden" name="id" value="<?php echo htmlentities($id); ?>" />
		<input type="submit" name="submit" class="button" id="delete" value="Delete Recipe" onclick="return confirm('Are you sure you want to delete this recipe?')"/>
	</form>
	</div>
  </div>
 <?php
}
?>


<div class="recipeHeader">
  <div class="center">
	<?php
	$picture = evaluate_name($recipe_name);
	if(file_exists("images/".$picture.".jpg")){
		echo "<div class=\"picture\">";
		echo "<img src=\"images/".$picture.".jpg\"";
		echo " alt=".$recipe_name;
		echo " height=\"200\" width=\"180\"></div>";
	} else {
		echo "<div class=\"picture border\">No picture</div>";
	}

	?>
  </div>
  <div class="center">
	<h2><?php echo $recipe->select_by_id($id)[0]["recipe_name"];?></h2>
	<div class="left">Total Calories:  <?php echo $recipe->select_by_id($id)[0]["calories"];?></div>
	<div class="left">Number of Ingredients:  <?php echo count($recipe_ingredient->get_ingredients_by_id($id));?></div>
	<div class="left">Total Time:  <?php echo $recipe_instruction->get_total_time($id);?> minutes</div>
  </div>

</div>
<div class="recipeDetail">
 <div class="section">
	<div class="center">
		<?php
		//Get the ingredients for the selected recipe
		$result = $recipe_ingredient->get_ingredients_by_id($id);
		$count = count($result);

		//Output data
		$output  = "<table class=\"center\">";
		for($i=0; $i < $count; $i++){
			$output .= "<tr><td class=\"right colMax\">";
			$output .= htmlentities($recipe_ingredient->ingredient_name[$i]);
			$output .= "</td><td class=\"left colMax\">";
			$output .= htmlentities($recipe_ingredient->amount[$i])." ". htmlentities($recipe_ingredient->unit[$i]);
			$output .= "</td></tr>";
		}

		$output .= "</table>";

		echo $output;
		?>

	</div>
</div>
</div>
<div class="recipeDetail">
<div class="section">
	<div class="center">
		<?php
		//Get instructions for the selected recipe
		$result = $recipe_instruction->get_instructions_by_id($id);
		$count = count($result);

		$output  = "<table class=\"center\">";
		$output .= "<th></th><th>Instruction</th><th>Time (Minutes)</th>";

		for($i=0; $i < $count; $i++){
			$output .= "<tr><td class=\"left\">";
			$output .= htmlentities($recipe_instruction->instruction_number[$i]);
			$output .= "</td><td class=\"left colMin\">";
			$output .= htmlentities($recipe_instruction->instruction[$i]);
			$output .= "</td><td class=\"colMax\">";
			$output .= htmlentities($recipe_instruction->time[$i]);
			$output .= "</td></tr>";

		}
		$output .= "</table>";

		echo $output;
		?>
	</div>
</div>
</div>

<?php
include_once("../private/layout/footer.php");
?>

<?php
require_once('../private/initialize.php');
include_once("../private/layout/header.php");


if(isset($_GET["id"])){
	$id = htmlentities(urlencode($_GET["id"]), ENT_QUOTES);
}
$recipe_name = get_recipe_info($id)["recipe_name"];
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
	<h2><?php echo get_recipe_info($id)["recipe_name"];?></h2>
	<div class="left">Total Calories:  <?php echo get_recipe_info($id)["calories"];?></div>
	<div class="left">Number of Ingredients:  <?php echo count(get_ingredients_by_id($id));?></div>
	<div class="left">Total Time:  <?php echo get_total_time($id);?> minutes</div>
  </div>

</div>
<div class="recipeDetail">
 <div class="section">
	<div class="center"><?php echo view_recipe_ingredients($id); ?></div>
</div>
</div>
<div class="recipeDetail">
<div class="section">
	<div class="center"><?php echo view_recipe_instructions($id); ?></div>
</div>
</div>

<?php
include_once("../private/layout/footer.php");
?>
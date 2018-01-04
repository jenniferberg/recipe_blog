<div class="centerAlign">
<form id="form" method="POST" enctype="multipart/form-data">
<div class="recipeDetail">
<div class="section wide">
<div class="red centerAlign"><?php echo $error_warning; ?></div>
<h3>Recipe Information</h3>
<?php if(isset($validate["recipe_name"])){ echo "<div class=\"red\">*".$validate["recipe_name"]."</div>";}
	  if(isset($name["recipe_name"])){ echo "<div class=\"red\">*".$name["recipe_name"]."</div>";}
	  if(isset($validate["recipe_type"])){ echo "<div class=\"red\">*".$validate["recipe_type"]."</div>";}
	  if(isset($validate["calories"])){ echo "<div class=\"red\">*".$validate["calories"]."</div>";}
	  if(isset($validate["active"])){ echo "<div class=\"red\">*".$validate["active"]."</div>";}
	  if(isset($uploadOk) && $uploadOk == 0){ echo "<div class=\"red\">*".$picture_error."</div>";}
?>
<table class="centerAlign">
  <tr>
    <td class="right">Recipe Name:</td>
	<td class="left"><input <?php if(isset($validate["recipe_name"]) || isset($name["recipe_name"])){ echo "id=\"error\"";} ?> type="text" name="recipe_name" value="<?php echo htmlentities($recipe_name, ENT_QUOTES); ?>"/></td>
  </tr>
  <tr>
    <td class="right">Recipe Type:</td>
	<td class="left"><select <?php if(isset($validate["recipe_type"])){ echo "id=\"error\"";} ?> name="recipe_type">
		  <?php
		  foreach(Recipe::$recipe_types as $type){
			  $option  = "<option value=";
			  $option .= "{$type}";
			  if(isset($recipe_type) && $type == $recipe_type)
				{$option .= " selected";}
			  $option .= ">{$type}</option>";

			  echo $option;
		  }
		  ?>
		</select>
	</td>
  </tr>
  <tr>
    <td class="right">Total Calories:</td>
	<td class="left"><input <?php if(isset($validate["calories"])){ echo "id=\"error\"";} ?>  type="number" name="calories" value="<?php echo htmlentities($calories); ?>"/></td>
  </tr>
  <tr>
    <td class="right">Visible to Viewer:</td>
	<td class="left"><select <?php if(isset($validate["active"])){ echo "id=\"error\"";} ?> name="active">
		  <option value="0" <?php echo $active == 0 ? ' selected' : '';?>>No</option>
		  <option value="1" <?php echo $active == 1 ? ' selected' : '';?>>Yes</option>
		</select>
	</td>
  </tr>
  <tr>
	<td class="right">Recipe Image:</td>
	<td class="left"><input type="file" name="recipeImage"></td>
  </tr>
</table>
</div>
</div>
<div class="recipeDetail">
<div class="section wide">
<h3>Ingredients</h3>
<?php
for ($m = 1; $m < $end_h; $m++){
	if(
		isset($req["ingredient_name{$m}"]) ||
		isset($req["amount{$m}"]) ||
		isset($req["unit{$m}"])
	) {echo "<div class=\"red\">*All fields are required</div>"; break;}
}

for ($m = 1; $m < $end_h; $m++){
	if(isset($max["ingredient_name{$m}"])
	) {echo "<div class=\"red\">*".$max["ingredient_name{$m}"]."</div>"; break;}
}

for ($m = 1; $m < $end_h; $m++){
	if(isset($max["amount{$m}"])
	) {echo "<div class=\"red\">*".$max["amount{$m}"]."</div>"; break;}
}

for ($m = 1; $m < $end_h; $m++){
	if(isset($max["unit{$m}"])
	) {echo "<div class=\"red\">*".$max["unit{$m}"]."</div>"; break;}
}


?>
<table class="centerAlign">
<thead>
  <tr>
	<th class="pad">Ingredient Name</th>
	<th class="pad">Amount</th>
	<th class="pad">Unit of Measure</th>
  </tr>
</thead>
<tbody id="ingredients">
  <?php
	for ($m = 1; $m < $end_h; $m++){
	  //Create HTML inputs
	  //Ingredient name
	  echo "<tr><td><input ";
	  if(isset($validate["ingredient_name{$m}"])){ echo "id=\"error\"";}
	  echo "type=\"text\" name=\"ingredient_name{$m}\" value=\"{$ingredient_name[$m]}\"/></td>";

	  //Amount
	  echo "<td><input class=\"smallInput\"";
	  if(isset($validate["amount{$m}"])){ echo "id=\"error\"";}
	  echo "type=\"text\" name=\"amount{$m}\" value=\"{$amount[$m]}\" />";

	  //Unit of Measure
	  echo "<td><select ";
	  if(isset($validate["unit{$m}"])){ echo "id=\"error\"";}
	  echo "name=\"unit{$m}\">";
	  foreach(RecipeIngredient::$units_of_measure as $uom){
		  $option  = "<option value=";
		  $option .= "{$uom}";
		  if(isset($unit[$m]) && $uom == $unit[$m])
			{$option .= " selected";}
		  $option .= ">{$uom}</option>";
		  echo $option;
		}
	  echo	 "</select>";
	  echo "</td></tr>";
  }
  ?>
</tbody>
</table>
<button type="button" id="addIngredient" class="button">Add New Line</button>
<button type="button" id="removeIngredient" class="button" <?php if($h <= 1){ echo " hidden";} ?>>Remove Last Line</button>
</div>
</div>
<div class="recipeDetail">
<div class="section wide">
<h3>Instructions</h3>
<?php
//If there are errors in the instructions, display them
for ($j = 1; $j < $end_i; $j++){
	if(
		isset($req["time{$j}"]) ||
		isset($req["instruction{$j}"])
	) {echo "<div class=\"red\">*All fields are required</div>"; break;}
}

for ($j = 1; $j < $end_i; $j++){
	if(isset($max["time{$j}"])
	) {echo "<div class=\"red\">*".$max["time{$j}"]."</div>"; break;}
}

for ($j = 1; $j < $end_i; $j++){
	if(isset($min["time{$j}"])
	) {echo "<div class=\"red\">*".$min["time{$j}"]."</div>"; break;}
}

?>
<table class="centerAlign">
  <thead>
  <tr>
    <th class="pad">Number</th>
    <th class="pad">Time (Minutes)</th>
    <th class="pad">Instruction</th>
  </tr>
  </thead>
  <tbody id="instructions">
  <?php

  for ($j = 1; $j < $end_i; $j++){
	  //Create HTML inputs
	  //Instruction Number
	  echo "<tr><td class=\"centerText\">{$j}</td>";

	  //Time
	  echo "<td><input class=\"smallInput\"";
	  if(isset($validate["time{$j}"])){ echo "id=\"error\"";}
	  echo "type=\"number\" name=\"time{$j}\" value=\"{$time[$j]}\"/></td>";

	  //Instruction
	  echo "<td class=\"ins\"><input class=\"largeInput\"";
	  if(isset($validate["instruction{$j}"])){ echo "id=\"error\"";}
	  echo "type=\"text\" name=\"instruction{$j}\" value=\"{$instruction[$j]}\" />";
	  echo "</td></tr>";
  }
  ?>
</tbody>
</table>
<button type="button" id="addInstruction" class="button">Add New Line</button>
<button type="button" id="removeInstruction" class="button" <?php if($i <= 1){ echo " hidden";} ?>>Remove Last Line</button>

</div>
</div>
<br />
<div>
<input type="submit" name="submit" class="button" value="<?php echo $choice; ?> Recipe" />
</form>
<a href="index.php" class="buttonLink">Cancel</a>
</div>
</div>
<?php
include_once("../private/layout/footer.php");
?>

<script>
var h = <?php echo $h; ?>;
var i = <?php echo $i; ?>;

console.log("h = "+h);
console.log("i = "+i);


var addInstruction = document.getElementById("addInstruction");
var removeInstruction = document.getElementById("removeInstruction");

var addIngredient = document.getElementById("addIngredient");
var removeIngredient = document.getElementById("removeIngredient");

var ingredients = document.getElementById("ingredients");
var instructions = document.getElementById("instructions");

addInstruction.addEventListener("click", function(){
	add("instructions",instructions);
});

addIngredient.addEventListener("click", function(){
	add("ingredients",ingredients);
})

removeInstruction.addEventListener("click", function(){
	remove("instructions",instructions);
});

removeIngredient.addEventListener("click", function(){
	remove("ingredients",ingredients);
})

function add(type, section){
	if(type == "instructions"){ i++;}
	else{ h++;}

	var xhr = new XMLHttpRequest();
	xhr.open("GET","../private/addLines.php?h="+h+"&i="+i, true);

	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 && xhr.status == 200){

			var newLine = document.createElement("TR");
			var newCol = [];

			for(j=1; j<4; j++){
				newCol[j] = document.createElement("TD");
			}


			var time = document.createElement("input");
				time.setAttribute("class","smallInput");
				time.setAttribute("type","number");
				time.setAttribute("name","time"+i)
			var inst = document.createElement("input");
				inst.setAttribute("type","text");
				inst.setAttribute("class","largeInput");
				inst.setAttribute("name","instruction"+i)
			var ingredientName = document.createElement("input");
				ingredientName.setAttribute("type","text");
				ingredientName.setAttribute("name","ingredient_name"+h);
			var amount = document.createElement("input");
				amount.setAttribute("class","smallInput");
				amount.setAttribute("type","text");
				amount.setAttribute("name","amount"+h);
			var unit = document.createElement("select");
				unit.setAttribute("name","unit"+h);

			var units_of_measure = [
				<?php foreach(RecipeIngredient::$units_of_measure as $unit){
					echo "'{$unit}',";
				} ?> ]

			length = units_of_measure.length;

			for(x = 0; x < length; x++){
				var option = document.createElement("option");
					option.setAttribute("value",units_of_measure[x]);
				var optionName = document.createTextNode(units_of_measure[x]);
					option.appendChild(optionName);
					unit.appendChild(option);
			}

			if(type == "instructions"){
				var number = document.createTextNode(i);

				newCol[1].setAttribute("class","centerText");
				newCol[1].appendChild(number);
				//newCol[1].appendChild(count);
				newCol[2].appendChild(time);
				newCol[3].appendChild(inst);

				for(k=1; k<4; k++){
				newLine.appendChild(newCol[k]);
			}
			} else{
				var number = document.createTextNode(h);

				newCol[1].appendChild(ingredientName);
				newCol[2].appendChild(amount);
				newCol[3].appendChild(unit);

				for(k=1; k<4; k++){
					newLine.appendChild(newCol[k]);
				}
			}

			section.appendChild(newLine);

			if(i <= 1){
				removeInstruction.hidden = true;
			} else{
				removeInstruction.hidden = false;
			}

			if(i > 999){
				addInstruction.hidden = true;
			} else{
				addInstruction.hidden = false;
			}

			if(h <= 1){
				removeIngredient.hidden = true;
			} else{
				removeIngredient.hidden = false;
			}

			if(h > 999){
				addIngredient.hidden = true;
			} else{
				addIngredient.hidden = false;
			}
		}
	}
	xhr.send();
}

function remove(type, section){
	if(type == "instructions"){ i--;}
	else{ h--;}

	var xhr = new XMLHttpRequest();
	xhr.open("GET","../private/addLines.php?i="+i+"&h="+h, true);

	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 && xhr.status == 200){

			section.removeChild(section.lastChild);

			if(i <= 1){
				removeInstruction.hidden = true;
			} else{
				removeInstruction.hidden = false;
			}

			if(i > 999){
				addInstruction.hidden = true;
			} else{
				addInstruction.hidden = false;
			}

			if(h <= 1){
				removeIngredient.hidden = true;
			} else{
				removeIngredient.hidden = false;
			}

			if(h > 999){
				addIngredient.hidden = true;
			} else{
				addIngredient.hidden = false;
			}
		}
	}
	xhr.send();
}

</script>

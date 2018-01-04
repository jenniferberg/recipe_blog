<?php
require_once('../private/initialize.php');
include_once("../private/layout/header.php");

if($admin != 'superadmin'){
	header("Location:  index.php");
	exit;
}

?>
<hr />
<div class="centerAlign"><a class="buttonLink" href="newAdmin.php">New Administrator</a></div>
<br />
<div class="section">
<div class="center">
  <?php $result = $administrator->select_all();
	$count = count($result);

	$output = "<table><th class=\"right\">Username</th><th class=\"left\">Access Type</th><th class=\"right\"></th>";
	for($i = 0; $i < $count; $i++){
		$output .= "<tr><td class=\"right\">";
		$output .= htmlentities($result[$i]["username"]);
		$output .= "</td><td class=\"left\">";
		$output .= htmlentities($result[$i]["type"]);
		$output .= "</td><td class=\"right\">";
		$output .= "<a href=\"editAdmin.php?id=".urlencode($result[$i]["id"])."\">Edit</a>";
	}
	$output .= "</table>";

	echo $output;
	?>
</div>
</div>

<?php
include_once("../private/layout/footer.php");
?>

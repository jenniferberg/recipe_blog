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
  <?php echo view_admins(); ?>
</div>
</div>		

<?php
include_once("../private/layout/footer.php");
?>
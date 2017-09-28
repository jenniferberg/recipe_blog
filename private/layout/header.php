<?php
if($admin == 'superadmin'){
	echo "<div class=\"red\">Super Admin {$user} is currently logged in.</div>";
} elseif($admin == 'admin'){
	echo "<div class=\"red\">Admin {$user} is currently logged in.</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Recipe Blog</title>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="../public/stylesheet/styles.css">
</head>
<body>
  <div class="navigation">
    <a href="index.php" class="nav">Home</a>
	<a href="index.php?type=Meal" class="nav">Meals</a>
	<a href="index.php?type=Snack" class="nav">Snacks</a>
	<a href="index.php?type=Dessert" class="nav">Desserts</a>
	<?php if($admin == 'admin' || $admin == 'superadmin'){ ?>
	<a href="add.php" class="nav">New Recipe</a>
	<?php ;} ?>
	<?php if($admin == 'superadmin'){ ?>
	<a href="admins.php" class="nav">View Admins</a>
	<?php ;} ?>
	<?php if($admin == 'admin' || $admin == 'superadmin'){ ?>
	<a href="logout.php" class="nav" onclick="return confirm('Are you sure you want to log out?')">Log Out</a>
	<?php ;} ?>
	
  </div>



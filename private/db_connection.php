<?php
const DBHOST = '';
const DBUSER = '';
const DBPASSWORD = '';
const DBNAME = 'recipe_blog';

//Connect to the recipe_blog database
$db = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBNAME);

//Test connection
if (mysqli_connect_errno()){
	die("Database connection failed: " . mysqli_connect_error() . " (Error number:  " . mysqli_connect_errno() . ")"
	);
}

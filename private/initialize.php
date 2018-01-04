<?php
ob_start(); //turn on output buffering

//Session file
include_once('session.php');

//Class files
require_once('classes/database.php');
require_once('classes/database_queries.php');
require_once('classes/administrator.php');
require_once('classes/recipe.php');
require_once('classes/recipe_ingredient.php');
require_once('classes/recipe_instruction.php');

//Validation functions file
require_once('validation_functions.php');

$admin = isset($_SESSION['admin']) ? $_SESSION['admin'] : '';
$user = isset($_SESSION['user']) ? $_SESSION['user'] : '';
$last_login = isset($_SESSION['last_login']) ? $_SESSION['last_login'] : '';

$directory = "../public/images/";

//Instantiate objects
$administrator = new Administrator();
$recipe = new Recipe();
$recipe_ingredient = new RecipeIngredient();
$recipe_instruction = new RecipeInstruction();

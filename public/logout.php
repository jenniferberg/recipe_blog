<?php
require_once('../private/initialize.php');

//Unset session values;
unset($_SESSION['admin']);
unset($_SESSION['user']);
unset($_SESSION['last_login']);

//Destroy session
session_destroy();

//Redirect user to main page
header("Location: index.php");
exit;

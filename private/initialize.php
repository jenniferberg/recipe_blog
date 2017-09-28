<?php
ob_start(); //turn on output buffering

include_once('session.php');
require_once('db_connection.php');
require_once('functions.php');
require_once('validation_functions.php');

$admin = isset($_SESSION['admin']) ? $_SESSION['admin'] : '';
$user = isset($_SESSION['user']) ? $_SESSION['user'] : '';
$last_login = isset($_SESSION['last_login']) ? $_SESSION['last_login'] : '';

//File directory where recipe images are stored
$directory = '../public/images/';


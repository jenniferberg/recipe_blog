<?php
session_start();

if($_SERVER['REQUEST_METHOD'] == 'GET'){
	$_SESSION["i"] = 0;
	$_SESSION["h"] = 0;
}

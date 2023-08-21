<?php
require_once("auth.php");

function giveMeTheCue($b){
	//łączenie z bazą danych
	global $conn;
	$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_BASE_PREFIX.$b);
	
	//błędy
	if($conn->connect_error) echo "Nie można się połączyć z bazą: ".$conn->connect_error;
	
	//charset, bo świra dostaje
	$conn->set_charset("utf8");
}
require("core/engine.php");
?>
<?php 
/**
 * Gera a página de logout
 */ 
 
 	session_start();
	$_SESSION = array();
	session_destroy();

	header("Location: index.php");
	exit;
	
?>
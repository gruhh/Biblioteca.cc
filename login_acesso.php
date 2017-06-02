<?php 
/**
 * Gera a página de checagem de login
 */ 

session_start();

if(!isset($_SESSION['idinstalacao']) && !isset($_SESSION['usuario']) && $_SESSION['logado']!=1):
	header("Location: login.php");
	exit;
endif;

?>
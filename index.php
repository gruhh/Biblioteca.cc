<?php 
/**
 * Gera o index de acesso
 *
 * Esta página permite a determinação da localização dos usuários perante
 * o nível e a situação de login. Além de checar a instalação do sistema.
 *
 * @todo Níveis, situação de login
 */ 

include_once("instalacao.php"); 

$sconexao = new mysqli(BANCO_DADOS_SERVIDOR, BANCO_DADOS_USUARIO, BANCO_DADOS_SENHA, BANCO_DADOS_NOME);
$sconexao->set_charset("utf8");

if ($sconexao->connect_errno > 0){
	header("Location: instalador.php");
	exit;
}

/**
 * Checar a instalação local
 */
$sql = "SHOW TABLES LIKE 'b_admin'";
if (!$checagem = $sconexao->query($sql)){ 
	die('<pre>Desculpe, mas aconteceu um erro na instalação [' . $sconexao->error . ']</pre>'); 
}

if ($checagem->num_rows == 0){
	header("Location: instalador.php");
	exit;
} else {
	
/**
 * Se logado
 */
if(!isset($_SESSION['idinstalacao']) && !isset($_SESSION['usuario']) && $_SESSION['logado']!=1):
	header("Location: painel.php");
	exit;
else:
/**
 * Se não logado
 */
	header("Location: login.php");
	exit;
endif;	
}
?>
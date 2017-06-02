<?php
include_once("instalacao.php");  

$sconexao = new mysqli(BANCO_DADOS_SERVIDOR, BANCO_DADOS_USUARIO, BANCO_DADOS_SENHA, BANCO_DADOS_NOME);
$sconexao->set_charset("utf8");

if ($sconexao->connect_errno > 0){ 
	die('<pre>Problemas: Não conseguimos realizar a conexão com o banco de dados. [' . $sconexao->connect_error . ']</pre>');
}
?>
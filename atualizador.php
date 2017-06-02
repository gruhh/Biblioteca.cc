<?php 
/**
 * Gera a atualização do sistema
 */ 
 
include_once("instalacao.php"); 

$sconexao = new mysqli(BANCO_DADOS_SERVIDOR, BANCO_DADOS_USUARIO, BANCO_DADOS_SENHA, BANCO_DADOS_NOME);
$sconexao->set_charset("utf8");

if ($sconexao->connect_errno > 0){ 
	die('<pre>Você ainda não configurou a sua conexão com o banco de dados, abra o arquivo<br><strong>instalacao.php</strong> que está na pasta principal do arquivo .zip que você acabou<br>de descompactar, e adicione as informações de conexão com o banco de dados. <br><br>[' . $sconexao->connect_error . ']</pre>');
}


/**
 * Atualizando tabelas do MySQL
 */
$sql = '';

$sql .= 'ALTER TABLE `b_configuracoes`';
$sql .= '	ADD COLUMN `abordagemacervo` tinyint(1) DEFAULT NULL;';

if (!$sconexao->query($sql))
	die('<pre>Desculpe, mas aconteceu um erro na criação dos Bancos de Dados [' . $sconexao->error . ']</pre>'); 

$sql = '';

$sql .= 'ALTER TABLE `b_itens`';
$sql .= '	ADD COLUMN `numexemplar` tinyint(1) DEFAULT NULL;';

if (!$sconexao->query($sql))
	die('<pre>Desculpe, mas aconteceu um erro na criação dos Bancos de Dados [' . $sconexao->error . ']</pre>'); 

$sql = '';

$sql .= 'ALTER TABLE `b_itens`';
$sql .= '	ADD COLUMN `origem` varchar(64) DEFAULT NULL;';

if (!$sconexao->query($sql))
	die('<pre>Desculpe, mas aconteceu um erro na criação dos Bancos de Dados [' . $sconexao->error . ']</pre>'); 

$sql = '';

$sql .= 'ALTER TABLE `b_usuarios`';
$sql .= '	ADD COLUMN `interesse` varchar(256) DEFAULT NULL;';

if (!$sconexao->query($sql))
	die('<pre>Desculpe, mas aconteceu um erro na criação dos Bancos de Dados [' . $sconexao->error . ']</pre>'); 

$sql = '';

$sql .= "UPDATE b_configuracoes SET abordagemacervo=0; ";

if (!$sconexao->query($sql))
	die('<pre>Desculpe, mas aconteceu um erro na criação dos Bancos de Dados [' . $sconexao->error . ']</pre>'); 

$sql = '';

	header("Location: index.php");
	exit;

?>
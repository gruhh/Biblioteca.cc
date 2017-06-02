<?php 
/**
 * Gera a instalação do sistema
 */ 
 
include_once("instalacao.php"); 

$sconexao = new mysqli(BANCO_DADOS_SERVIDOR, BANCO_DADOS_USUARIO, BANCO_DADOS_SENHA, BANCO_DADOS_NOME);
$sconexao->set_charset("utf8");

if ($sconexao->connect_errno > 0){ 
	die('<pre>Você ainda não configurou a sua conexão com o banco de dados, abra o arquivo<br><strong>instalacao.php</strong> que está na pasta principal do arquivo .zip que você acabou<br>de descompactar, e adicione as informações de conexão com o banco de dados. <br><br>[' . $sconexao->connect_error . ']</pre>');
}

if (isset($_POST['instalar']) && (isset($_POST['nomebiblioteca']) && isset($_POST['email']) && isset($_POST['senha']) && isset($_POST['tipoinstalacao']))):

/**
 * Instalando tabelas do MySQL
 */
$sql = '';

$sql .= 'CREATE TABLE IF NOT EXISTS `b_admin` (';
$sql .= '  `id` int(11) NOT NULL AUTO_INCREMENT,';
$sql .= '  `nome` varchar(128) DEFAULT NULL,';
$sql .= '  `email` varchar(128) DEFAULT NULL,';
$sql .= '  `senha` varchar(64) DEFAULT NULL,';
$sql .= '  `idinstalacao` varchar(64) DEFAULT NULL,';
$sql .= '  PRIMARY KEY (`id`)';
$sql .= ') ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ; ';

if (!$sconexao->query($sql))
	die('<pre>Desculpe, mas aconteceu um erro na criação dos Bancos de Dados [' . $sconexao->error . ']</pre>'); 

$sql = '';

$sql .= 'CREATE TABLE IF NOT EXISTS `b_configuracoes` (';
$sql .= '  `id` int(11) NOT NULL AUTO_INCREMENT,';
$sql .= '  `idinstalacao` varchar(64) DEFAULT NULL,';
$sql .= '  `nomebiblioteca` varchar(128) DEFAULT NULL,';
$sql .= '  `emailbiblioteca` varchar(128) DEFAULT NULL,';
$sql .= '  `tipoinstalacao` tinyint(1) DEFAULT NULL,';
$sql .= '  `descricao` varchar(128) DEFAULT NULL,';
$sql .= '  `datainstalacao` date DEFAULT NULL,';
$sql .= '  `emprestimos` tinyint(1) DEFAULT NULL,';
$sql .= '  `emprestimosdias` tinyint(2) DEFAULT NULL,';
$sql .= '  `paginacao` tinyint(2) DEFAULT NULL,';
$sql .= '  `ajudaconfiguracao` tinyint(1) DEFAULT NULL,';
$sql .= '  `abordagemacervo` tinyint(1) DEFAULT NULL,';
$sql .= '  PRIMARY KEY (`id`),';
$sql .= '  KEY `id` (`id`)';
$sql .= ') ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ; ';

if (!$sconexao->query($sql))
	die('<pre>Desculpe, mas aconteceu um erro na criação dos Bancos de Dados [' . $sconexao->error . ']</pre>'); 

$sql = '';
	
$sql .= 'CREATE TABLE IF NOT EXISTS `b_emprestimos` (';
$sql .= '  `id` int(11) NOT NULL AUTO_INCREMENT,';
$sql .= '  `uid` int(11) NOT NULL,';
$sql .= '  `idusuario` int(11) DEFAULT NULL,';
$sql .= '  `iditem` int(11) DEFAULT NULL,';
$sql .= '  `datasaida` datetime DEFAULT NULL,';
$sql .= '  `datadevolucao` date DEFAULT NULL,';
$sql .= '  `dataentrada` datetime DEFAULT NULL,';
$sql .= '  `status` tinyint(1) DEFAULT NULL,';
$sql .= '  `idatendente` int(11) DEFAULT NULL,';
$sql .= '  `idinstalacao` varchar(64) DEFAULT NULL,';
$sql .= '  PRIMARY KEY (`id`),';
$sql .= '  KEY `id` (`id`)';
$sql .= ') ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ; ';

if (!$sconexao->query($sql))
	die('<pre>Desculpe, mas aconteceu um erro na criação dos Bancos de Dados [' . $sconexao->error . ']</pre>'); 

$sql = '';

$sql .= 'CREATE TABLE IF NOT EXISTS `b_itens` (';
$sql .= '  `id` int(11) NOT NULL AUTO_INCREMENT,';
$sql .= '  `uid` int(11) NOT NULL,';
$sql .= '  `tipo` int(11) DEFAULT NULL,';
$sql .= '  `titulo` varchar(256) DEFAULT NULL,';
$sql .= '  `autor` varchar(256) DEFAULT NULL,';
$sql .= '  `editora` varchar(64) DEFAULT NULL,';
$sql .= '  `edicao` varchar(32) DEFAULT NULL,';
$sql .= '  `ano` varchar(4) DEFAULT NULL,';
$sql .= '  `assuntos` varchar(256) DEFAULT NULL,';
$sql .= '  `datacad` date DEFAULT NULL,';
$sql .= '  `qntd` tinyint(2) DEFAULT NULL,';
$sql .= '  `localizacao` varchar(64) DEFAULT NULL,';
$sql .= '  `statusitem` tinyint(1) DEFAULT NULL,';
$sql .= '  `bloqueio` tinyint(1) DEFAULT NULL,';
$sql .= '  `isbn` varchar(13) DEFAULT NULL,';
$sql .= '  `idatendente` int(11) DEFAULT NULL,';
$sql .= '  `idinstalacao` varchar(64) DEFAULT NULL,';
$sql .= '  `numexemplar` tinyint(1) DEFAULT NULL,';
$sql .= '  `origem` varchar(64) DEFAULT NULL,';
$sql .= '  PRIMARY KEY (`id`),';
$sql .= '  KEY `id` (`id`)';
$sql .= ') ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ; ';

if (!$sconexao->query($sql))
	die('<pre>Desculpe, mas aconteceu um erro na criação dos Bancos de Dados [' . $sconexao->error . ']</pre>'); 

$sql = '';

$sql .= 'CREATE TABLE IF NOT EXISTS `b_itens_classificacao` (';
$sql .= '  `id` int(11) NOT NULL AUTO_INCREMENT,';
$sql .= '  `uid` int(11) NOT NULL,';
$sql .= '  `idinstalacao` varchar(64) DEFAULT NULL,';
$sql .= '  `classificacao` varchar(64) DEFAULT NULL,';
$sql .= '  PRIMARY KEY (`id`)';
$sql .= ') ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ; ';

if (!$sconexao->query($sql))
	die('<pre>Desculpe, mas aconteceu um erro na criação dos Bancos de Dados [' . $sconexao->error . ']</pre>'); 

$sql = '';

$sql .= 'CREATE TABLE IF NOT EXISTS `b_usuarios` (';
$sql .= '  `id` int(11) NOT NULL AUTO_INCREMENT,';
$sql .= '  `uid` int(11) NOT NULL,';
$sql .= '  `nome` varchar(128) DEFAULT NULL,';
$sql .= '  `referencia` varchar(128) DEFAULT NULL,';
$sql .= '  `telefone1` varchar(64) DEFAULT NULL,';
$sql .= '  `telefone2` varchar(64) DEFAULT NULL,';
$sql .= '  `email` varchar(128) DEFAULT NULL,';
$sql .= '  `endereco` varchar(64) DEFAULT NULL,';
$sql .= '  `bairro` varchar(32) DEFAULT NULL,';
$sql .= '  `cidade` varchar(32) DEFAULT NULL,';
$sql .= '  `datacad` date DEFAULT NULL,';
$sql .= '  `status` tinyint(1) DEFAULT NULL,';
$sql .= '  `idatendente` int(11) DEFAULT NULL,';
$sql .= '  `idinstalacao` varchar(64) DEFAULT NULL,';
$sql .= '  `interesse` varchar(256) DEFAULT NULL,';
$sql .= '  PRIMARY KEY (`id`),';
$sql .= '  KEY `id` (`id`)';
$sql .= ') ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ; ';

if (!$sconexao->query($sql))
	die('<pre>Desculpe, mas aconteceu um erro na criação dos Bancos de Dados [' . $sconexao->error . ']</pre>'); 

$sql = '';

$sql .= 'ALTER TABLE b_admin CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci; ';

if (!$sconexao->query($sql))
	die('<pre>Desculpe, mas aconteceu um erro na criação dos Bancos de Dados [' . $sconexao->error . ']</pre>'); 

$sql = '';

$sql .= 'ALTER TABLE b_configuracoes CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci; ';

if (!$sconexao->query($sql))
	die('<pre>Desculpe, mas aconteceu um erro na criação dos Bancos de Dados [' . $sconexao->error . ']</pre>'); 

$sql = '';

$sql .= 'ALTER TABLE b_emprestimos CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci; ';

if (!$sconexao->query($sql))
	die('<pre>Desculpe, mas aconteceu um erro na criação dos Bancos de Dados [' . $sconexao->error . ']</pre>'); 

$sql = '';

$sql .= 'ALTER TABLE b_itens CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci; ';

if (!$sconexao->query($sql))
	die('<pre>Desculpe, mas aconteceu um erro na criação dos Bancos de Dados [' . $sconexao->error . ']</pre>'); 

$sql = '';

$sql .= 'ALTER TABLE b_itens_classificacao CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci; ';

if (!$sconexao->query($sql))
	die('<pre>Desculpe, mas aconteceu um erro na criação dos Bancos de Dados [' . $sconexao->error . ']</pre>'); 

$sql = '';

$sql .= 'ALTER TABLE b_usuarios CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci; ';

if (!$sconexao->query($sql))
	die('<pre>Desculpe, mas aconteceu um erro na criação dos Bancos de Dados [' . $sconexao->error . ']</pre>'); 

$sql = '';

	$input1 = array("Amor", "Felicidade", "Compaixao", "Alegria", "Compartilhe");
	$rand_keys = array_rand($input1, 2);
	$idinstalacao = $input1[$rand_keys[0]] . "_p_Mundo_" . date('ymdgis');

	$nomebiblioteca		= $sconexao->escape_string($_POST['nomebiblioteca']);
	$email				= $sconexao->escape_string($_POST['email']);
	$senha				= md5(trim($_POST['senha']) . '_mundo');
	$tipoinstalacao		= $_POST['tipoinstalacao'];
	$datainstalacao 	= date('Y-m-d');

$sql .= "INSERT INTO b_admin (email, senha, idinstalacao) VALUES ('$email', '$senha', '$idinstalacao'); ";

if (!$sconexao->query($sql))
	die('<pre>Desculpe, mas aconteceu um erro na criação dos Bancos de Dados [' . $sconexao->error . ']</pre>'); 

$sql = '';

$sql .= "INSERT INTO b_itens_classificacao (uid, idinstalacao, classificacao) VALUES (1, '$idinstalacao', 'Livro'), (2, '$idinstalacao', 'Revista'); ";

if (!$sconexao->query($sql))
	die('<pre>Desculpe, mas aconteceu um erro na criação dos Bancos de Dados [' . $sconexao->error . ']</pre>'); 

$sql = '';

$sql .= "INSERT INTO b_configuracoes (idinstalacao, nomebiblioteca, tipoinstalacao, datainstalacao, emprestimos, emprestimosdias, paginacao, ajudaconfiguracao) VALUES ('$idinstalacao', '$nomebiblioteca', '$tipoinstalacao', '$datainstalacao', 1, 7, 10, 1); ";
	
if (!$sconexao->query($sql))
	die('<pre>Desculpe, mas aconteceu um erro na criação dos Bancos de Dados [' . $sconexao->error . ']</pre>'); 

$sql = '';

	header("Location: index.php");
	exit;

endif;

/**
 * Checar a instalação local
 */
$sql = "SHOW TABLES LIKE 'b_admin'";
if (!$checagem = $sconexao->query($sql)){ 
	die('<pre>Desculpe, mas aconteceu um erro [' . $sconexao->error . ']</pre>'); 
}

if ($checagem->num_rows != 0){
	header("Location: index.php");
	exit;
} 
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Biblioteca.cc</title>
<!-- Desenvolvido por Elementus.co -->
<meta name="description" content="">
<!-- Bootstrap 3.0 -->
<link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="dsg/tema-padrao.css" rel="stylesheet">
</head>
<body>
<div class="container painel">
<div class="row">
<div class="col-sm-8 painel-entrada">
  <h1>Biblioteca.cc</h1>
  <p>As configurações do banco de dados estão<strong> funcionando corretamente</strong>! </p>
  <p>Agora vamos realizar a instalação, preencha todos os dados abaixo e clique no botão [Instalar].</p>
  <p>&nbsp;</p>
<form class="form-horizontal" role="form" method="post">
	
    <div class="form-group">
	<label for="nomebiblioteca" class="col-md-3 control-label">Nome da Biblioteca:</label>
	<div class="col-md-6">
	  <input type="text" class="form-control" id="nomebiblioteca" name="nomebiblioteca"  placeholder="Ex.: Biblioteca da Escola X">
	</div>
  </div>

  <div class="form-group">
	<label for="email" class="col-md-3 control-label">E-mail de acesso:</label>
	<div class="col-md-5">
	  <input type="text" class="form-control" id="email" name="email" >
	</div>
  </div>

  <div class="form-group">
	<label for="senha" class="col-md-3 control-label">Senha de acesso:</label>
	<div class="col-md-3">
	  <input type="text" class="form-control" id="senha" name="senha">
	</div>
  </div>
  
  <div class="form-group">
	<label for="tipoinstalacao" class="col-md-3 control-label">Tipo da instalação:</label>
    <div class="col-md-6">
<div class="radio">
  <label>
	<input type="radio" name="tipoinstalacao" id="tipoinstalacao2" value="20">
	Instalação web, em site próprio
  </label>
</div>
<div class="radio">
  <label>
	<input type="radio" name="tipoinstalacao" id="tipoinstalacao2" value="40" checked>
	Instalação local, com ou sem internet
  </label>
</div>
	</div>
  </div>
  
  <div class="form-group">
	<div class="col-md-offset-3 col-md-5">
	  <button type="submit" name="instalar" class="btn btn-primary">Instalar</button>
	</div>
  </div>
</form>
<p>&nbsp;</p>
</div>
</div>
</div>
</body>
</html>
<?php 
/**
 * Gera a página de login de acesso
 */ 
 
include_once("instalacao.php"); 
include_once("inc/conexao.php"); 

if (isset($_POST['acessar']) && (!empty($_POST['email']) && !empty($_POST['senha']))):

$erro	= 0;
$emailg	= $sconexao->escape_string($_POST['email']);
$senhag	= md5(trim($_POST['senha']) . '_mundo');

$checar = $sconexao->prepare("SELECT id, senha, idinstalacao FROM b_admin WHERE email=?");
$checar->bind_param('s', $emailg);
$checar->execute();
$checar->store_result();
$checar->bind_result($id, $senha, $idinstalacao);
$checar->fetch();

if ($checar->num_rows == 0) {
	header('Location: login.php?erro=1');
	die();
} else {
	
	if ($senha != $senhag) {
		header('Location: login.php?erro=1');
		die();
	} else {
		session_start();
		$_SESSION['usuario'] = $id;
		$_SESSION['logado'] = 1;
		$_SESSION['idinstalacao'] = $idinstalacao;
		header('Location: index.php');
		exit;
	}
}

endif;
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
<div class="col-sm-5">
  <p>&nbsp;</p>
  <?php if($_GET['erro'] == 1): ?>
  <div class="alert alert-danger">Sua senha e e-mail não conferem.</div>
  <?php endif; ?>
<div class="painel-entrada">
<p>&nbsp;</p>
<form class="form-horizontal painel-entrada" role="form" method="post">

  <div class="form-group">
	<label for="email" class="col-md-5 control-label">E-mail de acesso:</label>
	<div class="col-md-6">
	  <input type="text" class="form-control" id="email" name="email" >
	</div>
  </div>

  <div class="form-group">
	<label for="senha" class="col-md-5 control-label">Senha de acesso:</label>
	<div class="col-md-6">
	  <input type="password" class="form-control" id="senha" name="senha">
	</div>
  </div>

  <div class="form-group">
	<div class="col-md-offset-5 col-md-5">
	  <button type="submit" name="acessar" class="btn btn-primary">Acessar</button>
	</div>
  </div>
</form>
<p>&nbsp;</p>
</div>
</div>
</div>
</div>
</body>
</html>
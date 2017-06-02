<?php
/**
 * Gera a página "Meus Dados"
 *
 * Esta página permite a alteração de configurações do acesso
 * ao sistema.
 */ 
include("login_acesso.php");
include_once("instalacao.php");
include_once("inc/conexao.php");
include_once("inc/funcoes.php");

$idatendente	= $_SESSION['usuario'];
$idinstalacao	= $_SESSION['idinstalacao'];

if (isset($_POST['salvar'])):
	$erro		= 0;
	$nome		= $sconexao->escape_string($_POST['nome']);
	$email		= $sconexao->escape_string($_POST['email']);
	if (!empty($_POST['senha']))
	$senha		= md5(trim($_POST['senha']) . '_mundo');
	else
	$senha		= '';
	
	if (!empty($nome)) {
		$checar = $sconexao->prepare("SELECT id FROM b_admin WHERE idinstalacao=?");
		$checar->bind_param('s', $idinstalacao);
		$checar->execute();
		$checar->store_result();
		$checar->bind_result($id);
		$checar->fetch();
		
		if ($checar->num_rows != 0) {
			if (!empty($senha)):
				$alterardados = $sconexao->prepare("UPDATE b_admin SET nome=?, email=?, senha=? WHERE idinstalacao=?");
				$alterardados->bind_param('ssss', $nome, $email, $senha, $idinstalacao);
			else:
				$alterardados = $sconexao->prepare("UPDATE b_admin SET nome=?, email=? WHERE idinstalacao=?");
				$alterardados->bind_param('sss', $nome, $email, $idinstalacao);
			endif;
			
			if (!$alterardados->execute())
				$erro = 1;
			$configuracoessave = 1;
		}
	}
endif;

$checardados = $sconexao->prepare("SELECT idinstalacao, nome, email, senha FROM b_admin WHERE idinstalacao=?");
$checardados->bind_param('s', $idinstalacao);
$checardados->execute();
$checardados->store_result();
$checardados->bind_result($idinstalacao, $nome, $email, $senha);
$checardados->fetch();

if ($checardados->num_rows == 0) {
	$erro=2;
}

include_once("inc/cabecalho.php"); 
include_once("inc/menu-cabecalho.php"); ?>
<div class="container painel">
<div class="row">
<?php include_once("inc/menu-painel.php"); ?>
<div class="col-sm-9">
<div class="page-header">
<h1>Configurações Acesso</h1>
</div>
<div class="col-md-12">
<?php if($erro == 2): ?>
  <div class="row">
  <div class="col-md-offset-1 col-md-11">
	<h2>Parece que temos um problema</h2>
  <p>A instalação está corrompida, procure a documentação on-line para saber como resolver este problema.</p>
  <p class="divider"></p>
  </div>
  </div>
  <?php else: ?>
<?php if($erro == 1): ?>
<div class="alert alert-danger">
Não conseguimos salvar as alterações.
</div>
<?php else:?>
<?php if($configuracoessave == 1): ?>
<div class="alert alert-success">
Configurações salvas.
</div>
<?php endif; ?>
<?php endif;?>
<form class="form-horizontal" role="form" method="post">


  <div class="form-group">
	<label for="idinstalacaook" class="col-md-3 control-label">ID da Instalação:</label>
	<div class="col-md-5">
	  <input type="text" class="form-control" id="idinstalacaook" name="idinstalacaook" value="<?php echo $idinstalacao; ?>" disabled="disabled">
	</div>
  </div>
  
  <div class="form-group">
	<label for="nome" class="col-md-3 control-label">Nome do responsável:</label>
	<div class="col-md-5">
	  <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $nome; ?>">
	</div>
  </div>

  <div class="form-group">
	<label for="email" class="col-md-3 control-label">E-mail de acesso:</label>
	<div class="col-md-5">
	  <input type="text" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
	</div>
  </div>

  <div class="form-group">
	<label for="senha" class="col-md-3 control-label">Senha de acesso:</label>
	<div class="col-md-3">
	  <input type="password" class="form-control" id="senha" name="senha" placeholder="*****">
	</div>
  </div>

  <div class="form-group">
	<div class="col-md-offset-3 col-md-5">
	  <button type="submit" name="salvar" class="btn btn-success">Salvar alterações</button>
	</div>
  </div>
</form>

</div>
<?php endif; ?>
</div>
</div>
</div>
<?php include_once("inc/rodape.php"); ?>
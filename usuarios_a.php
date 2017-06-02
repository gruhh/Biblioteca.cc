<?php
/**
 * Gera a página do formulário dos "Usuários"
 *
 * Esta página permite a alteração de dados dos usuários através de formulário
 */ 
include("login_acesso.php");
include_once("instalacao.php");
include_once("inc/conexao.php");
include_once("inc/funcoes.php");

$alterarget		= $_GET['alterar'];
$apagarget		= $_GET['apagar'];
$idatendente	= $_SESSION['usuario'];
$idinstalacao	= $_SESSION['idinstalacao'];

if (isset($_POST['adicionar']) && isset($_POST['nome'])):
	$erro		= 0;
	$nome		= $sconexao->escape_string($_POST['nome']);
	$referencia	= $sconexao->escape_string($_POST['referencia']);
	$telefone1	= $sconexao->escape_string($_POST['telefone1']);
	$telefone2	= $sconexao->escape_string($_POST['telefone2']);
	$email		= $sconexao->escape_string($_POST['email']);
	$endereco	= $sconexao->escape_string($_POST['endereco']);
	$bairro		= $sconexao->escape_string($_POST['bairro']);
	$cidade		= $sconexao->escape_string($_POST['cidade']);
	$interesse	= $sconexao->escape_string($_POST['interesse']);
	$status		= 0;
	$datacad	= date("Y-m-d");
	$excecao	= $_POST['excecao'];
	$uid		= uID('usuarios');
	
	if (!empty($nome)) {
		$checar = $sconexao->prepare("SELECT uid FROM b_usuarios WHERE nome=? AND idinstalacao=?");
		$checar->bind_param('ss', $nome, $idinstalacao);
		$checar->execute();
		$checar->store_result();
		$checar->bind_result($id);
		$checar->fetch();
		
		if ($checar->num_rows == 0 || isset($excecao)) {
			$adicionar = $sconexao->prepare("INSERT INTO b_usuarios (uid, nome, referencia, telefone1, telefone2, email, endereco, bairro, cidade, status, datacad, idatendente, idinstalacao, interesse) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
			$adicionar->bind_param('issssssssisiss', $uid, $nome, $referencia, $telefone1, $telefone2, $email, $endereco, $bairro, $cidade, $status, $datacad, $idatendente, $idinstalacao, $interesse);
			
			if (!$adicionar->execute())
				$erro = 1;
			$usuarioadd = 1;
		
		} else {
			$erro 		= 2;
			$nomeget		= stripslashes($nome);
			$referenciaget	= stripslashes($referencia);
			$telefone1get	= stripslashes($telefone1);
			$telefone2get	= stripslashes($telefone2);
			$emailget		= stripslashes($email);
			$enderecoget	= stripslashes($endereco);
			$bairroget		= stripslashes($bairro);
			$cidadeget		= stripslashes($cidade);
			$interesseget	= stripslashes($interesse);
		}
	} else {
		$erro = 4;
	}
endif;

if (isset($_POST['alterar']) && isset($_POST['nome']) && isset($_POST['alterarid'])):
	$erro		= 0;
	$uid		= $_POST['alterarid'];
	$nome		= $sconexao->escape_string($_POST['nome']);
	$referencia	= $sconexao->escape_string($_POST['referencia']);
	$telefone1	= $sconexao->escape_string($_POST['telefone1']);
	$telefone2	= $sconexao->escape_string($_POST['telefone2']);
	$email		= $sconexao->escape_string($_POST['email']);
	$endereco	= $sconexao->escape_string($_POST['endereco']);
	$bairro 	= $sconexao->escape_string($_POST['bairro']);
	$cidade		= $sconexao->escape_string($_POST['cidade']);
	$interesse	= $sconexao->escape_string($_POST['interesse']);
	$status		= 0;
	
	if (!empty($uid)) {
		$checar = $sconexao->prepare("SELECT uid FROM b_usuarios WHERE uid=? AND idinstalacao=?");
		$checar->bind_param('is', $uid, $idinstalacao);
		$checar->execute();
		$checar->store_result();
		$checar->bind_result($id);
		$checar->fetch();
		
		if ($checar->num_rows != 0) {
			$alterar = $sconexao->prepare("UPDATE b_usuarios SET nome=?, referencia=?, telefone1=?, telefone2=?, email=?, endereco=?, bairro=?, cidade=?, status=?, datacad=?, idatendente=?, interesse=? WHERE uid=?");
			$alterar->bind_param('ssssssssisisi', $nome, $referencia, $telefone1, $telefone2, $email, $endereco, $bairro, $cidade, $status, $datacad, $idatendente, $interesse, $uid);
		
			if (!$alterar->execute())
				$erro = 1;
			$usuarioedit = 1;
		}
	}
endif;

if (isset($_POST['apagar'])):
	$erro = 0;
	$uid = $_POST['apagarid'];
	
	if (!empty($uid)) {
		$deletar = $sconexao->query("DELETE FROM b_usuarios WHERE uid=$uid and idinstalacao='$idinstalacao'");
		$deletar = $sconexao->query("DELETE FROM b_emprestimos WHERE idusuario=$uid and idinstalacao='$idinstalacao'");
		$usuariodelet = 1;
	} else {
		$erro = 1;
	}
endif;

if (isset($apagarget)):
	$id	= $apagarget;
	$campooculto = '<input name="apagarid" type="hidden" id="apagarid" value="' . $id . '">';
	
	$seleciona = $sconexao->query("SELECT uid, nome, referencia FROM b_usuarios WHERE uid=$id AND idinstalacao='$idinstalacao'");
	$row = $seleciona->fetch_assoc();
	
	if (!empty($row)) {
		$idget = $row['uid'];
		$nomeget = stripslashes($row['nome']);
		$referenciaget = stripslashes($row['referencia']);
	} else {
		$erro = 3;
	}
endif;

if (isset($alterarget)):
	$id = $alterarget;
	$campooculto = '<input name="alterarid" type="hidden" id="alterarid" value="' . $id . '">';
	
	$seleciona = $sconexao->query("SELECT nome, referencia, telefone1, telefone2, email, endereco, bairro, cidade, interesse FROM b_usuarios WHERE uid=$id AND idinstalacao='$idinstalacao'");
	$row = $seleciona->fetch_assoc();
	
	if (!empty($row)) {
		$tituloget		= stripslashes($row['titulo']);
		$nomeget		= stripslashes($row['nome']);
		$referenciaget	= $row['referencia'];
		$telefone1get	= $row['telefone1'];
		$telefone2get	= $row['telefone2'];
		$emailget		= $row['email'];
		$enderecoget	= $row['endereco'];
		$bairroget		= $row['bairro'];
		$cidadeget		= $row['cidade'];
		$interesseget	= $row['interesse'];
	}
endif;

include_once("inc/cabecalho.php");
include_once("inc/menu-cabecalho.php"); ?>
<div class="container painel">
<div class="row">
<?php include_once("inc/menu-painel.php"); ?>
<div class="col-sm-9">
<div class="page-header">
<h1>Usuários da Biblioteca</h1>
</div>
<div class="col-md-12">
<?php if (infoConfiguracao('ajuda')=='1'): ?>
<div class="panel panel-default">
  <div class="panel-body">
	<h4>Ajuda</h4>
	<p>Este é o formulário padrão de manutenção dos usuários, ou seja, é o mesmo formulário que você estará utilizando para adicionar, alterar ou confirmar a exclusão de um usuário cadastrado.</p>
	<p>Leia as <a href="http://localhost:8888/bibliotecas/dev_app/pagina_dicasdeuso.php">Dicas de Uso</a>, para conhecer algumas recomendações. Os campos são literais quanto à informação que deverá ser cadastrada em cada campo. De todos os campos, apenas o nome é obrigatório, porém recomendamos que a referência e pelo menos um contato seja adicionado, para facilitar a manutenção de empréstimos que não sejam devolvidos.</p>
<p><a href="configuracoes.php?ajuda=0" class="btn btn-default btn-xs">Quero ocultar toda a ajuda</a></p>
  </div>
</div>
<?php endif; ?>
<?php if ($erro == 1): ?>
<div class="alert alert-danger">
  <strong>Erro:</strong> O usuário não foi adicionado.
</div>
<?php elseif ($erro == 2): ?>
<div class="alert alert-danger">
  <strong>Atenção: </strong> este usuário parece que já existe.<br>
  Para autorizar a adição de outro usuário com o mesmo nome, basta clicar novamente no botão [Adicionar].
</div>
<?php  elseif ($erro == 4): ?>
<div class="alert alert-warning">
  Parece que o nome está em branco.<br />Este é o único campo obrigatório que você precisará preencher em todos os usuários que adicionar.
</div>
<?php else:?>
<?php if ($usuarioadd == 1): ?>
<div class="alert alert-success">
  <strong>Sucesso!</strong> Usuário adicionado.
</div>
<?php endif;?>
<?php if ($usuarioedit == 1): ?>
<div class="alert alert-success">
  Usuário atualizado.
</div>
<?php endif;?>
<?php if ($usuariodelet == 1): ?>
<div class="alert alert-success">
  Usuário apagado.
</div>
<?php endif; ?>
<?php endif; ?>
<form class="form-horizontal" role="form" method="post">
  <?php if (isset($apagarget)): 
  if (!isset($_POST['apagar'])) {?>
  <legend><?php echo 'Apagar um objeto'; ?></legend>
  <div class="row">
  <?php if ($erro==3): ?>
<div class="col-md-offset-1 col-md-11">
<p>Este usuário não existe.</p>
<p><a href="acervo.php" class="btn btn-default btn-sm">Voltar</a></p>
</div><?php else: ?>
  <div class="col-md-offset-1 col-md-11"><h2>#<?php echo $idget ?> <small><?php echo $tipoget ?></small></h2>
  <h4><?php echo $nomeget ?></h4>
  <p><?php echo $referenciaget ?></p>
  <p class="divider"></p>
  </div>
  </div>
  <div class="row">
  <div class="col-md-offset-1 col-md-6"><p>Deseja apagar este usuário? Não há como desfazer esta ação. <?php echo $campooculto;?></p>
  <p><button type="submit" name="apagar" class="btn btn-alerta btn-sm">Apagar usuário</button></div>
  </div>
	<?php endif; ?>
  <?php };
  else: ?>
  <legend><?php
	if (isset($alterarget))
		echo 'Alterar um usuário';
	else
		echo 'Adicionar um usuário';
  ?></legend>

  <div class="form-group">
	<label for="nome" class="col-md-3 control-label">Nome:</label>
	<div class="col-md-6">
	  <input type="text" class="form-control" id="nome" name="nome"<?php if (isset($nomeget)) echo " value='$nomeget'";?>><?php echo $campooculto;?>
	</div>
  </div>
  
  <div class="form-group">
	<label for="referencia" class="col-md-3 control-label">Referência:</label>
	<div class="col-md-5">
	  <input type="text" class="form-control" id="referencia" name="referencia"<?php if (isset($referenciaget)) echo " value='$referenciaget'";?>>
	</div>
  </div>
  
  <div class="form-group">
	<label for="telefone1" class="col-md-3 control-label">Telefone 1:</label>
	<div class="col-md-3">
	  <input type="text" class="form-control" id="telefone1" name="telefone1"<?php if (isset($telefone1get)) echo " value='$telefone1get'";?>>
	</div>
  </div>

  <div class="form-group">
	<label for="telefone2" class="col-md-3 control-label">Telefone 2:</label>
	<div class="col-md-3">
	  <input type="text" class="form-control" id="telefone2" name="telefone2"<?php if (isset($telefone2get)) echo " value='$telefone2get'";?>>
	</div>
  </div>
  
  <div class="form-group">
	<label for="email" class="col-md-3 control-label">E-mail:</label>
	<div class="col-md-6">
	  <input type="text" class="form-control" id="email" name="email" <?php if (isset($emailget)) echo " value='$emailget'";?>>
	</div>
  </div>

  <legend>Endereço</legend>

  <div class="form-group">
	<label for="endereco" class="col-md-3 control-label">Endereço e Nº:</label>
	<div class="col-md-5">
	  <input type="text" class="form-control" id="endereco" name="endereco"<?php if (isset($enderecoget)) echo " value='$enderecoget'";?>>
	</div>
  </div>
  
  <div class="form-group">
	<label for="bairro" class="col-md-3 control-label">Bairro:</label>
	<div class="col-md-5">
	  <input type="text" class="form-control" id="bairro" name="bairro"<?php if (isset($bairroget)) echo " value='$bairroget'";?>>
	</div>
  </div>

  <div class="form-group">
	<label for="cidade" class="col-md-3 control-label">Cidade:</label>
	<div class="col-md-5">
	  <input type="text" class="form-control" id="cidade" name="cidade"<?php if (isset($cidadeget)) echo " value='$cidadeget'";?>>
	</div>
  </div>
  
 <legend>Interesses</legend>

  <div class="form-group">
	<label for="interesse" class="col-md-3 control-label">Interesse de leituras:</label>
	<div class="col-md-5">
	  <textarea name="interesse" rows="3" class="form-control" id="interesse"><?php if (isset($interesseget)) echo $interesseget;?></textarea>
      <span class="help-block">Descreva rapidamente as áreas de interesse do usuário. (Máximo de 256 caracteres.)</span>
	</div>
  </div>  
  
  <div class="form-group">
	<div class="col-md-offset-3 col-md-5">
	  <?php
		if (isset($alterarget))
			echo '<button type="submit" name="alterar" class="btn btn-success">Alterar usuário</button>'; 
		else
			echo '<button type="submit" name="adicionar" class="btn btn-success">Adicionar usuário</button>';
		if (isset($_POST['nome']))
			echo '<input name="excecao" type="hidden" value="s">';
		?>
	</div>
  </div>
  <?php endif; ?>
</form>
</div>
</div>
</div>
</div>
<?php include_once("inc/rodape.php"); ?>
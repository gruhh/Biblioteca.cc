<?php
/**
 * Gera a página do formulário dos "Empréstimos"
 *
 * Esta página permite a alteração de dados dos empréstimos através de formulário
 */ 
include("login_acesso.php");
include_once("instalacao.php");
include_once("inc/conexao.php");
include_once("inc/funcoes.php");

$alterarget		= $_GET['alterar'];
$devolverget	= $_GET['devolver'];
$renovarget		= $_GET['renovar'];
$apagarget		= $_GET['apagar'];
$idatendente	= $_SESSION['usuario'];
$idinstalacao	= $_SESSION['idinstalacao'];
$filtroa		= $_GET['acervo'];
$filtrou		= $_GET['usuario'];

if (isset($_POST['adicionar']) && empty($_POST['usuarioid']) && empty($_POST['acervoid'])):
	$erro			= 1;
	
elseif (isset($_POST['adicionar']) && !empty($_POST['usuarioid']) && !empty($_POST['acervoid'])):
	$erro			= 0;
	$status			= 0;
	$usuarioid		= $sconexao->escape_string($_POST['usuarioid']);
	$usuario		= $sconexao->escape_string($_POST['usuario']);
	$acervoid		= $sconexao->escape_string($_POST['acervoid']);
	$acervo			= $sconexao->escape_string($_POST['acervo']);
	$datasaida		= padraoDatas($_POST['datasaida'],'s',0,1,1);
	$datadevolucao	= padraoDatas($_POST['datadevolucao'],'s',0);
	$excecao		= $_POST['excecao'];
	$uid			= uID('emprestimos');
	
	if (!empty($usuarioid)) {
		$checar = $sconexao->prepare("SELECT uid FROM b_emprestimos WHERE idusuario=? AND iditem=? AND idinstalacao=? AND status=0");
		$checar->bind_param('iis', $usuarioid, $acervoid, $idinstalacao);
		$checar->execute();
		$checar->store_result();
		$checar->bind_result($id);
		$checar->fetch();
		
		if ($checar->num_rows == 0 || isset($excecao)) {
			$adicionar = $sconexao->prepare("INSERT INTO b_emprestimos (uid, idusuario, iditem, datasaida, datadevolucao, status, idatendente, idinstalacao) VALUES (?,?,?,?,?,?,?,?)");
			$adicionar->bind_param('iiissiis', $uid, $usuarioid, $acervoid, $datasaida, $datadevolucao, $status, $idatendente, $idinstalacao);
			
			if (!$adicionar->execute())
				$erro = 1;
			$emprestimoadd = 1;
		
		} else {
			$erro = 2;
			$tituloget			= stripslashes($acervo);
			$acervoidget		= $acervoid;
			$usuarioget			= stripslashes($usuario);
			$usuarioidget		= $usuarioid;
			$datasaidaget		= $_POST['datasaida'];
			$datadevolucaoget	= $_POST['datadevolucao'];
		}
	}
endif;

if (isset($_POST['alterar']) && empty($_POST['usuarioid']) && empty($_POST['acervoid'])):
	$erro			= 1;
	
elseif (isset($_POST['alterar']) && !empty($_POST['usuarioid']) && !empty($_POST['acervoid'])):
	$erro			= 0;
	$uid			= $_POST['alterarid'];
	$status			= 0;
	$usuarioid		= $sconexao->escape_string($_POST['usuarioid']);
	$usuario		= $sconexao->escape_string($_POST['usuario']);
	$acervoid		= $sconexao->escape_string($_POST['acervoid']);
	$acervo			= $sconexao->escape_string($_POST['acervo']);
	$datasaida		= padraoDatas($_POST['datasaida'],'s',0,1,1);
	$datadevolucao	= padraoDatas($_POST['datadevolucao'],'s',0);
	
	if (!empty($uid)) {
		$checar = $sconexao->prepare("SELECT uid FROM b_emprestimos WHERE uid=? AND idinstalacao=? AND status=0");
		$checar->bind_param('is', $uid, $idinstalacao);
		$checar->execute();
		$checar->store_result();
		$checar->bind_result($id);
		$checar->fetch();
		
		if ($checar->num_rows != 0) {
			$alterar = $sconexao->prepare("UPDATE b_emprestimos SET idusuario=?, iditem=?, datadevolucao=? WHERE uid=? AND idinstalacao=?");
			$alterar->bind_param('iisis', $usuarioid, $acervoid, $datadevolucao, $uid, $idinstalacao);
		
			if (!$alterar->execute())
				$erro = 1;
			$emprestimoedit = 1;
		}
	}
endif;

if (isset($_POST['apagar'])):
	$erro = 0;
	$uid = $_POST['apagarid'];
	
	if (!empty($uid)) {
		$deletar = $sconexao->query("DELETE FROM b_emprestimos WHERE uid=$uid and idinstalacao='$idinstalacao'");
		$emprestimodelet = 1;
	} else {
		$erro = 1;
	}
endif;

if (isset($apagarget)):
	$id	= $apagarget;
	$campooculto = '<input name="apagarid" type="hidden" id="apagarid" value="' . $id . '">';
	
	$seleciona = $sconexao->query("SELECT uid, idusuario, iditem, datadevolucao FROM b_emprestimos WHERE uid=$id AND idinstalacao='$idinstalacao'");
	$row = $seleciona->fetch_assoc();
	
	if (!empty($row)) {
		$idget			 	= $row['uid'];
		$usuarioget			= stripslashes(bdDados('usuario-nome',$row['idusuario']));
		$acervoget 			= stripslashes(bdDados('acervo-titulo',$row['iditem']));
		$datadevolucaoget	= padraoDatas($row['datadevolucao'],'e',0);
	} else {
		$erro = 3;
	}
endif;

if (isset($alterarget)):
	$id = $alterarget;
	$campooculto = '<input name="alterarid" type="hidden" id="alterarid" value="' . $id . '">';
	
	$seleciona = $sconexao->query("SELECT idusuario, iditem, datasaida, datadevolucao, status FROM b_emprestimos WHERE uid=$id AND idinstalacao='$idinstalacao'");
	$row = $seleciona->fetch_assoc();
	
	if (!empty($row)) {
		$tituloget			= stripslashes(bdDados('acervo-titulo',$row['iditem']));
		$acervoidget		= $row['iditem'];
		$usuarioget			= stripslashes(bdDados('usuario-nome',$row['idusuario']));
		$usuarioidget		= $row['idusuario'];
		$datasaidaget		= padraoDatas($row['datasaida'],'e',0);
		$datadevolucaoget	= padraoDatas($row['datadevolucao'],'e',0);
		$status				= $row['status'];
	}
endif;

if (isset($devolverget) || isset($renovarget)):
	$id = $devolverget.$renovarget;
	
	if (isset($devolverget))
	$campooculto = '<input name="devolverid" type="hidden" id="devolverid" value="' . $id . '">';
	if (isset($renovarget))
	$campooculto = '<input name="renovarid" type="hidden" id="renovarid" value="' . $id . '">';
	
	$seleciona = $sconexao->query("SELECT idusuario, iditem, datasaida, datadevolucao, status, uid FROM b_emprestimos WHERE ((uid=$id) AND (idinstalacao='$idinstalacao') AND (status=0))");
	$row = $seleciona->fetch_assoc();
	
	if (!empty($row)) {
		$idget				= $row['uid'];
		$acervoget			= stripslashes(bdDados('acervo-titulo',$row['iditem']));
		$acervoidget		= $row['iditem'];
		$usuarioget			= stripslashes(bdDados('usuario-nome',$row['idusuario']));
		$usuarioidget		= $row['idusuario'];
		$localizacaoget		= stripslashes(bdDados('acervo-localizacao',$row['iditem']));
		$datasaidaget		= padraoDatas($row['datasaida'],'e',0);
		$datadevolucaoget	= padraoDatas($row['datadevolucao'],'e',0);
		$datarenovarget		= padraoDatas($row['datadevolucao'],'e',1);
		$numexemplarget		= bdDados('acervo-exemplar',$row['iditem']);
	}
endif;

if (isset($_POST['devolver'])):
	$erro = 0;
	$uid = $_POST['devolverid'];
	$datadev = padraoDatas('agora','s',0,1);
	
	if (!empty($uid)) {
		$devolver = $sconexao->query("UPDATE b_emprestimos SET status=1, dataentrada='$datadev' WHERE uid=$uid and idinstalacao='$idinstalacao'");
		$emprestimodevolv = 1;
	} else {
		$erro = 1;
	}
endif;

if (isset($_POST['renovar'])):
	$erro = 0;
	$uid = $_POST['renovarid'];
	$datadevolucao = padraoDatas($_POST['datarenovacao'],'s',0);
	
	if (!empty($uid)) {
		$renovar = $sconexao->prepare("UPDATE b_emprestimos SET datadevolucao=? WHERE idinstalacao=? AND uid=?");
		$renovar->bind_param('ssi', $datadevolucao, $idinstalacao, $uid);
			
		if (!$renovar->execute())
			$erro = 1;
		$emprestimorenov = 1;
	} else {
		$erro = 1;
	}
endif;

if (!empty($filtroa)):
	$selecionafiltro = $sconexao->query("SELECT uid, titulo FROM b_itens WHERE uid=$filtroa AND idinstalacao='$idinstalacao'");
	$row = $selecionafiltro->fetch_assoc();
	
	if (!empty($row)) {
		$tituloget		= stripslashes($row['titulo']);
		$acervoidget	= $row['uid'];
	};
elseif (!empty($filtrou)):
	$selecionafiltro = $sconexao->query("SELECT uid, nome FROM b_usuarios WHERE uid=$filtrou AND idinstalacao='$idinstalacao'");
	$row = $selecionafiltro->fetch_assoc();
	
	if (!empty($row)) {
		$usuarioget		= stripslashes($row['nome']);
		$usuarioidget	= $row['uid'];
	};
endif;

include_once("inc/cabecalho.php");
include_once("inc/menu-cabecalho.php"); ?>
<div class="container painel">
<div class="row">
<?php include_once("inc/menu-painel.php"); ?>
<div class="col-sm-9">
<div class="page-header">
<h1>Empréstimos</h1>
</div>
<div class="col-md-12">
<?php if (infoConfiguracao('emprestimos')==0): ?>
<div class="panel panel-default">
  <div class="panel-body">
	<h4>A biblioteca aceita empréstimos de livros?</h4>
	<p>Atualmente a configuração da sua biblioteca não aceita empréstimos de livros, altere as informações para começar a aceitar.</p>
	<p><a href="configuracoes.php#emprestimos" class="btn btn-default btn-sm">Altere a configuração</a></p>
  </div>
</div>
<?php else: ?>
<?php if (infoConfiguracao('ajuda')=='1'): ?>
<div class="panel panel-default">
  <div class="panel-body">
	<h4>Ajuda</h4>
	<p>Este é o formulário padrão de manutenção dos empréstimos cadastrados, ou seja, é o mesmo formulário que você estará utilizando para adicionar, alterar ou confirmar a exclusão de um empréstimo cadastrado.</p>
    <p>Leia as <a href="pagina_dicasdeuso.php">Dicas de Uso</a>, para conhecer algumas recomendações. Existem algumas recomendações que devem ser seguidas para facilitar o processo de empréstimo e devolução,  por exemplo, todo empréstimo deve ser visto como &quot;um usuário está emprestando um ítem&quot;. E este mesmo usuário deve ser conhecido no momento da devolução. Cada ítem emprestado deverá ser cadastrado separadamente.</p>
    <p>Os campos são literais quanto à informação que deverá ser cadastrada em cada campo. Entretanto neste caso, todos os campos são obrigatórios.</p>
    <p>No campo &quot;ítem do acervo&quot;, você deverá digitar parte do título ou o nome do autor do ítem que será emprestado, e clicar no botão [selecionar], este botão abrirá uma listagem com as obras encontradas nesta busca, nesta listagem, cada obra possuí o seu botão de seleção, você somente estará marcando um ítem para empréstimo quando clicar no botão [selecionar] nesta listagem. O mesmo vale para o &quot;usuário&quot;, onde será necessário digitar parte do nome do usuário e clicar no botão [selecionar] para a abertura da listagem de usuários encontrados.</p>
    <p>Caso você apenas digite o nome do ítem e/ou o nome do usuário e não realize o processo de seleção, o empréstimo não será adicionado, resultando em erro.</p>
    <p><a href="configuracoes.php?ajuda=0" class="btn btn-default btn-xs">Quero ocultar toda a ajuda</a></p>
    </div></div>
<?php endif; ?>
<?php if ($erro == 1): ?>
<div class="alert alert-danger">
  <strong>Erro:</strong> O empréstimo não foi adicionado, tente novamente.
</div>
<?php elseif ($erro == 2 && !isset($_POST['adicionar'])): ?>
<div class="alert alert-danger">
  <strong>Erro: </strong> Este empréstimo  parece que já existe.<br>
  Para autorizar o empréstimo de outra obra com o mesmo título para este usuário, basta clicar novamente no botão [Emprestar].
</div>
<?php else:?>
<?php if ($emprestimoadd == 1): ?>
<div class="alert alert-success">
  <strong>Sucesso!</strong> O empréstimo foi adicionado.
</div>
<?php endif;?>
<?php if ($emprestimoedit == 1): ?>
<div class="alert alert-success">
  Empréstimo atualizado.
</div>
<?php endif;?>
<?php if ($emprestimodelet == 1): ?>
<div class="alert alert-success">
  Empréstimo apagado.
</div>
<?php endif; 
if ($emprestimorenov == 1): ?>
<div class="alert alert-success">
  Empréstimo renovado.
</div>
<?php endif; 
if ($emprestimodevolv == 1): ?>
<div class="alert alert-success">
  Empréstimo devolvido.
</div>
<?php endif; 
if (isset($acervoidget) && !isset($renovarget)  && !isset($devolverget) && !isset($apagarget) && !isset($alterarget)):
if (!Disponibilidade($acervoidget)): ?>
<div class="alert alert-warning">
  Parece que este ítem não está disponível, ou então a quantidade cadastrada não corresponde ao número de ítens disponíveis na biblioteca.
</div>
<?php endif; endif; ?>
<?php endif; ?>
<form class="form-horizontal" role="form" method="post">

<?php if(isset($devolverget)):
  if (!isset($_POST['devolver'])) {?>
  <legend><?php echo 'Devolver um empréstimo'; ?></legend>
  <div class="row">
  <?php if ($erro == 3): ?>
<div class="col-md-offset-1 col-md-11">
<p>O item não existe na sua instalação, caso você esteja vendo esta mensagem, envie uma notificação de erro para que possamos tentar solucionar este problema na próxima versão.</p>
<p><a href="acervo.php" class="btn btn-default btn-sm">Voltar</a></p>
</div>
</div><?php else: ?>
  <div class="col-md-offset-1 col-md-11"><h2>#<?php echo $idget ?></h2>
  <h4>Empréstimo de: <?php echo $usuarioget ?></h4>
  <p>Ítem emprestado: <?php echo $acervoget ?></p>
  <?php if (infoConfiguracao('abordagemacervo')=='1') {
	if (!empty($numexemplarget))
		echo "<p>Exemplar: $numexemplarget</p>"; } ?>
  <p>Previsão de devolução: <?php echo $datadevolucaoget ?></p>
  <p>Localização: <?php echo $localizacaoget ?></p>
  <p class="divider"></p>
  </div>
  </div>
  <div class="row">
  <div class="col-md-offset-1 col-md-6">
  <p><button type="submit" name="devolver" class="btn btn-success btn-sm">Confirmar devolução</button><?php echo $campooculto;?></p></div>
  </div>
	<?php endif; ?>
  <?php };
  
elseif(isset($renovarget)): 
 if (!isset($_POST['renovar'])) {?>
  <legend><?php echo 'Renovar um empréstimo'; ?></legend>
  <div class="row">
  <?php if ($erro == 3): ?>
<div class="col-md-offset-1 col-md-11">
<p>O item não existe na sua instalação, caso você esteja vendo esta mensagem, envie uma notificação de erro para que possamos tentar solucionar este problema na próxima versão.</p>
<p><a href="acervo.php" class="btn btn-default btn-sm">Voltar</a></p>
</div>
</div><?php else: ?>
  <div class="col-md-offset-1 col-md-11"><h2>#<?php echo $idget ?></h2>
  <h4>Empréstimo de: <?php echo $usuarioget ?></h4>
  <p>Ítem emprestado: <?php echo $acervoget ?></p>
  <p>Previsão de devolução: <?php echo $datadevolucaoget ?></p>
  <p class="divider"></p>
  <p>Nova data de devolução:</p>
	<p class="col-md-4">
	  <input type="text" class="form-control" id="datarenovacao" name="datarenovacao"<?php if (isset($datarenovarget)) echo " value='$datarenovarget'";?>>
	</p>
  </div>
  </div>

  <div class="row">
  <div class="col-md-offset-1 col-md-6">

<p><button type="submit" name="renovar" class="btn btn-success btn-sm">Confirmar renovação</button><?php echo $campooculto;?></p></div>
  </div>
	<?php endif; ?>
  <?php };

 elseif(isset($apagarget)): 
  if (!isset($_POST['apagar'])) {?>
  <legend><?php echo 'Apagar um empréstimo'; ?></legend>
  <div class="row">
  <?php if ($erro == 3): ?>
<div class="col-md-offset-1 col-md-11">
<p>O item não existe na sua instalação, caso você esteja vendo esta mensagem, envie uma notificação de erro para que possamos tentar solucionar este problema na próxima versão.</p>
<p><a href="acervo.php" class="btn btn-default btn-sm">Voltar</a></p>
</div>
</div><?php else: ?>
  <div class="col-md-offset-1 col-md-11"><h2>#<?php echo $idget ?></h2>
  <h4>Empréstimo de: <?php echo $usuarioget ?></h4>
  <p>Ítem emprestado: <?php echo $acervoget ?></p>
  <p>Previsão de devolução: <?php echo $datadevolucaoget ?></p>
  <p class="divider"></p>
  </div>
  </div>
  <div class="row">
  <div class="col-md-offset-1 col-md-6"><p>Deseja apagar este ítem? Não há como desfazer esta ação. Prefira sempre realizar alterações, deixando a opção de apagar empréstimos apenas para a correção de eventuais erros. <?php echo $campooculto;?></p>
  <p><button type="submit" name="apagar" class="btn btn-alerta btn-sm">Apagar empréstimo</button></div>
  </div>
	<?php endif; ?>
  <?php };
 else: ?>
  <div class="form-group"><a name="emprestimo" id="emprestimo"></a>
	<label for="acervo" class="col-md-3 control-label">Ítem do Acervo:</label>
	<div class="col-md-5">
    <div class="input-group">
	  <input type="text" class="form-control" id="acervo" name="acervo"<?php if (isset($tituloget)) echo " value='$tituloget'";?>><input type="hidden" id="acervoid" name="acervoid"<?php if (isset($acervoidget)) echo " value='$acervoidget'";?>>
	      <span class="input-group-btn">
        <a data-toggle="modal" href="#selecaoMod" class="btn btn-default selecaoabrirMod" data-tipo="a" type="button">Selecionar</a>
      </span>
    </div>
    </div>
  </div>
  <div class="form-group">
	<label for="usuario" class="col-md-3 control-label">Usuário:</label>
	<div class="col-md-5">
    <div class="input-group">
	  <input type="text" class="form-control" id="usuario" name="usuario"<?php if (isset($usuarioget)) echo " value='$usuarioget'";?>><input type="hidden" id="usuarioid" name="usuarioid"<?php if (isset($usuarioidget)) echo " value='$usuarioidget'";?>>
	      <span class="input-group-btn">
        <a data-toggle="modal" href="#selecaoMod" class="btn btn-default selecaoabrirMod" data-tipo="u" type="button">Selecionar</a>
      </span>
    </div>
    </div>
  </div>
  <div class="form-group">
	<label for="datasaida" class="col-md-3 control-label">Data de Saída:</label>
	<div class="col-md-3">
	  <input type="text" class="form-control" id="datasaida" name="datasaida"<?php if (isset($datasaidaget)) echo " value='$datasaidaget' disabled='disabled'"; else echo " value='" . padraoDatas('agora','e') . "'";?>><?php echo $campooculto;?>
	</div>
  </div>
  <div class="form-group">
	<label for="datadevolucao" class="col-md-3 control-label">Data de Devolução:</label>
	<div class="col-md-3">
	  <input type="text" class="form-control" id="datadevolucao" name="datadevolucao"<?php if (isset($datadevolucaoget)) echo " value='$datadevolucaoget'"; else echo " value='" . padraoDatas('agora','e',1) . "'";?>>
	</div>
  </div>
  <div class="form-group">
	<div class="col-md-offset-3 col-md-5">
	  <?php
		if (isset($alterarget))
			echo '<button type="submit" name="alterar" class="btn btn-success">Alterar empréstimo</button>'; 
		else
			echo '<button type="submit" name="adicionar" class="btn btn-success">Emprestar</button>';
		if (isset($_POST['adicionar']))
			echo '<input name="excecao" type="hidden" value="s">';
		?>
	</div>
  </div>  
<?php endif; ?>
<?php endif; ?>
</div>
</div>
</div>
</div>

  <div class="modal" id="selecaoMod" tabindex="-1" role="dialog" aria-labelledby="selecaoModLabel" aria-hidden="true"> 
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Seleção do empréstimo</h4>
        </div>
        <form role="form">
        <div class="modal-body">
            <div id="modal-acervo">
            Não carregou...
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Fechar</button>
        </div></form>
      </div>
    </div>
  </div>
  
<script>
$(function () {
    $(".selecaoabrirMod").click(function () {
        var tipo = $(this).data('tipo');
		if (tipo == 'a')
        var q = $("input#acervo").val();
		else
		var q = $("input#usuario").val();

        $.ajax({
            type: "POST",
            url: "inc/emprestimos_a_lista.php",
            data: 't=' + tipo + '&q=' + q,
            success: function (data) {
                $('.modal-body').html(data);
            }
        });
        return;
    });
});
</script>
<?php include_once("inc/rodape.php"); ?>
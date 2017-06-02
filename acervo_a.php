<?php 
/**
 * Gera a página do formulário do "Acervo"
 *
 * Esta página permite a alteração de dados do acervo através de formulário
 */ 
include("login_acesso.php");
include_once("instalacao.php");
include_once("inc/conexao.php");
include_once("inc/funcoes.php");

$alterarget		= $_GET['alterar'];
$apagarget		= $_GET['apagar'];
$idatendente 	= $_SESSION['usuario'];
$idinstalacao	= $_SESSION['idinstalacao'];

if (isset($_POST['adicionar']) && isset($_POST['titulo'])):
	$erro			= 0;
	$uidmanual		= $_POST['uid'];
	$titulo			= $sconexao->escape_string($_POST['titulo']);
	$tipo			= $sconexao->escape_string($_POST['tipo']);
	$autor			= $sconexao->escape_string($_POST['autor']);
	$editora		= $sconexao->escape_string($_POST['editora']);
	$edicao			= $sconexao->escape_string($_POST['edicao']);
	$ano			= $sconexao->escape_string($_POST['ano']);
	$assunto		= $sconexao->escape_string($_POST['assuntos']);
	$numexemplar	= $sconexao->escape_string($_POST['numexemplar']);
	$quantidade		= $sconexao->escape_string($_POST['quantidade']);
	$localizacao	= $sconexao->escape_string($_POST['localizacao']);
	$origem			= $sconexao->escape_string($_POST['origem']);
	$isbn			= $sconexao->escape_string($_POST['isbn']);
	$bloqueio		= 0;
	$statusitem		= 0;
	$datacad		= date("Y-m-d");
	$excecao		= $_POST['excecao'];
	if (empty($uidmanual))
	$uid			= uID('itens');
	else
	$uid			= $uidmanual;
	
	if (!empty($titulo)) {
		$checar = $sconexao->prepare("SELECT uid FROM b_itens WHERE uid=? AND idinstalacao=?");
		$checar->bind_param('is', $uid, $idinstalacao);
		$checar->execute();
		$checar->store_result();
		$checar->bind_result($id);
		$checar->fetch();
		
		//if ($checar->num_rows == 0 || isset($excecao)) {
		if ($checar->num_rows == 0) {
			$adicionar = $sconexao->prepare("INSERT INTO b_itens (uid, tipo, titulo, autor, editora, edicao, ano, assuntos, datacad, qntd, localizacao, statusitem, bloqueio, isbn, idatendente, idinstalacao, numexemplar, origem) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
			$adicionar->bind_param('iisssssssisiisisis', $uid, $tipo, $titulo, $autor, $editora, $edicao, $ano, $assunto, $datacad, $quantidade, $localizacao, $statusitem, $bloqueio, $isbn, $idatendente, $idinstalacao, $numexemplar, $origem);
			
			if (!$adicionar->execute())
				$erro = 1;
			$itemadd = 1;
			
		} else {
			$erro = 2;
			$idget			= $uid;
			$tituloget		= stripslashes($titulo);
			$tipoget		= stripslashes($tipo);
			$autorget		= stripslashes($autor);
			$editoraget		= stripslashes($editora);
			$edicaoget		= stripslashes($edicao);
			$anoget			= stripslashes($ano);
			$assuntosget	= stripslashes($assunto);
			$quantidadeget	= stripslashes($quantidade);
			$numexemplarget	= stripslashes($numexemplar);
			$localizacaoget	= stripslashes($localizacao);
			$origemget		= stripslashes($origem);
			$isbnget		= stripslashes($isbn);
		}
	} else {
		$erro = 4;
	}
endif;

if (isset($_POST['alterar']) && isset($_POST['titulo']) && isset($_POST['alterarid'])):
	$erro			= 0;
	$uid			= $_POST['alterarid'];
	$uidmanual		= $_POST['uid'];
	$titulo			= $sconexao->escape_string($_POST['titulo']);
	$tipo			= $sconexao->escape_string($_POST['tipo']);
	$autor			= $sconexao->escape_string($_POST['autor']);
	$editora		= $sconexao->escape_string($_POST['editora']);
	$edicao			= $sconexao->escape_string($_POST['edicao']);
	$ano			= $sconexao->escape_string($_POST['ano']);
	$assuntos		= $sconexao->escape_string($_POST['assuntos']);
	$numexemplar	= $sconexao->escape_string($_POST['numexemplar']);
	$quantidade		= $sconexao->escape_string($_POST['quantidade']);
	$localizacao	= $sconexao->escape_string($_POST['localizacao']);
	$origem			= $sconexao->escape_string($_POST['origem']);
	$isbn			= $sconexao->escape_string($_POST['isbn']);
	$bloqueio		= 0;
	$statusitem		= 0;
	$alterarget		= $uidmanual;
	
	if (!empty($uid)) {
		$checar = $sconexao->prepare("SELECT uid FROM b_itens WHERE uid=? AND idinstalacao=?");
		$checar->bind_param('is', $uid, $idinstalacao);
		$checar->execute();
		$checar->store_result();
		$checar->bind_result($id);
		$checar->fetch();
		
		if ($checar->num_rows != 0) {
			$alterar = $sconexao->prepare("UPDATE b_itens SET tipo=?, titulo=?, autor=?, editora=?, edicao=?, ano=?, assuntos=?, qntd=?, localizacao=?, statusitem=?, bloqueio=?, isbn=?, idatendente=?, uid=?, numexemplar=?, origem=? WHERE uid=?");
			$alterar->bind_param('issssssisiisiiisi', $tipo, $titulo, $autor, $editora, $edicao, $ano, $assuntos, $quantidade, $localizacao, $statusitem, $bloqueio, $isbn, $idatendente, $uidmanual, $numexemplar, $origem, $uid);
			
			if (!$alterar->execute())
				$erro = 1;
				
			$alterar = $sconexao->prepare("UPDATE b_emprestimos SET iditem=? WHERE iditem=?");
			$alterar->bind_param('ii', $uidmanual, $uid);
			
			if (!$alterar->execute())
				$erro = 1;
				
			$itemedit = 1;
		}
	}
endif;

if (isset($_POST['apagar'])):
	$erro = 0;
	$uid = $_POST['apagarid'];
	
	if (!empty($uid)) {
		$deletar = $sconexao->query("DELETE FROM b_itens WHERE uid=$uid and idinstalacao='$idinstalacao'");
		$deletar = $sconexao->query("DELETE FROM b_emprestimos WHERE iditem=$uid and idinstalacao='$idinstalacao'");
		$itemdelet = 1;
	} else {
		$erro = 1;
	}
endif;

if (isset($apagarget)):
	$id = $apagarget;
	$campooculto = '<input name="apagarid" type="hidden" id="apagarid" value="' . $id . '">';
	
	$seleciona = $sconexao->query("SELECT uid, titulo, tipo, autor, editora, edicao, ano, assuntos, qntd, localizacao, isbn, numexemplar, origem FROM b_itens WHERE uid=$id AND idinstalacao='$idinstalacao'");
	$row = $seleciona->fetch_assoc();
	
	if (!empty($row)) {
		$idget			= $row['uid'];
		$tituloget		= stripslashes($row['titulo']);
		$tipoget		= stripslashes($row['tipo']);
		$autorget		= stripslashes($row['autor']);
		$editoraget		= stripslashes($row['editora']);
		$edicaoget		= stripslashes($row['edicao']);
		$anoget			= stripslashes($row['ano']);
		$assuntosget	= stripslashes($row['assuntos']);
		$quantidadeget	= stripslashes($row['qntd']);
		$numexemplarget	= stripslashes($row['numexemplar']);
		$localizacaoget	= stripslashes($row['localizacao']);
		$origemget		= stripslashes($row['origem']);
		$isbnget		= stripslashes($row['isbn']);
	} else {
		$erro = 3;
	}
endif;

if (isset($alterarget)):
	$id	= $alterarget;
	$campooculto = '<input name="alterarid" type="hidden" id="alterarid" value="' . $id . '">';
	
	$seleciona = $sconexao->query("SELECT uid, titulo, tipo, autor, editora, edicao, ano, assuntos, qntd, localizacao, isbn, numexemplar, origem FROM b_itens WHERE uid=$id AND idinstalacao='$idinstalacao'");
	$row = $seleciona->fetch_assoc();
	
	if (!empty($row)) {
		$idget			= $row['uid'];
		$tituloget		= stripslashes($row['titulo']);
		$tipoget		= stripslashes($row['tipo']);
		$autorget		= stripslashes($row['autor']);
		$editoraget		= stripslashes($row['editora']);
		$edicaoget		= stripslashes($row['edicao']);
		$anoget			= stripslashes($row['ano']);
		$assuntosget	= stripslashes($row['assuntos']);
		$numexemplarget	= stripslashes($row['numexemplar']);
		$quantidadeget	= stripslashes($row['qntd']);
		$localizacaoget	= stripslashes($row['localizacao']);
		$origemget		= stripslashes($row['origem']);
		$isbnget		= stripslashes($row['isbn']);
	}
endif;

include_once("inc/cabecalho.php");
include_once("inc/menu-cabecalho.php"); ?>
<div class="container painel">
<div class="row">
<?php include_once("inc/menu-painel.php"); ?>
<div class="col-sm-9">
<div class="page-header">
<h1>Acervo da Biblioteca</h1>
</div>
<div class="col-md-12">
<?php if (infoConfiguracao('ajuda')=='1'): ?>
<div class="panel panel-default">
  <div class="panel-body">
	<h4>Ajuda</h4>
	<p>Este é o formulário padrão de manutenção do acervo cadastrado, ou seja, é o mesmo formulário que você estará utilizando para adicionar, alterar ou confirmar a exclusão de um ítem cadastrado.</p>
	<p>Leia as <a href="pagina_dicasdeuso.php">Dicas de Uso</a>, para conhecer algumas recomendações. A mais importante de todas, é a recomendação de que se cadastre pelo menos o título do ítem, o autor do ítem e uma breve descrição no assunto, pois isso irá facilitar para que os usuários encontrem os ítens do acervo no futuro.</p>
	<p>Os campos são literais quanto à informação que deverá ser cadastrada em cada campo. De todos os campos, apenas o título é obrigatório.</p>
	<p>Na quantidade, sempre adicione o número de ítens iguais disponíveis. Caso esteja adicionando obras sem levar em consideração as edições, poderá somar todos os ítens. Já a localização está relacionada ao local físico onde o livro está na bilbioteca, geralmente organizado por estante.</p>
	<p><a href="configuracoes.php?ajuda=0" class="btn btn-default btn-xs">Quero ocultar toda a ajuda</a></p>
  </div>
</div>
<?php endif; ?>
<?php if ($erro == 1): ?>
<div class="alert alert-danger">
  <strong>Erro:</strong> O registro não foi adicionado, tente novamente.
</div>
<?php elseif ($erro == 2): ?>
<div class="alert alert-danger">
  <strong>Erro: </strong>Parece que já existe um ítem com esta Identificação (#).</div>
<?php elseif ($erro == 4): ?>
<div class="alert alert-warning">
  <strong>Erro: </strong>Parece que o título está em branco.<br />Este é o único campo obrigatório que você precisará preencher em todos os ítens que adicionar.
</div>
<?php else:?>
<?php if ($itemadd == 1): ?>
<div class="alert alert-success">
  <strong>Sucesso!</strong> O ítem foi adicionado.
</div>
<?php endif;?>
<?php if ($itemedit == 1): ?>
<div class="alert alert-success">
  Ítem atualizado.
</div>
<?php endif;?>
<?php if ($itemdelet == 1): ?>
<div class="alert alert-success">
  Ítem apagado.
</div>
<?php endif; ?>
<?php endif; ?>
<form class="form-horizontal" role="form" method="post">
  <?php if (isset($apagarget)): 
  if (!isset($_POST['apagar'])) {?>
  <legend><?php echo 'Apagar um item'; ?></legend>
  <div class="row">
  <?php if ($erro == 3): ?>
<div class="col-md-offset-1 col-md-11">
<p>O item não existe na sua instalação, caso você esteja vendo esta mensagem, envie uma notificação de erro para que possamos tentar solucionar este problema na próxima versão.</p>
<p><a href="acervo.php" class="btn btn-default btn-sm">Voltar</a></p>
</div>
</div><?php else: ?>
  <div class="col-md-offset-1 col-md-11"><h2>#<?php echo $idget ?> <small><?php
  $sql = "SELECT uid, classificacao FROM b_itens_classificacao WHERE idinstalacao='$idinstalacao' AND uid=$tipoget";
if(!$classificacaoacervo = $sconexao->query($sql)){ 
	die('<pre>Desculpe, mas aconteceu um erro [' . $sconexao->error . ']</pre>'); 
}
if ($classificacaoacervo->num_rows != 0){ $row = $classificacaoacervo->fetch_assoc();
echo $row['classificacao']; } ?></small></h2>
  <h4><?php echo $tituloget ?></h4>
  <p><?php echo $autorget ?></p>
  <?php if (!empty($numexemplarget)) 
  	echo "<p>Exemplar: $numexemplarget</p>";?>
  <p class="divider"></p>
  </div>
  </div>
  <div class="row">
  <div class="col-md-offset-1 col-md-6"><p>Deseja apagar este ítem? Não há como desfazer esta ação. <?php echo $campooculto;?></p>
  <p><button type="submit" name="apagar" class="btn btn-alerta btn-sm">Apagar ítem</button></div>
  </div>
	<?php endif; ?>
  <?php };
  else: ?>
  <legend><?php
	if (isset($alterarget))
		echo 'Alterar um ítem';
	else
		echo 'Adicionar um novo ítem';
  ?></legend>

  <div class="form-group">
	<label for="uid" class="col-md-3 control-label">Identificação (#):</label>
	<div class="col-md-2">
	  <input type="text" class="form-control" id="uid" name="uid"<?php if (isset($idget)) echo " value='$idget'";?> placeholder="<?php echo uID('itens'); ?>">
	</div>
  </div>
  
  <div class="form-group">
	<label for="tipo" class="col-md-3 control-label">Classificação do ítem</label>
	<div class="col-md-4">
	 <select class="form-control" id="tipo" name="tipo">
	  <option></option>
	<?php
	$sql = "SELECT uid, classificacao FROM b_itens_classificacao WHERE idinstalacao='$idinstalacao' ORDER BY uid ASC";
if(!$classificacaoacervo = $sconexao->query($sql)){ 
	die('<pre>Desculpe, mas aconteceu um erro [' . $sconexao->error . ']</pre>'); 
}
if ($classificacaoacervo->num_rows != 0){
	while ($row = $classificacaoacervo->fetch_assoc()){ 
	echo '<option value="' . $row['uid'] . '"' ;
	if ($tipoget == $row['uid'])
		echo ' selected';
	echo '>' . $row['classificacao'] . '</option>';
	}
	}
	?>
	</select>
	<span class="help-block">Ou adicione uma <a data-toggle="modal" href="#classificacaoAcervoMod">nova classificação</a>.</span>
	</div>
  </div>

  <div class="form-group">
	<label for="titulo" class="col-md-3 control-label">Título:</label>
	<div class="col-md-6">
	  <input type="text" class="form-control" id="titulo" name="titulo"<?php if (isset($tituloget)) echo " value='$tituloget'";?>><?php echo $campooculto;?>
	</div>
  </div>
  
  <div class="form-group">
	<label for="autor" class="col-md-3 control-label">Autor(es):</label>
	<div class="col-md-5">
	  <input type="text" class="form-control" id="autor" name="autor"<?php if (isset($autorget)) echo " value='$autorget'";?>>
	</div>
  </div>
  
  <div class="form-group">
	<label for="editora" class="col-md-3 control-label">Editora:</label>
	<div class="col-md-4">
	  <input type="text" class="form-control" id="editora" name="editora"<?php if (isset($editoraget)) echo " value='$editoraget'";?>>
	</div>
  </div>

  <div class="form-group">
	<label for="edicao" class="col-md-3 control-label">Edição:</label>
	<div class="col-md-3">
	  <input type="text" class="form-control" id="edicao" name="edicao"<?php if (isset($edicaoget)) echo " value='$edicaoget'";?>>
	</div>
  </div>
  
  <div class="form-group">
	<label for="ano" class="col-md-3 control-label">Ano da Edição:</label>
	<div class="col-md-2">
	  <input type="text" class="form-control" id="ano" name="ano"<?php if (isset($anoget)) echo " value='$anoget'";?>>
	</div>
  </div>

  <div class="form-group">
	<label for="isbn" class="col-md-3 control-label">ISBN:</label>
	<div class="col-md-4">
	  <input type="text" class="form-control" id="isbn" name="isbn"<?php if (isset($isbnget)) echo " value='$isbnget'";?>>
	</div>
  </div>
  
  <div class="form-group">
	<label for="assuntos" class="col-md-3 control-label">Assunto:</label>
	<div class="col-md-6">
	  <textarea name="assuntos" rows="4" class="form-control" id="assuntos"><?php if (isset($assuntosget)) echo $assuntosget;?></textarea>
	  <span class="help-block">Descreva rapidamente o assunto do ítem, utilizando-se de palavras chaves que facilitem a procura pelo mesmo. (Máximo de 256 caracteres.)</span>
	</div>
  </div>
  <legend>Na biblioteca</legend>
  <?php if (infoConfiguracao('abordagemacervo')=='1'): ?>
  <div class="form-group">
	<label for="numexemplar" class="col-md-3 control-label">Núm. Exemplar:</label>
	<div class="col-md-2">
	  <input type="text" class="form-control" id="numexemplar" name="numexemplar" <?php if (isset($numexemplarget)) echo " value='$numexemplarget'";?>><input name="quantidade" type="hidden" value="1">
	</div>
  </div>
  <?php else: ?>
  <div class="form-group">
	<label for="quantidade" class="col-md-3 control-label">Quantidade:</label>
	<div class="col-md-2">
	  <input type="text" class="form-control" id="quantidade" name="quantidade" <?php if (isset($quantidadeget)) echo " value='$quantidadeget'"; else echo " value='1'";?>><input name="numexemplar" type="hidden" value="0">
	</div>
  </div>
  <?php endif; ?>

  <div class="form-group">
	<label for="localizacao" class="col-md-3 control-label">Localização:</label>
	<div class="col-md-4">
	  <input type="text" class="form-control" id="localizacao" name="localizacao"<?php if (isset($localizacaoget)) echo " value='$localizacaoget'";?>>
	</div>
  </div> 

  <div class="form-group">
	<label for="origem" class="col-md-3 control-label">Origem:</label>
	<div class="col-md-4">
	  <input type="text" class="form-control" id="origem" name="origem"<?php if (isset($origemget)) echo " value='$origemget'";?>>
	</div>
  </div> 
  
  <div class="form-group">
	<div class="col-md-offset-3 col-md-5">
	  <?php
		if (isset($alterarget))
			echo '<button type="submit" name="alterar" class="btn btn-success">Alterar ítem</button>'; 
		else
			echo '<button type="submit" name="adicionar" class="btn btn-success">Adicionar ítem</button>';
		//if (isset($_POST['titulo']))
		//	echo '<input name="excecao" type="hidden" value="s">';
		?>
	</div>
  </div>
  <?php endif; ?>
</form>
</div>
</div>
</div>
</div>
  <div class="modal" id="classificacaoAcervoMod" tabindex="-1" role="dialog" aria-labelledby="classificacaoAcervoModLabel" aria-hidden="true"> 
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Classificação do acervo</h4>
        </div>
        <form role="form">
        <div class="modal-body">
        	<div class="alert alert-danger alert-dismissable" id="acervo-msg-erro"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
Aconteceu algum erro... Tente novamente</div>
            <div class="alert alert-success alert-dismissable" id="acervo-msg-ok"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Ação realizada com sucesso</div>
			<input name="acervoidinstalacao" id="acervoidinstalacao" type="hidden" value="<?php echo $idinstalacao; ?>">
            <input name="acervoalterarid" id="acervoalterarid" type="hidden">
            <input name="acervoapagarid" id="acervoapagarid" type="hidden">
            <div id="modal-acervo"><label for="acervocategoria">Nome da Categoria</label>
            <input type="text" class="form-control" id="acervocategoria">
            <span class="help-block">A categoria de classificação do acervo é uma forma de união de tipos de ítens disponíveis, como por exemplo: Livro, Dicionário, Revista, Periódico, DVD, etc.<br />Dica: Prefira palavras no singular.</span>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Fechar</button>
          <button type="submit" class="btn btn-success btn-sm" id="acervosalvarMod">Adicionar Categoria</button>
        </div></form>
      </div>
    </div>
  </div>

<script>
$(function () {
    $("#acervo-msg-erro").hide();
    $("#acervo-msg-ok").hide();
    $("#acervosalvarMod").click(function () {
		$(this).button('loading');
        var idinstalacao = $("input#acervoidinstalacao").val();
        var alterarid = $("input#acervoalterarid").val();
        var apagarid = $("input#acervoapagarid").val();
        var categoria = $("input#acervocategoria").val();
        if (categoria == "") {
            $("input#acervocategoria").focus();
            return false;
        }

        $.ajax({
            type: "POST",
            url: "inc/configuracoes_classificacao_acervo.php",
            data: 'categoria=' + categoria + '&idinstalacao=' + idinstalacao + '&apagarid=' + apagarid + '&alterarid=' + alterarid,
            success: function (data) {
				setTimeout(function () {$("#acervosalvarMod").button('reset')}, 1000)
                if (data == "ok") {
                    $('#acervo-msg-ok').show();
                    $("#acervo-msg-erro").hide();
                } else {
                    $('#acervo-msg-erro').show();
                    $("#acervo-msg-ok").hide();
                }
            }
        });
        return false;
    });

    $('#classificacaoAcervoMod').on('hide.bs.modal', function () {
        $("input#acervoalterarid").val('');
        window.location.reload();
    });

    $(".classificacaoEditar").on("click", function () {
        $(".modal-body #acervocategoria").val($(this).data('categoria'));
        $(".modal-body #acervoalterarid").val($(this).data('idcategoria'));
        $("#acervosalvarMod").html('Alterar Categoria');
    });
	
    $(".classificacaoApagar").on("click", function () {
        $("#modal-acervo").html('<p>Você irá apagar a categoria: <strong>' + $(this).data('categoria') + '</strong>.<br><br>Ao apagar esta categoria de classificação, todos os itens cadastrados perderão sua classificação, mas não serão apagados.<br><br>Não há como desfazer esta opção, prefira alterar a categoria ao invés de apagá-la.</p>');
        $(".modal-body #acervoapagarid").val($(this).data('idcategoria'));
        $("#acervosalvarMod").html('Apagar Categoria');
        $("#acervosalvarMod").attr("class", "btn btn-alerta btn-sm");
    });
	
});
</script>
<?php include_once("inc/rodape.php"); ?>
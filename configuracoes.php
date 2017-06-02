<?php
/**
 * Gera a página "Configurações"
 *
 * Esta página permite a alteração de configurações do sistema que estão
 * salvas no banco de dados de configurações únicas.
 */ 
include("login_acesso.php");
include_once("instalacao.php");
include_once("inc/conexao.php");
include_once("inc/funcoes.php");

$apagarget		= $_GET['apagar'];
$ajudaget		= $_GET['ajuda'];
$idatendente	= $_SESSION['usuario'];
$idinstalacao	= $_SESSION['idinstalacao'];

if (isset($_POST['salvar'])):
	$erro				= 0;
	$uid				= $_POST['uid'];
	$nomebiblioteca		= $sconexao->escape_string($_POST['nomebiblioteca']);
	$descricao			= $sconexao->escape_string($_POST['descricao']);
	$email				= $sconexao->escape_string($_POST['email']);
	$tipoinstalacao		= $sconexao->escape_string($_POST['tipoinstalacao']);
	$emprestimos		= $sconexao->escape_string($_POST['emprestimos']);
	$emprestimosdias	= $sconexao->escape_string($_POST['emprestimosdias']);
	$ajudaconfiguracao	= $sconexao->escape_string($_POST['ajudaconfiguracao']);
	$abordagemacervo	= $sconexao->escape_string($_POST['abordagemacervo']);
	$paginacao			= $sconexao->escape_string($_POST['paginacao']);
	
	if (!empty($uid)) {
		$checar = $sconexao->prepare("SELECT id FROM b_configuracoes WHERE id=? AND idinstalacao=?");
		$checar->bind_param('is', $uid, $idinstalacao);
		$checar->execute();
		$checar->store_result();
		$checar->bind_result($id);
		$checar->fetch();
		
		if ($checar->num_rows != 0) {
			$alterardados = $sconexao->prepare("UPDATE b_configuracoes SET nomebiblioteca=?, tipoinstalacao=?, descricao=?, emprestimos=?, emprestimosdias=?, emailbiblioteca=?, paginacao=?, ajudaconfiguracao=?, abordagemacervo=? WHERE idinstalacao=?");
			$alterardados->bind_param('sisiisiiis', $nomebiblioteca, $tipoinstalacao, $descricao, $emprestimos, $emprestimosdias, $email, $paginacao, $ajudaconfiguracao, $abordagemacervo, $idinstalacao);
			
			if (!$alterardados->execute())
				$erro = 1;
			$configuracoessave = 1;
		}
	}
endif;

if (isset($ajudaget)):
	if (!empty($idinstalacao)) {
		$checar = $sconexao->prepare("SELECT id FROM b_configuracoes WHERE idinstalacao=?");
		$checar->bind_param('s', $idinstalacao);
		$checar->execute();
		$checar->store_result();
		$checar->bind_result($id);
		$checar->fetch();
		
		if ($checar->num_rows != 0) {
			if (!$alterardados = $sconexao->query("UPDATE b_configuracoes SET ajudaconfiguracao=0 WHERE idinstalacao='$idinstalacao'"))
				$erro = 1;
			$configuracoesajudasave = 1;
		}
	}
endif; 

if (isset($_POST['apagar'])):
	$erro = 0;
	$configuracoesdelet = 1;
endif;

if (isset($apagarget)):
	$id = $apagarget;
	$campooculto = '<input name="apagarid" type="hidden" id="apagarid" value="' . $id . '">';
endif;

$checardados = $sconexao->prepare("SELECT id, nomebiblioteca, tipoinstalacao, descricao, emprestimos, emprestimosdias, emailbiblioteca, paginacao, ajudaconfiguracao, abordagemacervo FROM b_configuracoes WHERE idinstalacao=?");
$checardados->bind_param('s', $idinstalacao);
$checardados->execute();
$checardados->store_result();
$checardados->bind_result($idbase, $nomebiblioteca, $tipoinstalacao, $descricao, $emprestimos, $emprestimosdias, $email, $paginacao, $ajudaconfiguracao, $abordagemacervo);
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
<h1>Configurações Gerais</h1>
</div>
<div class="col-md-12">
<?php if($erro == 2): ?>
  <div class="row">
  <div class="col-md-offset-1 col-md-11">
	<h2>Parece que temos um problema</h2>
  <p>A instalação está corrompida, caso este seja o seu primeiro acesso, tente realizar novamente a instalação. Caso contrário, acesse a documentação para saber como resolver este problema.</p>
  <p class="divider"></p>
  </div>
  </div>
  <?php else: ?>
<?php if($erro == 1): ?>
<div class="alert alert-danger">
Não conseguimos salvar as configurações.
</div>
<?php else:?>
<?php if($configuracoessave == 1): ?>
<div class="alert alert-success">
Configurações salvas.
</div>
<?php endif; ?>
<?php if($configuracoesajudasave == 1): ?>
<div class="alert alert-success">
Configurações de ajuda alteradas.
</div>
<?php endif;?>
<?php if($configuracoesdelet == 1): ?>
<div class="col-md-offset-1 col-md-11">
<p>Que pena que você se foi...</p>
</div>
<?php endif;?>
<?php endif;?>
<form class="form-horizontal" role="form" method="post">
  <?php if (isset($apagarget)): 
  if (!isset($_POST['apagar'])) { ?>
  <legend>Apagar sua conta</legend>
  <div class="row">
  <div class="col-md-offset-1 col-md-11"><h2>Depois disso, não há voltas</h2>
  <p>Gostamos de ter a sua companhia, e mesmo caso você se vá, não deixaremos de compartilhar  ideais. Então, gostaríamos de saber se há algum motivo especial para a sua saída, não queremos discutir a nossa relação, mas nos ajudaria muito a melhorar e conhecer um pouco mais sobre nós mesmos. <a href="pagina_contato.php">Entre em contato</a>.</p>
  <p class="divider"></p>
  </div>
  </div>
  <div class="row">
  <div class="col-md-offset-1 col-md-11">
  <p><strong>Deseja realmente apagar a sua conta?</strong> Não há como desfazer esta ação, e todas as informações como o acervo, os usuários e os empréstimos serão perdidos. Por via das dúvidas, faça o <a href="configuracoes.php#exportar">download dos dados</a> antes de clicar no botão. <?php echo $campooculto;?></p>
  <p><button type="submit" name="apagar" class="btn btn-alerta btn-sm disabled">(EM BREVE) Sim, quero apagar minha conta</button></div>
  </div>
  <?php };
  else:
  
  if ($tipoinstalacao != 10):
  $file = "http://www.biblioteca.cc/check_versao.php";
  $data = @file_get_contents($file);
  
  if ($data!=FALSE && $data!=infoConfiguracao('versao')) {?>
  <legend>Atualização disponível</legend>
  <div class="row">
  <div class="col-md-offset-3 col-md-8">
  <p>Há uma nova versão do sistema disponível para download, comece a atualização lendo em nosso site como realizar toda a atualização, e quais foram as modificações da nova versão.</p>
  <p><a href="http://www.biblioteca.cc/check_atualizacao.php?atual=<?php echo infoConfiguracao('versao'); ?>" class="btn btn-warning btn-sm" role="button">Comece a atualização</a></p>
  </div>
  </div>
  <?php }
  endif; ?>
  <legend>Sobre a biblioteca </legend>

  <div class="form-group">
	<label for="nomebiblioteca" class="col-md-3 control-label">Nome da Biblioteca:</label>
	<div class="col-md-6">
	  <input type="text" class="form-control" id="nomebiblioteca" name="nomebiblioteca"value="<?php echo $nomebiblioteca; ?>"><input type="hidden" id="uid" name="uid"value="<?php echo $idbase; ?>">
	</div>
  </div>
  
  <div class="form-group">
	<label for="descricao" class="col-md-3 control-label">Descrição:</label>
	<div class="col-md-5">
	  <input type="text" class="form-control" id="descricao" name="descricao"value="<?php echo $descricao; ?>">
      <span class="help-block">Por exemplo, caso a biblioteca tenha um nome próprio, a descrição poderá conter o nome da instituição.</span>
	</div>
  </div>

  <?php if ($tipoinstalacao < 40): ?><div class="form-group">
	<label for="email" class="col-md-3 control-label">E-mail da Biblioteca:</label>
	<div class="col-md-5">
	  <input type="text" class="form-control" id="email" name="email"value="<?php echo $email; ?>">
	</div>
  </div>
  <?php endif; ?>
  
  <?php if ($tipoinstalacao > 10): ?>
  <legend>Tipo de instalação</legend>

  <div class="form-group">
	<div class="col-md-offset-3 col-md-8">
	<p>É importante que o tipo certo da instalação seja escolhido, caso você esteja realizando a sua própria instalação. Para conhecer todos os tipos de instalação e escolher a melhor, acesse <a href="http://biblioteca.cc" target="_blank">biblioteca.cc</a></p>
	<label for="tipoinstalacao" class="control-label">Tipo da instalação:</label>
<div class="radio">
  <label>
	<input type="radio" name="tipoinstalacao" id="tipoinstalacao1" value="10" disabled>
	Sem instalação, utilizando o app web
  </label>
</div>
<div class="radio">
  <label>
	<input type="radio" name="tipoinstalacao" id="tipoinstalacao2" value="20"<?php if ($tipoinstalacao == 20) echo " checked";?>>
	Instalação web, em site próprio
  </label>
</div>
<div class="radio">
  <label>
	<input type="radio" name="tipoinstalacao" id="tipoinstalacao2" value="40"<?php if ($tipoinstalacao == 40) echo " checked";?>>
	Instalação local, com ou sem internet
  </label>
</div>
	</div>
  </div>
  <?php endif; ?>
 
 <legend>Personalizações</legend>

  <div class="form-group">
	<label for="paginacao" class="col-md-3 control-label">Paginação Administrativa:</label>
	<div class="col-md-2">
	  <input type="number" class="form-control" id="paginacao" name="paginacao" min="5" value="<?php echo $paginacao; ?>">
	</div>
    <div class="col-md-offset-3 col-md-8">
	<span class="help-block">Registros nas listas administrativas de usuários, acervo e empréstimos.</span></div>
  </div>
 
  <div class="form-group">
    <label for="ajudaconfiguracao" class="col-md-3 control-label">Mostrar ajuda?</label>
	<div class="col-md-5">
<div class="radio">
  <label>
	<input type="radio" name="ajudaconfiguracao" id="ajudaconfiguracao1" value="1"<?php if ($ajudaconfiguracao==1) echo " checked";?>>
	Sim
  </label>
</div>
<div class="radio">
  <label>
	<input type="radio" name="ajudaconfiguracao" id="ajudaconfiguracao2" value="0"<?php if ($ajudaconfiguracao==0) echo " checked";?>>
	Não
  </label>
</div>
	</div>
</div>
 
  <div class="form-group">
    <label for="abordagemacervo" class="col-md-3 control-label">Abordagem do Acervo:</label>
	<div class="col-md-7">
<div class="radio">
  <label>
	<input type="radio" name="abordagemacervo" id="abordagemacervo2" value="0"<?php if ($abordagemacervo==0) echo " checked";?>>
	Não quero diferenciar exemplares, assim, somarei ítens iguais como Quantidade. <em>(Padrão)</em>
  </label>
</div>
<div class="radio">
  <label>
	<input type="radio" name="abordagemacervo" id="abordagemacervo1" value="1"<?php if ($abordagemacervo==1) echo " checked";?>>
	Quero diferenciar exemplares, marcando cada ítem com o seu número de exemplar.   
  </label>
</div>
	</div>
</div>
 
	<a name="emprestimos" id="emprestimos"></a><legend>Empréstimos</legend>
	
  <div class="form-group">
    <label for="emprestimos" class="col-md-3 control-label">Aceita empréstimos?</label>
	<div class="col-md-5">
<div class="radio">
  <label>
	<input type="radio" name="emprestimos" id="emprestimos1" value="1"<?php if ($emprestimos==1) echo " checked";?>>
	Sim
  </label>
</div>
<div class="radio">
  <label>
	<input type="radio" name="emprestimos" id="emprestimos2" value="0"<?php if ($emprestimos==0) echo " checked";?>>
	Não
  </label>
</div>
	</div>
</div>

  <div class="form-group">
	<label for="emprestimosdias" class="col-md-3 control-label">Dias para devolução:</label>
	<div class="col-md-2">
	  <input type="number" class="form-control" id="emprestimosdias" name="emprestimosdias" min="0" value="<?php echo $emprestimosdias; ?>">
	</div>
  </div>

  <div class="form-group">
	<div class="col-md-offset-3 col-md-5">
	  <button type="submit" name="salvar" class="btn btn-success">Salvar alterações</button>
	</div>
  </div>
  <p>&nbsp;</p>

	<legend>Classificação do acervo</legend>
  <div class="form-group">
	<div class="col-md-offset-3 col-md-8">
    <p>Por favor, antes de começar a editar a classificação do acervo, você deverá salvar as alterações que realizou nas configurações clicando no botão [Salvar alterações].</p>
<?php 
$sql = "SELECT uid, classificacao FROM b_itens_classificacao WHERE idinstalacao='$idinstalacao' ORDER BY uid ASC";
if(!$classificacaoacervo = $sconexao->query($sql)){ 
	die('<pre>Desculpe, mas aconteceu um erro [' . $sconexao->error . ']</pre>'); 
}
if ($classificacaoacervo->num_rows != 0){
	?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-condensed">
  <tr>
    <th scope="col" width="65%">Categoria</th>
    <th scope="col"><a data-toggle="modal" href="#classificacaoAcervoMod" class="btn btn-success btn-xs">Adicionar</a></th>
  </tr>
<?php
	while ($row = $classificacaoacervo->fetch_assoc()){ 
?>
  <tr>
    <td><?php echo stripslashes($row['classificacao']); ?></td>
    <td><a data-toggle="modal" href="#classificacaoAcervoMod" class="btn btn-default btn-xs classificacaoEditar" data-idcategoria="<?php echo $row['uid']; ?>" data-categoria="<?php echo stripslashes($row['classificacao']); ?>">Alterar</a>&nbsp;<a data-toggle="modal" href="#classificacaoAcervoMod" class="btn btn-alerta btn-xs classificacaoApagar" data-idcategoria="<?php echo $row['uid']; ?>" data-categoria="<?php echo stripslashes($row['classificacao']); ?>">Apagar</a></td>
  </tr>
<?php } ?>
</table>
<?php } else { ?>
<p>Nenhuma categoria encontrada, e isso não é bom, com as classificações a sua organização ficará melhor...</p><p><a data-toggle="modal" href="#classificacaoAcervoMod" class="btn btn-default btn-sm">Adicione a primeira categoria</a></p>
<?php } ?>
  </div>
  </div>

  <a name="exportar" id="exportar"></a><legend>Exportar dados</legend>
  <div class="form-group">
	<div class="col-md-offset-3 col-md-8">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-condensed">
  <tr>
  	<td width="65%">Acervo da Biblioteca</td>
    <td><a href="inc/configuracoes_exportar.php?t=<?php echo sha1("itens$sufixo_de_seguranca"); ?>&e=xml" class="btn btn-default btn-xs" role="button">XML</a> <a href="inc/configuracoes_exportar.php?t=<?php echo sha1("itens$sufixo_de_seguranca"); ?>&e=csv" class="btn btn-default btn-xs" role="button">CSV</a> <a href="inc/configuracoes_exportar.php?t=<?php echo sha1("itens$sufixo_de_seguranca"); ?>&e=sql" class="btn btn-default btn-xs" role="button">MySQL</a></td>
  </tr>
  <tr>
  	<td>Usuários</td>
    <td><a href="inc/configuracoes_exportar.php?t=<?php echo sha1("usuarios$sufixo_de_seguranca"); ?>&e=xml" class="btn btn-default btn-xs" role="button">XML</a> <a href="inc/configuracoes_exportar.php?t=<?php echo sha1("usuarios$sufixo_de_seguranca"); ?>&e=csv" class="btn btn-default btn-xs" role="button">CSV</a> <a href="inc/configuracoes_exportar.php?t=<?php echo sha1("usuarios$sufixo_de_seguranca"); ?>&e=sql" class="btn btn-default btn-xs" role="button">MySQL</a></td>
  </tr>
  <tr>
    <td>Empréstimos</td>
    <td><a href="inc/configuracoes_exportar.php?t=<?php echo sha1("emprestimos$sufixo_de_seguranca"); ?>&amp;e=xml" class="btn btn-default btn-xs" role="button">XML</a> <a href="inc/configuracoes_exportar.php?t=<?php echo sha1("emprestimos$sufixo_de_seguranca"); ?>&amp;e=csv" class="btn btn-default btn-xs" role="button">CSV</a> <a href="inc/configuracoes_exportar.php?t=<?php echo sha1("emprestimos$sufixo_de_seguranca"); ?>&amp;e=sql" class="btn btn-default btn-xs" role="button">MySQL</a></td>
  </tr>
  <tr>
    <td>Classificação do acervo</td>
    <td><a href="inc/configuracoes_exportar.php?t=<?php echo sha1("itens_classificacao$sufixo_de_seguranca"); ?>&amp;e=xml" class="btn btn-default btn-xs" role="button">XML</a> <a href="inc/configuracoes_exportar.php?t=<?php echo sha1("itens_classificacao$sufixo_de_seguranca"); ?>&amp;e=csv" class="btn btn-default btn-xs" role="button">CSV</a> <a href="inc/configuracoes_exportar.php?t=<?php echo sha1("itens_classificacao$sufixo_de_seguranca"); ?>&amp;e=sql" class="btn btn-default btn-xs" role="button">MySQL</a></td>
  </tr>
  <tr>
  	<td>Configurações</td>
    <td><a href="inc/configuracoes_exportar.php?t=<?php echo sha1("configuracoes$sufixo_de_seguranca"); ?>&e=xml" class="btn btn-default btn-xs" role="button">XML</a> <a href="inc/configuracoes_exportar.php?t=<?php echo sha1("configuracoes$sufixo_de_seguranca"); ?>&e=csv" class="btn btn-default btn-xs" role="button">CSV</a> <a href="inc/configuracoes_exportar.php?t=<?php echo sha1("configuracoes$sufixo_de_seguranca"); ?>&e=sql" class="btn btn-default btn-xs" role="button">MySQL</a></td>
  </tr>
</table>
	</div>
  </div>
    
  <?php if ($tipoinstalacao == 10) { ?>
  <legend>Apagar sua conta</legend>
  <div class="form-group">
	<div class="col-md-offset-3 col-md-8">
	  <p>É triste saber que talvez você se vá, mas caso você realmente queira apagar a sua conta, dê os primeiros passos para o adeus clicando no botão abaixo.</p>
	  <p><a href="configuracoes.php?apagar=s" class="btn btn-alerta btn-sm" role="button">Quero começar a apagar minha conta</a></p>
	</div>
  </div>
  <?php } ?>
  
  <?php endif; ?>
</form>

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
          <button type="submit" class="btn btn-success btn-sm" id="acervosalvarMod" data-loading-text="Executando...">Adicionar Categoria</button>
        </div></form>
      </div>
    </div>
  </div>
  
</div>
<?php endif; ?>
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
<?php 
/**
 * Gera a página de consulta ao "Acervo"
 *
 * Esta página permite a busca e a listagem de dados adicionados ao acervo
 */ 
include("login_acesso.php");
include_once("instalacao.php");
include_once("inc/conexao.php");
include_once("inc/funcoes.php");

$idinstalacao	= $_SESSION['idinstalacao'];

$o = $_GET['ordem']; 
if ($o == 'titulo')
	$or = ' ORDER BY titulo ASC';
elseif ($o == 'autor')
	$or = ' ORDER BY autor ASC';
elseif ($o == 'numero')
	$or = ' ORDER BY uid ASC';
else
	$or = ' ORDER BY titulo ASC';

$q = $_GET['buscarpor'];
if (isset($q))
	$qr = " AND ((titulo like '%$q%') OR (autor like '%$q%') OR (assuntos like '%$q%'))";
	
include_once("inc/cabecalho.php");
include_once("inc/menu-cabecalho.php"); ?>
<div class="container painel">
<div class="row">
<?php include_once("inc/menu-painel.php"); ?>
<div class="col-md-9">
<div class="page-header">
<h1>Acervo da Biblioteca</h1>
</div>
<div class="col-md-12">
<?php if (infoConfiguracao('ajuda')=='1'): ?>
<div class="panel panel-default">
  <div class="panel-body">
	<h4>Ajuda</h4>
	<p>Nesta página você terá a listagem geral do acervo cadastrado. Na coluna Quantidade, você poderá ver a relação entre o número de empréstimos em aberto / a quantidade cadastrada do ítem.</p>
	<p>Ao clicar no botão [emprestar], o mesmo será automaticamente linkado à página de empréstimo facilitando a ação. Na seta ao lado do botão [emprestar] você terá as opções de alteração e exclusão de cada ítem.</p>
	<p>Clique no botão [adicionar] para adicionar um novo ítem ao acervo, no momento em que desejar.</p>
	<p><a href="configuracoes.php?ajuda=0" class="btn btn-default btn-xs">Quero ocultar toda a ajuda</a></p>
  </div>
</div>
<?php endif; ?>
<?php 
$sql = "SELECT titulo, autor, qntd, localizacao, numexemplar, uid FROM b_itens WHERE ((idinstalacao='$idinstalacao')$qr)" . $or . paginacao();
if(!$acervo = $sconexao->query($sql)){ 
	die('<pre>Desculpe, mas aconteceu um erro [' . $sconexao->error . ']</pre>'); 
}
if ($acervo->num_rows != 0){
	
$sqlpg = "SELECT uid FROM b_itens WHERE ((idinstalacao='$idinstalacao')$qr)";
if (!$acervo_conta = $sconexao->query($sqlpg)){ 
	die('<pre>Desculpe, mas aconteceu um erro [' . $sconexao->error . ']</pre>'); 
}
$acervo_reg = $acervo_conta->num_rows;
?>
<p><a href="acervo_a.php" class="btn btn-success btn-sm">Adicionar</a></p>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-striped table-hover">
<tr>
<th width="5%" scope="col"><a href="?ordem=numero">#</a> <?php if ($o == 'numero') echo ' <span class="glyphicon glyphicon-arrow-down icon-pequeno"></span>'; ?></th>
<th scope="col"><a href="?ordem=titulo">Título</a> <?php if ($o == 'titulo') echo ' <span class="glyphicon glyphicon-arrow-down icon-pequeno"></span>'; ?> / <a href="?ordem=autor">Autor</a> <?php if ($o == 'autor') echo ' <span class="glyphicon glyphicon-arrow-down icon-pequeno"></span>'; ?></th>
<th width="14%" scope="col">Localização</th>
<th width="5%" scope="col">Qnt.</th>
<th width="22%">&nbsp;</th>
</tr>
<?php while ($row = $acervo->fetch_assoc()){ ?>
<tr>
<td><?php echo $row['uid']; ?><?php if (infoConfiguracao('abordagemacervo')=='1') {
	if ($row['numexemplar'])
		echo '/' . stripslashes($row['numexemplar']); } ?></td>
<td><?php echo stripslashes($row['titulo']); ?><br><small><?php echo stripslashes($row['autor']); ?></small></td>
<td><?php echo stripslashes($row['localizacao']); ?></td>
<td><?php echo Disponibilidade($row['uid']) . '/' . $row['qntd']; ?></td>
<td class="text-center">
<div class="btn-group">
  <a href="emprestimos_a.php?acervo=<?php echo $row['uid']; ?>" class="btn btn-default btn-sm">Emprestar</a>
  <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown">
	<span class="caret"></span>
  </button>
  <ul class="dropdown-menu text-left" role="menu">
    <li><a href="emprestimos.php?e_acervo=<?php echo $row['uid']; ?>">Empréstimos</a></li>
	<li class="divider"></li>
	<li><a href="acervo_a.php?alterar=<?php echo $row['uid']; ?>">Alterar</a></li>
	<li><a href="acervo_a.php?apagar=<?php echo $row['uid']; ?>">Apagar</a></li>
  </ul>
</div>
</td>
</tr>
<?php } ?>
</table>
<?php echo paginacao('p',$acervo_reg); ?>
<?php } else { ?>
<div class="panel panel-default">
  <div class="panel-body">
	<h4>Ainda não encontramos nenhum ítem.</h4>
	<p>O acervo está relacionado a todos os livros, revistas, periódicos, dvds, coleções, e outros materiais disponíveis na biblioteca.</p>
	<p><a href="acervo_a.php" class="btn btn-success btn-sm">Adicione o primeiro ítem ao acervo</a></p>
  </div>
</div>
<?php } ?>
</div>
</div>
</div>
</div>
<?php include_once("inc/rodape.php"); ?>
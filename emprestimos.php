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
	$or = ' ORDER BY a.titulo ASC';
elseif ($o == 'autor')
	$or = ' ORDER BY a.autor ASC';
elseif ($o == 'item')
	$or = ' ORDER BY a.uid ASC';
elseif ($o == 'usuario')
	$or = ' ORDER BY u.uid ASC';
elseif ($o == 'devolucao')
	$or = ' ORDER BY datadevolucao ASC';
else
	$or = ' ORDER BY datadevolucao ASC';
	
$f = $_GET['filtro']; 
if (empty($f) || $f==0)
	$fr = " AND (e.status=0)";
if (!empty($f))
	$fr = " AND (e.status=$f)";
	
$qa = $_GET['e_acervo']; 
$qu = $_GET['e_usuario'];
if (!empty($qa)) {
	if (is_numeric($qa))
	$fr = " AND (e.status=0) AND (a.uid LIKE '%$qa%') ";
	else
	$fr = " AND (e.status=0) AND ((a.titulo LIKE '%$qa%') OR (a.autor LIKE '%$qa%')) ";
} else {
if (!empty($qu)) {
	if (is_numeric($qu))
	$fr = " AND (e.status=0) AND (e.idusuario LIKE '%$qu%') ";
	else
	$fr = " AND (e.status=0) AND (u.nome LIKE '%$qu%') ";
}
}

include_once("inc/cabecalho.php");
include_once("inc/menu-cabecalho.php"); ?>
<div class="container painel">
<div class="row">
<?php include_once("inc/menu-painel.php"); ?>
<div class="col-md-9">
<div class="page-header">
<h1>Empréstimos</h1></div><div class="col-md-12">
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
	<p>Nesta página você terá a listagem de todos os empréstimos cadastrados na biblioteca. Um empréstimo é a retirada de um ítem por uma pessoa, leia as <a href="pagina_dicasdeuso.php">Dicas de Uso</a>, para conhecer algumas recomendações que irão facilitar no momento da devolução dos ítens.</p>
	<p>Lembre-se sempre que nossa metodologia diz que &quot;um usuário está emprestando um ítem&quot;, e no momento da devolução este usuário estará devolvendo um ítem. Sendo importante marcar não somente qual ítem foi devolvido, mas sim quem retirou o ítem.</p>
	<p>Ao clicar no botão [empréstimos em aberto], você filtrará a listagem apenas com os empréstimos que ainda não foram devolvidos. Ítens em atraso estarão marcados como atrasados nesta listagem. Já no botão [devolvidos] você filtrará todas as devoluções realizadas.</p>
	<p>Cada ítem da listagem terá o primeiro nome do usuário que realizou o empréstimo, o ítem emprestado, a data de previsão de devolução. Além do botão [devolver] que levará para a página de confirmação da devolução. Na seta ao lado deste botão, você terá opções de renovação da data de devolução, alteração ou exclusão do empréstimo.</p>
	<p>Ao clicar sobre o nome do usuário, você filtrará todos os ítens que este usuário tem em aberto. Outra forma de filtrar a listagem é clicando no menu lateral, sobre o link &quot;Devolução de Item&quot;, que irá lhe apresentar uma busca por ítens em aberto.</p>
	<p>Clique no botão [adicionar empréstimo] para adicionar um novo empréstimo, no momento em que desejar.</p>
<p><a href="configuracoes.php?ajuda=0" class="btn btn-default btn-xs">Quero ocultar toda a ajuda</a></p>
  </div>
</div>
<?php endif; ?>
<p><div class="btn-group"><a href="emprestimos.php?filtro=0<?php if (!empty($o)) echo "&ordem=$o"; ?>" class="btn btn-default btn-sm<?php if ($f==0) echo ' active';?>">Empréstimos em Aberto <span class="badge"><?php echo Estatisticas('emprestimos-abertos') ?></span></a><a href="emprestimos.php?filtro=1<?php if (!empty($o)) echo "&ordem=$o"; ?>" class="btn btn-default btn-sm<?php if ($f==1) echo ' active';?>">Devolvidos</a></div>
<?php
$sql = "SELECT e.uid, e.idusuario, e.iditem, e.datadevolucao, e.status, a.numexemplar, a.titulo, a.autor, u.nome FROM b_emprestimos e INNER JOIN b_itens a ON e.iditem = a.uid INNER JOIN b_usuarios u ON e.idusuario = u.uid WHERE (((u.idinstalacao='$idinstalacao') AND (a.idinstalacao='$idinstalacao') AND (e.idinstalacao='$idinstalacao'))$fr)" . $or . paginacao();
if(!$emprestimos = $sconexao->query($sql)){ 
	die('<pre>Desculpe, mas aconteceu um erro [' . $sconexao->error . ']</pre>'); 
}
if ($emprestimos->num_rows == 0)
echo '</p>';
if ($emprestimos->num_rows != 0){
	
$sqlpg = "SELECT e.uid FROM b_emprestimos e INNER JOIN b_itens a ON e.iditem = a.uid INNER JOIN b_usuarios u ON e.idusuario = u.uid WHERE (((u.idinstalacao='$idinstalacao') AND (a.idinstalacao='$idinstalacao') AND (e.idinstalacao='$idinstalacao'))$fr)";
if (!$emprestimo_conta = $sconexao->query($sqlpg)){ 
	die('<pre>Desculpe, mas aconteceu um erro [' . $sconexao->error . ']</pre>'); 
}
$emprestimos_reg = $emprestimo_conta->num_rows;
?> <a href="emprestimos_a.php" class="btn btn-success btn-sm">Adicionar empréstimo</a></p>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-striped table-hover">
<tr>
<th width="11%" scope="col"><a href="?ordem=usuario">Usuário</a> <?php if ($o == 'usuario') echo ' <span class="glyphicon glyphicon-arrow-down icon-pequeno"></span>'; ?></th>
<th width="8%" scope="col"><a href="?ordem=item">Ítem</a>
  <?php if ($o == 'item') echo ' <span class="glyphicon glyphicon-arrow-down icon-pequeno"></span>'; ?></th>
<th scope="col"><a href="?ordem=titulo">Título</a> <?php if ($o == 'titulo') echo ' <span class="glyphicon glyphicon-arrow-down icon-pequeno"></span>'; ?> / <a href="?ordem=autor">Autor</a> <?php if ($o == 'autor') echo ' <span class="glyphicon glyphicon-arrow-down icon-pequeno"></span>'; ?></th>
<th width="14%" scope="col"><a href="?ordem=devolucao"><?php if ($f==1) echo ' Devolvido'; else echo 'Devolução';?></a> <?php if ($o == 'devolucao') echo ' <span class="glyphicon glyphicon-arrow-down icon-pequeno"></span>'; ?></th>
<th width="22%">&nbsp;</th>
</tr>
<?php while ($row = $emprestimos->fetch_assoc()){ ?>
<tr>
<td><a href="emprestimos.php?e_usuario=<?php echo $row['idusuario']; ?>" data-toggle="tooltip" title="<?php $nome = bdDados('usuario-nome', $row['idusuario']); echo $nome; ?>" class="usuarioTabela" data-placement="right"><?php $nome = explode( ' ', $nome ); echo $nome[0]; ?></a></td>
<td><?php echo '#' . $row['iditem']; ?><?php if (infoConfiguracao('abordagemacervo')=='1') {
	if ($row['numexemplar'])
		echo '/' . stripslashes($row['numexemplar']); } ?></td>
<td><?php echo stripslashes($row['titulo']); ?> <br><small><?php echo stripslashes($row['autor']); ?></small></td>
<td><?php 
if ($row['status']==0) {
	echo padraoDatas($row['datadevolucao'],'e',0);
	if (strtotime($row['datadevolucao']) < strtotime("-1 day") && $row['status'] == 0)
		echo '<br><span class="label label-danger">Atrasado</span>';
} else {
	echo padraoDatas($row['dataentrada'],'e',0);
}?></td>
<td class="text-center">
  <?php if ($row['status']==0) { ?><div class="btn-group">
    <a href="emprestimos_a.php?devolver=<?php echo $row['uid']; ?>" class="btn btn-default btn-sm">Devolver</a>
    <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown">
      <span class="caret"></span>
      </button>
    <ul class="dropdown-menu text-left" role="menu">
      <li><a href="emprestimos_a.php?renovar=<?php echo $row['uid']; ?>">Renovar</a></li>
      <li class="divider">&nbsp;</li>
      <li><a href="emprestimos_a.php?alterar=<?php echo $row['uid']; ?>">Alterar</a></li>
      <li><a href="emprestimos_a.php?apagar=<?php echo $row['uid']; ?>">Apagar</a></li>
      </ul>
  </div><?php } else { ?><a href="#" class="btn btn-default btn-sm disabled">Devolvido</a><?php } ?>
</td>
</tr>
<?php } ?>
</table>
<?php echo paginacao('p',$emprestimos_reg); ?>
<?php } else { ?>
<div class="panel panel-default">
  <div class="panel-body">
	<h4>Não encontramos nenhum registro de empréstimo.</h4>
	<p>O sistema está configurado para aceitar empréstimos, porém, não encontramos nenhum registro de empréstimos conforme o filtro solicitado.</p>
	<p><a href="emprestimos_a.php" class="btn btn-success btn-sm">Faça um novo empréstimo</a></p>
  </div>
</div>
<?php } ?>
</div>
<?php endif; ?>
</div>
</div>
</div>
<script>
$('.usuarioTabela').tooltip();
</script>
<?php include_once("inc/rodape.php"); ?>
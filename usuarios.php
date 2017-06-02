<?php 
/**
 * Gera a página de consulta aos "Usuários"
 *
 * Esta página permite a busca e a listagem de dados adicionados aos usuários
 */ 
include("login_acesso.php");
include_once("instalacao.php"); 
include_once("inc/conexao.php");
include_once("inc/funcoes.php");

$idinstalacao	= $_SESSION['idinstalacao'];

$o = $_GET['ordem']; 
if ($o == 'nome')
	$or = ' ORDER BY nome ASC';
elseif ($o == 'id')
	$or = ' ORDER BY uid ASC';
else
	$or = ' ORDER BY nome ASC';

include_once("inc/cabecalho.php");
include_once("inc/menu-cabecalho.php"); ?>
<div class="container painel">
<div class="row">
<?php include_once("inc/menu-painel.php"); ?>
<div class="col-md-9">
<div class="page-header">
<h1>Usuários da Biblioteca</h1>
</div>
<div class="col-md-12">
<?php if (infoConfiguracao('ajuda')=='1'): ?>
<div class="panel panel-default">
  <div class="panel-body">
	<h4>Ajuda</h4>
	<p> Nesta página você terá a listagem de todos os usuários cadastrados na biblioteca. Um usuário é uma pessoa que possa retirar ítens da biblioteca. Na coluna referência você verá alguma informação de referência sobre este usuário, como por exemplo a atividade na instituição (se for um professor ou colaborador), aluno de uma turma na escola, conforme os regimentos de cada biblioteca.</p>
	<p>Ao clicar no botão [empréstimos], você será levado à lista de empréstimos em aberto, filtrando apenas os empréstimos deste usuário em questão. Na seta ao lado do botão [empréstimos] você terá as opções de alteração e exclusão de cada usuário, além de um link 'emprestar' com a mesma função de facilitar a ação, como no acervo.</p>
	<p>Clique no botão [adicionar] para adicionar um novo usuário, no momento em que desejar.</p>
<p><a href="configuracoes.php?ajuda=0" class="btn btn-default btn-xs">Quero ocultar toda a ajuda</a></p>
  </div>
</div>
<?php endif; ?>
<?php 
$sql = "SELECT nome, referencia, uid FROM b_usuarios WHERE idinstalacao='$idinstalacao'" . $or . paginacao();
if (!$usuario = $sconexao->query($sql)){ 
	die('<pre>Desculpe, mas aconteceu um erro [' . $sconexao->error . ']</pre>'); 
}

if ($usuario->num_rows != 0){

$sqlpg = "SELECT uid FROM b_usuarios WHERE idinstalacao='$idinstalacao'";
if (!$usuario_conta = $sconexao->query($sqlpg)){ 
	die('<pre>Desculpe, mas aconteceu um erro [' . $sconexao->error . ']</pre>'); 
}
$usuario_reg = $usuario_conta->num_rows;

?>
<p><a href="usuarios_a.php" class="btn btn-success btn-sm">Adicionar</a></p>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-striped table-hover">
<tr>
<th width="5%" scope="col">#</th>
<th scope="col"><a href="?ordem=titulo">Nome</a> <?php if ($o == 'titulo') echo ' <span class="glyphicon glyphicon-arrow-down icon-pequeno"></span>'; ?> </th>
<th scope="col">Referência</th>
<th width="22%">&nbsp;</th>
</tr>
<?php while ($row = $usuario->fetch_assoc()){ ?>
<tr>
<td><?php echo $row['uid']; ?></td>
<td><?php echo stripslashes($row['nome']); ?></td>
<td><?php echo stripslashes($row['referencia']); ?></td>
<td class="text-center">
  <div class="btn-group">
	<a href="emprestimos.php?e_usuario=<?php echo $row['uid']; ?>" class="btn btn-default btn-sm">Empréstimos</a>
	<button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown">
	  <span class="caret"></span>
	  </button>
	<ul class="dropdown-menu text-left" role="menu">
	  <li><a href="usuarios_a.php?alterar=<?php echo $row['uid']; ?>">Alterar</a></li>
	  <li><a href="usuarios_a.php?apagar=<?php echo $row['uid']; ?>">Apagar</a></li>
	  <li class="divider">&nbsp;</li>
      <li><a href="emprestimos_a.php?usuario=<?php echo $row['uid']; ?>">Emprestar</a></li>
	  </ul>
  </div>
</td>
</tr>
<?php } ?>
</table>
<?php echo paginacao('p',$usuario_reg); ?>
<?php } else { ?>
<div class="panel panel-default">
  <div class="panel-body">
	<h4>Ainda não encontramos nenhum usuário.</h4>
	<p>Os usuários da biblioteca estão relacionados às pessoas que frequentam a biblioteca, e podem retirar ítens do acervo.</p>
	<p><a href="usuarios_a.php" class="btn btn-success btn-sm">Adicione o primeiro usuário</a></p>
  </div>
</div>
<?php } ?>
</div>
</div>
</div>
</div>
<?php include_once("inc/rodape.php"); ?>
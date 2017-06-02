<?php 
/**
 * Gera lista para o modal de empréstimo
 *
 * Esta página permite a busca e a listagem de dados de usuários e acervo
 */ 
include("../login_acesso.php");
include_once("../instalacao.php"); 
include_once("conexao.php");
include_once("funcoes.php");

$t				= $_POST['t'];
$idinstalacao	= $_SESSION['idinstalacao'];
?>
<?php if ($t=='a'): 

$q = $_POST['q'];
if (isset($q))
	if (is_numeric($q))
	$qr = " AND ((uid like '%$q%') OR (titulo like '%$q%'))";
	else
	$qr = " AND ((titulo like '%$q%') OR (autor like '%$q%') OR (assuntos like '%$q%'))";
	
$sql = "SELECT uid, titulo, qntd, autor, numexemplar FROM b_itens WHERE ((idinstalacao='$idinstalacao')$qr)";
if (!$acervo = $sconexao->query($sql)){ 
	die('<pre>Desculpe, mas aconteceu um erro [' . $sconexao->error . ']</pre>'); 
}
if ($acervo->num_rows != 0){ ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-hover">
  <tr>
    <th scope="col" width="5%">#</th>
    <th scope="col" width="75%">Título / Autor</th>
    <th scope="col" width="5%">&nbsp;</th>
    <th scope="col">&nbsp;</th>
  </tr>
  <?php while ($row = $acervo->fetch_assoc()){ ?><tr>
    <td><?php echo $row['uid']; ?><?php if (infoConfiguracao('abordagemacervo')=='1') {
	if ($row['numexemplar'])
		echo '/' . stripslashes($row['numexemplar']); } ?></td>
    <td><?php echo stripslashes($row['titulo']); ?><br><small><?php echo stripslashes($row['autor']); ?></small></td>
    <td><?php echo Disponibilidade($row['uid']) . '/' . $row['qntd']; ?></td>
    <td><a href="#emprestimo" class="btn btn-default btn-sm btnselecao" data-acervoid="<?php echo $row['uid']; ?>" data-acervo="<?php echo stripslashes($row['titulo']); ?>">Selecionar</a></td>
  </tr><?php } ?>
</table>
<script>
$(".btnselecao").on("click", function () {
$('#acervo').val($(this).data('acervo'));
$('#acervoid').val($(this).data('acervoid'));
$('#selecaoMod').modal('hide');
});
</script>
<?php } else { ?>
<p>Nenhum item encontrado</p>
<?php } 
elseif ($t=='u'): 

$q = $_POST['q'];
if (isset($q)) {
	if (is_numeric($q))
	$qr = " AND (uid like '%$q%')";
	else
	$qr = " AND (nome like '%$q%')";
}

$sql = "SELECT nome, referencia, uid FROM b_usuarios WHERE ((idinstalacao='$idinstalacao')$qr)";
if (!$usuario = $sconexao->query($sql)){ 
	die('<pre>Desculpe, mas aconteceu um erro [' . $sconexao->error . ']</pre>'); 
}
if ($usuario->num_rows != 0){
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-hover">
  <tr>
    <th scope="col" width="5%">#</th>
    <th scope="col" width="75%">Nome do Usuário</th>
    <th scope="col">&nbsp;</th>
  </tr>
  <?php while ($row = $usuario->fetch_assoc()){ ?><tr>
    <td><?php echo $row['uid']; ?></td>
    <td><?php echo stripslashes($row['nome']); ?></td>
    <td><a href="#emprestimo" class="btn btn-default btn-sm btnselecao" data-usuarioid="<?php echo $row['uid']; ?>" data-usuario="<?php echo stripslashes($row['nome']); ?>">Selecionar</a></td>
  </tr><?php } ?>
</table>
<script>
$(".btnselecao").on("click", function () {
$('#usuario').val($(this).data('usuario'));
$('#usuarioid').val($(this).data('usuarioid'));
$('#selecaoMod').modal('hide');
});
</script>
<?php } else { ?>
<p>Nenhum usuário encontrado</p>
<?php } 
endif; ?>
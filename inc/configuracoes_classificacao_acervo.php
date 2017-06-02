<?php
/**
 * Gerenciamento da classificação do acervo
 *
 * Esta página é chamada por modal + ajax e deve retornar apenas
 * 'ok' para sucesso de envio dos dados.
 */ 
include("../login_acesso.php");
include_once("../instalacao.php");
include_once("conexao.php");
include_once("funcoes.php");

$idinstalacao	= $_POST['idinstalacao'];
$categoria		= $_POST['categoria'];
$alterarid		= $_POST['alterarid'];
$apagarid		= $_POST['apagarid'];

if (isset($categoria) && isset($idinstalacao) && empty($alterarid) && empty($apagarid)):
	$uid = uID('itens_classificacao');

	$checar = $sconexao->prepare("SELECT uid FROM b_itens_classificacao WHERE classificacao=? AND idinstalacao=?");
	$checar->bind_param('ss', $categoria, $idinstalacao);
	$checar->execute();
	$checar->store_result();
	$checar->bind_result($id);
	$checar->fetch();

	if ($checar->num_rows == 0) {
		$adicionar = $sconexao->prepare("INSERT INTO b_itens_classificacao (uid, classificacao, idinstalacao) VALUES (?,?,?)");
		$adicionar->bind_param('iss', $uid, $categoria, $idinstalacao);

		if ($adicionar->execute())
		echo 'ok';
		
	}
endif;

if (isset($categoria) && isset($idinstalacao) && !empty($alterarid) && empty($apagarid)):
	$checar = $sconexao->prepare("SELECT uid FROM b_itens_classificacao WHERE classificacao=? AND idinstalacao=? AND uid=?");
	$checar->bind_param('ssi', $categoria, $idinstalacao, $alterarid);
	$checar->execute();
	$checar->store_result();
	$checar->bind_result($id);
	$checar->fetch();
	
	if ($checar->num_rows == 0) {
		$alterar = $sconexao->prepare("UPDATE b_itens_classificacao SET classificacao=?, idinstalacao=? WHERE uid=?");
		$alterar->bind_param('ssi', $categoria, $idinstalacao, $alterarid);

		if ($alterar->execute())
		echo 'ok';
		
	}
endif;

if (isset($idinstalacao) && !empty($apagarid) && empty($alterarid)):
	$checar = $sconexao->prepare("SELECT id FROM b_itens_classificacao WHERE idinstalacao=? AND uid=?");
	$checar->bind_param('si', $idinstalacao, $apagarid);
	$checar->execute();
	$checar->store_result();
	$checar->bind_result($id);
	$checar->fetch();
	
	if ($checar->num_rows != 0) {
		$apagar = $sconexao->prepare("DELETE FROM b_itens_classificacao WHERE idinstalacao=? AND uid=?");
		$apagar->bind_param('si', $idinstalacao, $apagarid);

		if ($apagar->execute())
		echo 'ok';
		
	}
endif;
?>

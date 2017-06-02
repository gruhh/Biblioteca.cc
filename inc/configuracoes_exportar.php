<?php
include("../login_acesso.php");
include_once("../instalacao.php");
include_once("conexao.php");
include_once("funcoes.php");

$tipo = $_GET['e'];
$tabela = $_GET['t'];
$idinstalacao = $_SESSION['idinstalacao'];

	$or = ' ORDER BY uid ASC';
if ($tabela == sha1("usuarios$sufixo_de_seguranca")) {
	$tabela = 'usuarios';
	$reg = 'usuario';
} elseif ($tabela == sha1("itens$sufixo_de_seguranca")) {
	$tabela = 'itens';
	$reg = 'item';
} elseif ($tabela == sha1("emprestimos$sufixo_de_seguranca")) {
	$tabela = 'emprestimos';
	$reg = 'emprestimo';
} elseif ($tabela == sha1("configuracoes$sufixo_de_seguranca")) {
	$tabela = 'configuracoes';
	$reg = 'configuracao';
	$or = '';
} elseif ($tabela == sha1("itens_classificacao$sufixo_de_seguranca")) {
	$tabela = 'itens_classificacao';
	$reg = 'classificacao';
} else
	die('<pre>Aconteceu um erro.</pre>');
	
$sql = "SELECT * FROM b_$tabela WHERE idinstalacao='$idinstalacao'$or";

if (!$executar = $sconexao->query($sql)){ 
	die('<pre>Desculpe, mas aconteceu um erro [' . $sconexao->error . ']</pre>'); 
}

if ($tipo == 'xml') {
	$arquivo = 'xml_' . $tabela . '_' . date('Y_m_d');
	header('Content-Description: File Transfer');
	header("Content-Type: text/xml");
	header("Content-Disposition: attachment; filename = '$arquivo.xml'");
	header("Content-Transfer-Encoding: binary");
	
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$dados = $executar->fetch_fields();
	foreach ($dados as $v) {
		$a[] = $v->name;
	}
	
	echo "<$tabela>\n";
	while ($r = $executar->fetch_assoc()){
		echo "\t<$reg>\n";
		foreach ($a as $v) {
			echo "\t\t<$v>";
			echo $r[$v];
			echo "</$v>\n";	
		}
		echo "\t</$reg>\n";
	}
	echo "</$tabela>\n";
	exit();

} elseif ($tipo == 'csv') {
	$arquivo = 'csv_' . $tabela . '_' . date('Y_m_d');
	header('Content-Description: File Transfer');
	header("Content-Type: text/csv");
	header("Content-Disposition: attachment; filename = '$arquivo.csv'");
	header("Content-Transfer-Encoding: binary");

	$dados = $executar->fetch_fields();
	foreach ($dados as $v) {
		$a[] = $v->name;
		$c .= $v->name . ',';
	}
		$e = rtrim($c,',');
	
	while ($r = $executar->fetch_assoc()){
			$e .= "\n";
		foreach ($a as $v) {
			$d .= "\"" .  $r[$v] . "\",";
		}
			$e .= rtrim($d,',');
			$d = '';
	}
	print chr(255) . chr(254);
	print mb_convert_encoding($e, 'UTF-16LE', 'UTF-8');
	exit();
} elseif ($tipo == 'sql') {
	$arquivo = 'mysql_' . $tabela . '_' . date('Y_m_d');
	header('Content-Description: File Transfer');
	header("Content-Type: text/plain");
	header("Content-Disposition: attachment; filename = '$arquivo.txt'");
	header("Content-Transfer-Encoding: binary");
	
	$dados = $executar->fetch_fields();
	
		print "--\n-- APAGAR TABELA EXISTENTE\n--\n-- DROP TABLE IF EXISTS `b_$tabela`;\n--\n-- Execute o DROP apenas se quiser apagar uma tabela\n-- já existente junto com TODOS os seus dados\n\n\n-- CRIAR A TABELA\n\n";
		print "CREATE TABLE `b_$tabela` (\n";
		
	foreach ($dados as $v) {
		$a[] = array("nome" => $v->name, "tipo" => $v->type);
			print "\t`$v->name` ";
			if ($v->type == 1)
				print "tinyint($v->length) ";
			elseif ($v->type == 3)
				print "int($v->length) ";
			elseif ($v->type == 10)
				print "date ";
			elseif ($v->type == 12)
				print "datetime ";
			elseif ($v->type == 253)
				print "varchar(" . ($v->length/3) . ") ";
			else
				print $v->type;
			if ($v->flags & 1)
				print "NOT NULL";
			else
				print "DEFAULT NULL";
			if ($v->flags & 512)
				print " AUTO_INCREMENT";
			print ",\n";
	}
		print "\tPRIMARY KEY (`id`), KEY `id` (`id`)\n) ENGINE=InnoDB DEFAULT CHARSET=utf8;\n";
		
		if ($executar->num_rows != 0) {
		print "\n-- INSERIR DADOS ATUAIS\n\n";
		
			print "INSERT INTO `b_$tabela` (";
			foreach ($a as $v) {
				$q .= "`" .  $v["nome"] . "`, ";
			}
			print rtrim($q,', ');
			print ") VALUES ";
			$q = '';
		
		while ($r = $executar->fetch_assoc()){
				$q1 = "\n(";
			foreach ($a as $v) {
				if (($v["tipo"] == 10) or ($v["tipo"] == 12) or ($v["tipo"] == 253))
				$q .=  "'" . $r[$v["nome"]] . "', ";
				else
				$q .=  $r[$v["nome"]] . ", ";
			}
				$q1 .= rtrim($q,', ');
				$q1 .= "),";
				$q2 .= $q1;
				$q = '';
		}
			print rtrim($q2,',');
			print ';';
		}
		exit();
} else {
	die('<pre>Aconteceu um erro.</pre>');
}
?>
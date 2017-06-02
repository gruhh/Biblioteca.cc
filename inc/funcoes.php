<?php
/**
 * Funções básicas do Bibliotecas.cc
 */
include("login_acesso.php");
include_once("conexao.php");
$idinstalacao = $_SESSION['idinstalacao'];

/**
 * Retorna informações da configuração da instalação
 *
 * Necessita de um comando de saída para output
 */
function infoConfiguracao($a) {
	global $sconexao, $idinstalacao;
	
	if ($a == 'versao')
		return '1.0.2';
	elseif ($a == 'nome')
		$s = "SELECT nomebiblioteca as info FROM b_configuracoes WHERE idinstalacao='" . $idinstalacao . "'";
	elseif ($a == 'descricao')
		$s = "SELECT descricao as info FROM b_configuracoes WHERE idinstalacao='" . $idinstalacao . "'";
	elseif ($a == 'tipoinstalacao')
		$s = "SELECT tipoinstalacao as info FROM b_configuracoes WHERE idinstalacao='" . $idinstalacao . "'";
	elseif ($a == 'emprestimos')
		$s = "SELECT emprestimos as info FROM b_configuracoes WHERE idinstalacao='" . $idinstalacao . "'";
	elseif ($a == 'emprestimosdias')
		$s = "SELECT emprestimosdias as info FROM b_configuracoes WHERE idinstalacao='" . $idinstalacao . "'";
	elseif ($a == 'paginacao')
		$s = "SELECT paginacao as info FROM b_configuracoes WHERE idinstalacao='" . $idinstalacao . "'";
	elseif ($a == 'ajuda')
		$s = "SELECT ajudaconfiguracao as info FROM b_configuracoes WHERE idinstalacao='" . $idinstalacao . "'";
	elseif ($a == 'abordagemacervo')
		$s = "SELECT abordagemacervo as info FROM b_configuracoes WHERE idinstalacao='" . $idinstalacao . "'";
		
	if (!$seleciona = $sconexao->query($s)){ 
		die('<pre>Erro na função infoConfiguracao [' . $sconexao->error . ']</pre>'); 
	}
	$linha = $seleciona->fetch_assoc();
	
	return $linha['info'];
}
/**
 * Determina qual a página ativa no momento
 *
 * Esta função permite a adição da classe css "active" do bootstrap
 * conforme a página ativa.
 */
function Menu_Ativo($a) {
	$ativo = basename($_SERVER['PHP_SELF'],".php");
	if (strpos($ativo, $a) !== false)
		print " class=\"active\"";
}

/**
 * Gera o cabeçalho conforme o tipo de instalação
 */
function Construir_Cabecalho() {
	if (infoConfiguracao('tipoinstalacao') == 20) {
		$html5shiv = '<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>';
		$jquery = '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>';
		$bootstrapjs = '<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>';
	} else {
		$html5shiv = '<script src="lib/html5shiv/html5shiv.js"></script>';
		$jquery = '<script src="lib/jquery/jquery.js"></script>';
		$bootstrapjs = '<script src="lib/bootstrap/js/bootstrap.min.js"></script>';
	}
		$bootstrapcss = '<link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">';
		$temapadraocss = '<link href="dsg/tema-padrao.css" rel="stylesheet">';
		$respond = '<script src="lib/respond/respond.min.js"></script>';
		
	print $jquery . "\n" . $bootstrapjs ;
	print $bootstrapcss . "\n" . $temapadraocss;
	print "\n<!--[if lt IE 9]>\n" . $html5shiv . "\n" . $respond . "\n<![endif]-->\n";
}

/**
 * Escreve as informações estatísticas do banco de dados
 *
 * Não necessita de um comando de saída
 */
function Estatisticas($a,$b='e') {
	global $sconexao, $idinstalacao;
	
	if ($a == 'acervo')
		$s = "SELECT count(id) as contar FROM b_itens WHERE idinstalacao='" . $idinstalacao . "'";
	elseif ($a == 'usuarios')
		$s = "SELECT count(id) as contar FROM b_usuarios WHERE idinstalacao='" . $idinstalacao . "'";
	elseif ($a == 'emprestimos')
		$s = "SELECT count(id) as contar FROM b_emprestimos WHERE idinstalacao='" . $idinstalacao . "'";
	elseif ($a == 'emprestimos-abertos')
		$s = "SELECT count(id) as contar FROM b_emprestimos WHERE idinstalacao='" . $idinstalacao . "' AND status=0";
	
	if (!$seleciona = $sconexao->query($s)){ 
		die('<pre>Erro na função Estatisticas [' . $sconexao->error . ']</pre>'); 
	}
	$linha = $seleciona->fetch_assoc();
	
	if ($b=='e')
		print $linha['contar'];
	else
		return $linha['contar'];
}

/**
 * Checa disponibilidade no acervo 
 *
 * O tipo de resposta pode ser de dois tipos:
 * i (default) = retorna o número de itens disponíveis
 * b = retorna booleano, verdeiro se disponível
 */
function Disponibilidade($a,$b='i') {
	global $sconexao, $idinstalacao;
	
	if (isset($a)) {
		$s = "SELECT qntd as contar FROM b_itens WHERE idinstalacao='" . $idinstalacao . "' AND uid=$a";
		
	$seleciona = $sconexao->query($s);
	$linha = $seleciona->fetch_assoc();
	$qntitens = $linha['contar'];
	
		$s = "SELECT count(id) as contar FROM b_emprestimos WHERE ((idinstalacao='" . $idinstalacao . "') AND (iditem=$a) AND (status=0))";
		
	if (!$seleciona = $sconexao->query($s)){ 
		die('<pre>Erro na função Disponibilidade [' . $sconexao->error . ']</pre>'); 
	}
	$linha = $seleciona->fetch_assoc();
	$qntemprestada = $linha['contar'];

	if ($b=='i')
		return ($qntitens-$qntemprestada);
	elseif ($b=='b') {
		if (($qntitens-$qntemprestada)<=0)
		return false;
		else
		return true;
	}
	}
}

/**
 * Retorna informação do acervo ou usuário
 */
function bdDados($a,$b) {
	global $sconexao, $idinstalacao;
	
	if ($a == 'acervo-titulo')
		$s = "SELECT titulo as info FROM b_itens WHERE idinstalacao='" . $idinstalacao . "' AND uid=$b";
	elseif ($a == 'acervo-localizacao')
		$s = "SELECT localizacao as info FROM b_itens WHERE idinstalacao='" . $idinstalacao . "' AND uid=$b";
	elseif ($a == 'acervo-exemplar')
		$s = "SELECT numexemplar as info FROM b_itens WHERE idinstalacao='" . $idinstalacao . "' AND uid=$b";
	elseif ($a == 'usuario-nome')
		$s = "SELECT nome as info FROM b_usuarios WHERE idinstalacao='" . $idinstalacao . "' AND uid=$b";
		
	if (!$seleciona = $sconexao->query($s)){ 
		die('<pre>Erro na função bdDados [' . $sconexao->error . ']</pre>'); 
	}
	$linha = $seleciona->fetch_assoc();
	
	return $linha['info'];
}

/**
 * Retorna uID dos bancos de dados
 */
function uID($a) {
	global $sconexao, $idinstalacao;
	
	if ($a)
		$s = "SELECT uid FROM b_$a WHERE idinstalacao='" . $idinstalacao . "' ORDER BY uid DESC LIMIT 0,1";
	else
		return false;
		
	if (!$seleciona = $sconexao->query($s)){ 
		die('<pre>Erro na função uID [' . $sconexao->error . ']</pre>'); 
	}
	$linha = $seleciona->fetch_assoc();
	
	if ($seleciona->num_rows == 0)
		$n = 1;
	else
		$n = ($linha['uid'] + 1);
		
	return $n;
}

/**
 * Retorna o padrão de datas do sistema
 *
 * Salvando como YYYY-MM-DD HH:MM:SS
 * Exibindo como DD/MM/YYYY
 */
function padraoDatas($a,$b='e',$c=0,$e=0) {
	if (!$a || $a == 'agora')
		$a = date('d/m/Y');

		$a = explode(" ",$a);
		
		if ($a[1])
			$h = $a[1];
		
		$a = implode("-",array_reverse(explode("/",$a[0])));
		$a = strtotime($a);
		
	if ($c == 1) {
		$a = strtotime('+' . infoConfiguracao('emprestimosdias') . ' day', $a);
		$dw = date('w', $a);
		
		if ($dw==0)
			$a = strtotime('+1 day', $a);
		elseif ($dw==6)
			$a = strtotime('+2 day', $a);
	}
	
	if ($b == 's') {
		$d = date('Y-m-d', $a);
		if ($e == 1) {
			if ($h)
				$d .= " $h";
			else
				$d .= ' ' . date('g:i:s');
		}
		return $d;
	} else {
		$d = date('d/m/Y', $a);
		if ($e == 1) {
			if ($h)
				$d .= " $h";
			else
				$d .= ' ' . date('g:i:s');
		}
		return $d;
	}
}

/**
 * Função de paginação
 *
 * A lógica desta função está amplamente distribuída na internet
 * tendo sido adaptada para as necessidades do sistema
 */
function paginacao($a='q',$b=0) {
	$reg = infoConfiguracao('paginacao');
	$pag = $_GET['pag'];
	if(!$pag || $pag==0)
		$pag = 1;
		
	foreach ($_GET as $x => $y) {
		if ($x != 'pag')
	    	$f .= $x . '=' . $y . '&';
	}

	if ($a=='q') {
			if(!$pag)
				$mat = 0;
			else
				$mat = ($pag * $reg) - $reg;
				
			return " LIMIT $mat,$reg";
	}
	elseif ($a=='p' && $b>0) {
		$pags = ceil($b / $reg);
		if ($pags>1):
			echo "<ul class=\"pagination\">";
			if ($pag<=1) {
				$cls = ' class="disabled"';
				$paga = 1;
			} else
				$paga = $pag - 1;	
			echo "<li$cls><a href=\"?$f\rpag=$paga\">&laquo;</a></li>";
				for($i=1; $i <= $pags; $i++) {
					if ($pag==$i)
						$cls = ' class="active"';
					else
						$cls = '';
					echo "<li$cls><a href=\"?$f\rpag=$i\">$i</a></li>";
				}
			if ($pag>=$pags) {
				$cls = ' class="disabled"';
				$paga = $pags;
			} else
				$paga = $pag + 1;	
			echo "<li$cls><a href=\"?$f\rpag=$paga\">&raquo;</a></li>";
			echo "</ul>";
		endif;
	}
}
?>
<?php
/**
 * Gera a página do "Painel"
 */ 
include("login_acesso.php");
include_once("instalacao.php");
include_once("inc/funcoes.php");
include_once("inc/cabecalho.php");
include_once("inc/menu-cabecalho.php");
?>
<div class="container painel">
<div class="row">
<?php include_once("inc/menu-painel.php"); ?>
<div class="col-md-9">
<div class="page-header">
<h1>Painel <small><?php print infoConfiguracao('nome'); ?></small></h1>
</div>
<div class="row">
<div class="col-md-6">
<?php if ((Estatisticas('acervo','s')>=50) && (Estatisticas('acervo','s')<75)): ?>
<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title">Pesquisa de satisfação</h3>
</div>
<div class="panel-body"> 
  <p>Você 
    atingiu a primeira fase de teste, adicionando 50 ítens ao acervo, complete a nossa pesquisa de satisfação para sabermos um pouco sobre o que você achou do sistema até o momento. Para participar da pesquisa você precisará estar conectado à internet.</p>
  <p><a href="http://biblioteca.cc/pesquisa.php?meta=50" class="btn btn-success btn-sm">Acessar a pesquisa</a></p>
</div>
</div>
<?php endif; ?>
<?php if ((Estatisticas('acervo','s')>=150) && (Estatisticas('acervo','s')<175)): ?>
<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title">Pesquisa de satisfação II</h3>
</div>
<div class="panel-body">
  <p>Você 
    atingiu a segunda fase de teste, adicionando 150 ítens ao acervo, complete a nossa pesquisa de satisfação para sabermos um pouco sobre o que você achou do sistema até o momento. Para participar da pesquisa você precisará estar conectado à internet.</p>
  <p><a href="http://biblioteca.cc/pesquisa.php?meta=150" class="btn btn-success btn-sm">Acessar a pesquisa</a></p>
</div>
</div>
<?php endif; ?>
<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title">Seja bem-vindo</h3>
</div>
<div class="panel-body">
  Este é o painel administrativo através do qual poderá atualizar todas as informações da biblioteca.
</div>
</div>
</div>
<div class="col-md-6">
<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title">Estatísticas</h3>
</div>
<div class="panel-body">
<p><span class="lead"><?php Estatisticas('acervo'); ?></span> ítens adicionados ao acervo</p>
<p><span class="lead"><?php Estatisticas('usuarios'); ?></span> usuários cadastrados</p>
<p><span class="lead"><?php Estatisticas('emprestimos'); ?></span> empréstimos realizados</p>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<?php include_once("inc/rodape.php"); ?>
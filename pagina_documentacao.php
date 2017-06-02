<?php
/**
 * Página estática: Documentação
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
<h1>Documentação:</h1>
</div>
<div class="row">
<div class="col-md-offset-1 col-md-8">
<p>A documentação do código do Biblioteca.cc estará disponível publicamente através do wiki oficial do projeto, acesse as informações técnicas do sistema através do link:</p>
<p><a href="http://www.biblioteca.cc/wiki" class="btn btn-link" target="_blank">http://www.biblioteca.cc/wiki</a></p>
<p>Nas próximas versões, pretendemos distribuir uma documentação resumida através desta página, assim não necessitando de acesso à internet. Caso você tenha alguma dúvida, e não tenha uma conexão disponível, veja algumas informações na nossa página de <a href="pagina_aprendaobasico.php">ajuda básica</a>.</p>
</div>
<div class="col-md-2">
&nbsp;
</div>
</div>
</div>
</div>
</div>
<?php include_once("inc/rodape.php"); ?>
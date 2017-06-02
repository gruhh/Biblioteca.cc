<header class="cabecalho">
<nav class="navbar navbar-default" role="navigation">
<div class="container">
<div class="navbar-header">
<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
<span class="sr-only">Menu</span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
</button>
<a class="navbar-brand" href="painel.php">Biblioteca.cc</a>
</div>
<div class="collapse navbar-collapse navbar-ex1-collapse">
<ul class="nav navbar-nav">
<li<?php Menu_Ativo('configuracoes');?>><a href="configuracoes.php">Configurações</a></li>
<li<?php Menu_Ativo('dicasdeuso');?>><a href="pagina_dicasdeuso.php">Dicas de uso</a></li>
<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown">Precisa de Ajuda? <b class="caret"></b></a>
<ul class="dropdown-menu">
<li<?php Menu_Ativo('documentacao');?>><a href="pagina_documentacao.php">Documentação</a></li>
<li<?php Menu_Ativo('contato');?>><a href="pagina_contato.php">Contato</a></li>
<li class="divider">&nbsp;</li>
<li<?php Menu_Ativo('sobre');?>><a href="pagina_sobre.php">Sobre</a></li>
</ul>
</li>
</ul>
<ul class="nav navbar-nav navbar-right">
<li><a href="#"><?php print infoConfiguracao('nome'); ?></a></li>
<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown">Usuário <b class="caret"></b></a>
<ul class="dropdown-menu">
<li><a href="admin_dados.php">Seus Dados</a></li>
</ul>
</li>
<li><a href="logout.php">Sair</a></li>
</ul>
</div>
</div>
</nav>
</header>
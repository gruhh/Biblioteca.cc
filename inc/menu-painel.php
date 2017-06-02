<nav class="col-sm-3 menu">
  <ul class="nav nav-pills nav-stacked">
   <form class="menu-busca-rapida" role="form" action="acervo.php" method="get">
 	<div class="input-group">
	 <input type="search" class="form-control input-sm" id="buscarpor" name="buscarpor" placeholder="Buscar no acervo">
	 <span class="input-group-btn">
	 <button type="submit" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-search"></span></button>
	 </span>
	 </div>
   </form>
  <li<?php Menu_Ativo('painel');?>><a href="painel.php">Painel</a></li>
  <li<?php Menu_Ativo('acervo');?>><a href="acervo.php">Acervo da Biblioteca</a></li>
  <li<?php Menu_Ativo('usuarios');?>><a href="usuarios.php">Usuários</a></li>
  <li<?php Menu_Ativo('emprestimos');?>><a href="emprestimos.php">Empréstimos</a></li>
  <li class="nav-divider">&nbsp;</li>
  <li class="title"><a href="#">Acesso rápido:</a></li>
  <li><a href="emprestimos_a.php">Empréstimo de Item</a></li>
  <li><a data-toggle="modal" href="#emprestimoDevModrapido">Devolução de Item</a></li>
  </ul>
</nav>

  <div class="modal" id="emprestimoDevModrapido" tabindex="-1" role="dialog" aria-labelledby="emprestimoDevLabel" aria-hidden="true"> 
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Devolução de Empréstimo</h4>
        </div>
        <form role="form" method="get" action="emprestimos.php">
        <div class="modal-body">
        <?php if (infoConfiguracao('ajuda')=='1'): ?>
<div class="panel panel-default">
  <div class="panel-body">
	<h4>Ajuda</h4>
	<p>Este é o formulário de busca de empréstimos à serem devolvidos, preencha um dos dois campos e clique no botão para procurar.</p>
    <p>No ítem do acervo, você poderá procurar pelo título, autor, assunto ou ID (conforme as informações cadastradas no sistema). Você pode procurar apenas uma palavra, ou o título completo.</p>
    <p>No usuário, você poderá procurar pelo nome ou ID (conforme as informações cadastradas no sistema). E novamente, por apenas um nome ou o nome completo.</p>
  </div>
</div>
<?php endif; ?>
        <div class="form-group">
            <label for="e_acervo" class="control-label">Ítem do acervo:</label> <small>(Identificação, título ou autor)</small>
            <input type="text" class="form-control" id="e_acervo" name="e_acervo">
        </div>
        <div class="form-group">
            <label for="e_usuario" class="control-label">Ou usuário:</label> <small>(Nome do usuário)</small>
            <input type="text" class="form-control" id="e_usuario" name="e_usuario">
        </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Fechar</button>
          <button type="submit" class="btn btn-success btn-sm" id="emprestimosProcurar">Procurar empréstimos abertos</button>
        </div></form>
      </div>
    </div>
  </div>
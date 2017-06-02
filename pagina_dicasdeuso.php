<?php
/**
 * Página estática: Dicas de uso
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
<h1>Dicas de Uso</h1>
</div>
<div class="row">
<div class="col-md-offset-1 col-md-8">
<p class="lead">&quot;Agilize, facilitando a busca pelo conhecimento&quot;</p>
<p>Tenha esse pensamento ao utilizar o aplicativo. De forma básica, o aplicativo busca possibilitar a informatização rápida de acervos, usuários e empréstimos, facilitando a busca e o acesso ao conhecimento.</p>
<p>Veja o  aplicativo como um &quot;buscador de conhecimento&quot;. Esta é a abordagem proposta para a utilização do sistema, através da simplificação. Não desvalorizamos abordagens técnicas,  tentamos apenas disponibilizar  uma contextualização diferente.</p>
<p>&nbsp;</p>
<p class="divider">&nbsp;</p>
<p>&nbsp;</p>
<h4>Acervo da biblioteca</h4>
<p>- Por padrão, o aplicativo faz o registro do acervo por obra e não por exemplar. Ou seja, caso tenha mais de um exemplar (mesmo que de edições diferentes, caso não queira realizar um registro para cada edição), você poderá contá-los como quantidade de um mesmo registro.</p>
<p>- Caso opte por uma abordagem de cadastro por exemplar, o sistema poderá ser utilizado, porém, será necessário identificar cada livro, com o seu número de ID no sistema, para saber qual é o registro de cada exemplar, no momento em que estiver realizando manutenções ou empréstimos.</p>
<p><span class="label label-primary">Importante</span> - Obrigatóriamente o único campo necessário para o registro de um ítem é o &quot;Título&quot;, porém, preferencialmente recomendamos que pelo menos seja cadastrado também o &quot;autor&quot; da obra e uma descrição da obra no campo &quot;assunto&quot;. Estes 3 campos permitem uma abordagem de utilização do aplicativo como um &quot;buscador&quot; na biblioteca.</p>
<p>- Separe mais de um &quot;autor&quot; por vírgulas.</p>
<p>- Crie a sua própria abordagem ao cadastrar a descrição da obra no campo &quot;assunto&quot;, pensando no que o usuário procuraria para encontrar a obra. Esta é uma a principal forma de encontrar obras que não se conheça pelo título ou pelo nome do autor. A sinopse pode ajudar na hora de descreve, porém é importante que a abordagem seja levada a todos os ítens possíveis, pois assim, haverá uma unidade na busca, como no exemplo, adicionando palavras-chaves na descrição.</p>
<blockquote>
  <p><em>Por exemplo: A descrição para a obra &quot;O Senhor dos Anéis&quot; poderia ser: &quot;Uma aventura de fantasia que conta a história de Frodo para derrotar Sauron. RPG, literatura estrangeira, literatura infanto-juvenil.&quot; Dessa forma, se um aluno buscar por 'fantasia' ou 'literatura', o livro será listado, assim como se buscar por 'literatura estrangeira'.</em></p>
</blockquote>
<p>&nbsp;</p>
<p class="divider">&nbsp;</p>
<p>&nbsp;</p>
<h4>Usuários</h4>
<p>- Os usuários estão relacionados à todas as pessoas que utilizam a biblioteca e podem realizar empréstimos de livros. </p>
<p>- O único dado obrigatório dos usuários é o nome, porém recomendamos que além do nome completo, seja adicionado uma referência e pelo menos uma informação de contato, conforme o regimento da biblioteca.</p>
<p>- As referências estão relacionadas à uma identificação do usuário, como por exemplo, se é um colaborador da instituição, um aluno da escola (e de qual turma) ou o pai de um aluno, etc.</p>
<p>&nbsp;</p>
<p class="divider">&nbsp;</p>
<p>&nbsp;</p>
<h4>Empréstimos</h4>
<p>- Ao iniciar a utilização do aplicativo para organizar os empréstimos de ítens do acervo, dê continuidade por alguns dias à forma atual de gerenciamento, até adaptar-se.</p>
<p><span class="label label-primary">Importante</span> - Cada empréstimo deve ter como pensamento, &quot;um usuário está emprestando um ítem&quot;, ou seja, cada ítem gera um registro de empréstimo, e ao ser devolvido deverá estar associado ao usuário que o retirou.</p>
<blockquote>
  <p><em>Por exemplo: João, aluno da escola, está retirando 2 livros. Cada livro deverá gerar 1 registro de empréstimo em nome do João. No momento da devolução, os registros de que João retirou cada um dos livros deverão ser marcados como &quot;Devolvidos&quot;, uma vez que o acervo é cadastrado por obra e não por exemplar, assim, caso haja mais de 1 exemplar emprestado, você saberá qual foi devolvido.</em></p>
</blockquote>
<p>- Você não precisa ter o acervo completo ou o cadastro de todos os usuários para iniciar os empréstimos, comece a utilizar o sistema com pequenas metas, que vão possibilitar a validação da utilização do aplicativo.</p>
<p>- Prefira sempre levar alguns segundos à mais para selecionar o ítem e o usuário corretos antes de efetuar o empréstimo.</p>
<p>- O aplicativo leva em consideração empréstimos disponíveis entre segundas e sextas-feiras, assim datas de devolução que poderiam cair nos dias de sábado ou domingo são marcadas para a próxima segunda-feira. </p>
<p>&nbsp;</p>
<p class="divider">&nbsp;</p>
<p>&nbsp;</p>
<h4>Boas práticas</h4>
<p>- Inicie uma padronização dos seus dados, preferencialmente não utilize todas as letras em maiúsculo e evite abreviações.</p>
<p>- Facilite para você mesmo e para o usuário, lembre-se que estamos trabalhando com pessoas que querem buscar conhecimento.</p>
<p>- Não se desfaça dos dados caso deixe de trabalhar na biblioteca, passe para o próximo responsável, afinal, você fez sim um bom trabalho.</p>
<p>- Mantenha as informações atualizadas, seja o acervo, os usuários ou os empréstimos. </p>
</div>
<div class="col-md-2">
&nbsp;
</div>
</div>
</div>
</div>
</div>
<?php include_once("inc/rodape.php"); ?>
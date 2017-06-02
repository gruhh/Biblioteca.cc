$(function () {
    $("#acervo-msg-erro").hide();
    $("#acervo-msg-ok").hide();
    $("#acervosalvarMod").click(function () {
		$(this).button('loading');
        var idinstalacao = $("input#acervoidinstalacao").val();
        var alterarid = $("input#acervoalterarid").val();
        var apagarid = $("input#acervoapagarid").val();
        var categoria = $("input#acervocategoria").val();
        if (categoria == "") {
            $("input#acervocategoria").focus();
            return false;
        }

        $.ajax({
            type: "POST",
            url: "inc/configuracoes_classificacao_acervo.php",
            data: 'categoria=' + categoria + '&idinstalacao=' + idinstalacao + '&apagarid=' + apagarid + '&alterarid=' + alterarid,
            success: function (data) {
				setTimeout(function () {$("#acervosalvarMod").button('reset')}, 1000)
                if (data == "ok") {
                    $('#acervo-msg-ok').show();
                    $("#acervo-msg-erro").hide();
                } else {
                    $('#acervo-msg-erro').show();
                    $("#acervo-msg-ok").hide();
                }
            }
        });
        return false;
    });

    $('#classificacaoAcervoMod').on('hide.bs.modal', function () {
        $("input#acervoalterarid").val('');
        window.location.reload();
    });

    $(".classificacaoEditar").on("click", function () {
        $(".modal-body #acervocategoria").val($(this).data('categoria'));
        $(".modal-body #acervoalterarid").val($(this).data('idcategoria'));
        $("#acervosalvarMod").html('Alterar Categoria');
    });
	
    $(".classificacaoApagar").on("click", function () {
        $("#modal-acervo").html('<p>Você irá apagar a categoria: <strong>' + $(this).data('categoria') + '</strong>.<br><br>Ao apagar esta categoria de classificação, todos os itens cadastrados perderão sua classificação, mas não serão apagados.<br><br>Não há como desfazer esta opção, prefira alterar a categoria ao invés de apagá-la.</p>');
        $(".modal-body #acervoapagarid").val($(this).data('idcategoria'));
        $("#acervosalvarMod").html('Apagar Categoria');
        $("#acervosalvarMod").attr("class", "btn btn-alerta btn-sm");
    });
});
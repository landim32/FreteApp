$.cliente = {
    base_path: "/"
};

$.cliente.init = function() {
    $('.cliente-submit').click(function (e) {
        e.preventDefault();

        var $btn = $(this);
        $btn.button('loading');

        var id_cliente = $("#id_cliente").val();

        if (id_cliente > 0) {
            $.cliente.pegar(id_cliente, function (cliente) {
                $.cliente.preencherCliente(cliente);
                $.cliente.alterar(cliente, function () {
                    $.success("Cliente alterado com sucesso.");
                    $btn.button('reset');
                    $.cliente.limpar();
                }, function (erro) {
                    $btn.button('reset');
                    $.error(erro);
                });
            }, function (erro) {
                $btn.button('reset');
                $.error(erro);
            });
        }
        else {
            var cliente = {};
            preencherCliente(cliente);
            $.cliente.inserir(cliente, function (id_cliente) {
                $.success("Cliente incluído com sucesso.");
                $btn.button('reset');
                $.cliente.limpar();
            }, function (erro) {
                $btn.button('reset');
                $.cliente.limpar();
                $.error(erro);
            });
        }
        return false;
    });

    $('#cliente-buscar').click(function (e) {
        e.preventDefault();

        $.cliente.buscar($('#palavra-chave').val(), 1);

        return false;
    });
};

$.cliente.reload = function () {
    $('.cliente-alterar').click(function (e) {
        e.preventDefault();

        var id_cliente = $(this).attr('data-cliente');

        var $btn = $(this);
        $.cliente.pegar(id_cliente, function (cliente) {
            $("#id_cliente").val(cliente.id_cliente);
            $("#nome").val(cliente.nome);
            $("#empresa").val(cliente.empresa);
            $("#email1").val(cliente.email1);
            $("#telefone1").val(cliente.telefone1);
            $("#telefone2").val(cliente.telefone2);
            $("#cod_situacao").val(cliente.cod_situacao);
            $('#tags').tagsinput('removeAll');
            $.each(cliente.tags, function( index, tag ) {
                $('#tags').tagsinput('add', tag.nome);
            });
            $('#clienteModal').modal('show');
        }, function (erro) {
            $btn.button('reset');
            $.error(erro);
        });
        return false;
    });

    $('.cliente-excluir').click(function (e) {
        e.preventDefault();
        var id_cliente = $(this).attr('data-cliente');

        var $btn = $(this);
        $.confirm("Tem certeza que deseja excluir?", function () {
            $.cliente.excluir(id_cliente, function() {
                $btn.closest('tr').fadeOut('slow');
                $.success("Cliente excluído com sucesso.");
            }, function (erro) {
                $.error(erro);
            });
        });
        return false;
    });
};

$.cliente.preencherCliente = function (cliente) {
    var tags = [];
    $.each($("#tags").tagsinput('items'), function( index, tag ) {
        tags.push({id_tag: 0, slug: '', nome: tag});
    });
    cliente.id_cliente = $("#id_cliente").val();
    cliente.nome = $("#nome").val();
    cliente.empresa = $("#empresa").val();
    cliente.email1 = $("#email1").val();
    cliente.telefone1 = $("#telefone1").val();
    cliente.telefone2 = $("#telefone2").val();
    cliente.cod_situacao = $("#cod_situacao").val();
    cliente.tags = tags;
};

$.cliente.pegar = function(id_cliente, sucesso, falha) {
    var url = $.cliente.base_path + '/api/crm/cliente/pegar/' + id_cliente
    $.getJSON(url, function (cliente) {
        sucesso(cliente);
    }).fail(function (request, status, error) {
        falha(request.responseText);
    });
};

$.cliente.buscar = function(palavra_chave, cod_situacao) {
    $.ajax({
        method: "POST",
        dataType: "html",
        url: $.cliente.base_path + "/ajax/crm/clientes",
        data: {
            palavra_chave: palavra_chave,
            cod_situacao: cod_situacao
        },
        success: function ( data ) {
            $( "#main-content" ).html( data );
            inicializar();
        },
        error: function (request, status, error) {
            $.error(request.responseText);
        }
    });
};

$.cliente.excluir = function(id_cliente, sucesso, falha) {
    var url = $.cliente.base_path + '/api/crm/cliente/excluir/' + id_cliente
    $.get(url, function () {
        sucesso();
    }).fail(function (request, status, error) {
        falha(request.responseText);
    });
};

$.cliente.inserir = function(cliente, sucesso, falha) {
    $.ajax({
        method: "PUT",
        dataType: "json",
        url: $.cliente.base_path + '/api/crm/cliente/inserir',
        data: JSON.stringify(cliente),
        success: function (dado) {
            sucesso(dado);
        },
        error: function (request, status, error) {
            falha(request.responseText);
        }
    });
};

$.cliente.alterar = function(cliente, sucesso, falha) {
    var url = $.cliente.base_path + '/api/crm/cliente/inserirOuAlterar';
    $.ajax({
        method: "PUT",
        dataType: "json",
        url: url,
        data: JSON.stringify(cliente),
        success: function (dado) {
            sucesso();
        },
        error: function (request, status, error) {
            alert(request + status + error);
            falha(request.responseText);
        }
    });
};

$.cliente.limpar = function() {
    $("#id_cliente").val('0');
    $("#nome").val('');
    $("#empresa").val('');
    $("#email1").val('');
    $("#telefone1").val('');
    $("#telefone2").val('');
    $("#cod_situacao").val(1);
    $('#tags').tagsinput('removeAll');
}
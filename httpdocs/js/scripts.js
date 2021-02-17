/*!
    * Start Bootstrap - SB Admin v6.0.0 (https://startbootstrap.com/templates/sb-admin)
    * Copyright 2013-2020 Start Bootstrap
    * Licensed under MIT (https://github.com/BlackrockDigital/startbootstrap-sb-admin/blob/master/LICENSE)
    */

/* When the user clicks on the button, 
toggle between hiding and showing the dropdown content */

window.onload = function () {

    //Quando abrir o FORM        
    if ($("input[name=acesso_app]").is(":checked")) {
        $("div[name=configuracao_app]").removeClass('collapse');
    } else {
        $("div[name=configuracao_app]").addClass('collapse');
    }

    if ($("input[name=id]").val() != "") {
        $("input[name=senha]").removeAttr("required");
        $("input[name=senhaConfirmacao]").removeAttr("required");
    }
};

$(document).ready(function () {

    $("input[name=senha]").change(function () {
        if ($("input[name=id]").val() != "") {
            if ($(this).val() === "") {
                $("input[name=senha]").removeAttr("required");
                $("input[name=senhaConfirmacao]").removeAttr("required");
            } else {
                $("input[name=senha]").attr("required", "required");
                $("input[name=senhaConfirmacao]").attr("required", "required");
            }
        }
    });

    $("input[name=acesso_app]").change(function () {
        if (this.checked) {
            $("div[name=configuracao_app]").removeClass('collapse');
        } else {
            $("div[name=configuracao_app]").addClass('collapse');
        }
    });

    //Initialize select2
    if ($("input[name=id_usuario_lista_selecionado]").length) {
        var usuario_selecionado = $("input[name=id_usuario_lista_selecionado]").val();
        $("select[id=id_usuario_lista]").val(JSON.parse(usuario_selecionado)).select2({
            theme: "bootstrap4",
            language: "pt-BR",
            multiple: true,
            minimumResultsForSearch: -1,
            minimumInputLength: 2,
        });
    }

    if ($("input[name=id_usuario_selecionado]").length) {
        var usuario_selecionado = $("input[name=id_usuario_selecionado]").val();
        $("select[id=id_usuario]").val(JSON.parse(usuario_selecionado)).select2({
            theme: "bootstrap4",
            language: "pt-BR",
            multiple: false,
            minimumResultsForSearch: -1,
            minimumInputLength: 2,
        });
    }
    if ($("input[name=id_categoria_lista_selecionado]").length) {
        var categoria_selecionada = $("input[name=id_categoria_lista_selecionado]").val();
        $("select[id=id_categoria_lista]").val(JSON.parse(categoria_selecionada)).select2({
            theme: "bootstrap4",
            language: "pt-BR",
            multiple: true,
            minimumResultsForSearch: -1,
            minimumInputLength: 2,
        });
    }

    if ($("input[name=id_pessoa_lista_selecionado]").length) {
        var pessoa_selecionada = $("input[name=id_pessoa_lista_selecionado]").val();
        $("select[id=id_pessoa_lista]").val(JSON.parse(pessoa_selecionada)).select2({
            theme: "bootstrap4",
            language: "pt-BR",
            multiple: true,
            minimumResultsForSearch: -1,
            minimumInputLength: 2,
        });
    }

    if ($("input[name=id_pessoa_selecionado]").length) {
        var pessoa_selecionada = $("input[name=id_pessoa_selecionado]").val();
        $("select[id=id_pessoa]").val(JSON.parse(pessoa_selecionada)).select2({
            theme: "bootstrap4",
            language: "pt-BR",
            multiple: false,
            minimumResultsForSearch: -1,
            minimumInputLength: 2,
        });
    }

    if ($("input[name=id_produto_lista_selecionado]").length) {
        var produto_selecionado = $("input[name=id_produto_lista_selecionado]").val();
        $("select[id=id_produto_lista]").val(JSON.parse(produto_selecionado)).select2({
            theme: "bootstrap4",
            language: "pt-BR",
            multiple: true,
            minimumResultsForSearch: -1,
            minimumInputLength: 2,
        });
    }

    if ($("input[name=id_produto_modal_selecionado]").length) {
        var produto_selecionada = $("input[name=id_produto_modal_selecionado]").val();
        $("select[id=id_produto_modal]").val(JSON.parse(produto_selecionada)).select2({
            dropdownParent: $(this).find('.modal-content'),
            theme: "bootstrap4",
            language: "pt-BR",
            multiple: false,
            minimumResultsForSearch: -1,
            minimumInputLength: 2,
        });
    }

    $("select[name=id_estado]").select2({
        theme: "bootstrap4",
        language: "pt-BR",
    });

    $("select[name=id_banco]").select2({
        theme: "bootstrap4",
        language: "pt-BR",
    });

    // Setup - add a text input to each footer cell
    //$('#lista thead tr').clone(true).appendTo('#lista thead');
    $('#lista thead tr:eq(1) th').each(function (i) {
        var title = $(this).text();
        $(this).html(title != 'Ações' ? '<input type="text" placeholder="' + title + '" />' : "");
        $('input', this).on('keyup change', function () {
            if (table.column(i).search() !== this.value) {
                table
                    .column(i)
                    .search(this.value)
                    .draw();
            }
        });
    });

    //Datatable
    $.fn.dataTable.moment('DD/MM/YYYY');

    $.fn.dataTable.moment = function (format, locale) {
        var types = $.fn.dataTable.ext.type;

        // Add type detection
        types.detect.unshift(function (d) {
            return moment(d, format, locale, true).isValid() ?
                'moment-' + format :
                null;
        });

        // Add sorting method - use an integer for the sorting
        types.order['moment-' + format + '-pre'] = function (d) {
            return moment(d, format, locale, true).unix();
        };
    };

    //Datatable
    var table = $('#lista').DataTable({
        aaSorting: [],
        pageLength : 100,
        orderCellsTop: true,
        fixedHeader: true,
        //dom: 'lBtipr',
        dom: 'lBtr',
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Portuguese-Brasil.json",
            buttons: {
                colvis: "<i class=\"fa fa-columns\"></i>",
                excel: "<i class=\"fa fa-file-excel\"></i>",
                pdf: "<i class=\"fa fa-file-pdf\"></i>",
                print: "<i class=\"fas fa-print\"></i>",
                //pageLength: { _: "Mostrar %d ", '-1': "Exibir tudo" },
            }
        },
        lengthChange: false,
        select: true,
        buttons: {
            buttons: [
                //'pageLength',
                { extend: 'excelHtml5', titleAttr: 'Excel' },
                { extend: 'pdfHtml5', titleAttr: 'PDF' },
                { extend: 'print', titleAttr: 'Imprimir' },
                { extend: 'colvis', titleAttr: 'Colunas' },
                //'pageLength', 'excel', 'pdf', { extend: 'colvis', text: 'Colunas' }
                //buttons: [ 'pageLength', 'excel', 'pdf', 'colvis' ]
            ],
            dom: {
                button: {
                    tag: "button",
                    className: "btn btn-secondary"
                },
                buttonLiner: {
                    tag: null
                }
            }
        }
    });

    table.buttons().container()
        .appendTo('#lista_wrapper .col-md-6:eq(0)');

    //CEP
    $("input[name=auto_cep]").blur(
        function () {
            var csrf_hash = $("input[name=csrf_test_name]").val(); // CSRF hash
            var cep = $("input[name=auto_cep]").val().replace(/\D/g, ''); // CSRF hash
            return $.ajax({
                type: 'POST',
                url: '/autocep',
                data: { csrf_test_name: csrf_hash, query: cep },
                dataType: 'json',
            })
                .done(function (response) {
                    $('input[name=csrf_test_name]').val(response.csrf_hash);
                    $("input[name=logradouro]").val(response.logradouro);
                    $("input[name=id_auto_cidade]").val(response.id_cidade);
                    $("input[name=auto_cidade]").val(response.cidade);
                    $("input[name=bairro]").val(response.bairro);
                    $("input[name=complemento]").val(response.complemento);
                    $("select[name=id_estado]").val(response.id_estado).trigger('change');
                })
                .fail(function (jqXHR, textStatus, msg) {
                    $("input[name=logradouro]").val("");
                    $("input[name=id_auto_cidade]").val("");
                    $("input[name=auto_cidade]").val("");
                    $("select[name=id_estado]").val(null).trigger('change');
                    alert(msg);
                });
        },
    );

    //Busca de Cidade    
    $("input[name=auto_cidade]").autocomplete({
        source: function (query, process) {
            var csrf_hash = $("input[name=csrf_test_name]").val(); // CSRF hash
            return $.ajax({
                url: "/autocomplete",
                type: 'POST',
                data: { csrf_test_name: csrf_hash, query: query, tabela: 'cidades' },
                dataType: 'json',
            })
                .done(function (response) {
                    //console.log(response);
                    $('input[name=csrf_test_name]').val(response.csrf_hash);
                    data = JSON.parse(JSON.stringify(response.dados))
                    return process(data);
                })
                .fail(function (jqXHR, textStatus, msg) {
                    alert(msg);
                });
        },
        select: function (event, ui) {
            // Set selection          
            $('input[name=id_auto_cidade]').val(ui.item.id);
            $('input[name=auto_cidade]').val(ui.item.value);
            return false;
        }
    });

    //Busca de Cliente    
    $("input[name=auto_cliente]").autocomplete({
        classes: {
            "ui-autocomplete": "highlight"
        },
        source: function (query, process) {
            var csrf_hash = $("input[name=csrf_test_name]").val(); // CSRF hash
            return $.ajax({
                url: "/autocomplete",
                type: 'POST',
                data: { csrf_test_name: csrf_hash, query: query, tabela: 'pessoas', like: 'tipo', like_match: 'C', like_side: 'both' },
                dataType: 'json',
            })
                .done(function (response) {
                    //console.log(response);
                    $('input[name=csrf_test_name]').val(response.csrf_hash);
                    data = JSON.parse(JSON.stringify(response.dados))
                    return process(data);
                })
                .fail(function (jqXHR, textStatus, msg) {
                    alert(msg);
                });
        },
        select: function (event, ui) {
            // Set selection          
            $('input[name=id_auto_cliente]').val(ui.item.id);
            $('input[name=auto_cliente]').val(ui.item.value);
            return false;
        }
    });
});

function filtroList() {
    if ($("div[name=filtros_list]").hasClass("collapse")) {
        $("div[name=filtros_list]").removeClass('collapse');
    } else {
        $("div[name=filtros_list]").addClass('collapse');
    }
};

function deleteItem(comando, id) {
    $.confirm({
        title: 'Questão?',
        content: 'Tem certeza de que deseja excluir o item selecionado?',
        buttons: {
            confirmar: {
                btnClass: 'btn-green',
                //action: function () {}
                //action: function () { $.alert(comando); }
                action: function () {
                    var csrf_hash = $("input[name=csrf_test_name]").val(); // CSRF hash
                    return $.ajax({
                        type: 'POST',
                        url: '/' + comando,
                        data: { csrf_test_name: csrf_hash, query: id },
                        dataType: 'json',
                    })
                        .done(function (response) {
                            $('input[name=csrf_test_name]').val(response.csrf_hash);
                            $('#lista').DataTable().row("#" + id).remove().draw();
                        })
                        .fail(function (jqXHR, textStatus, msg) {
                            alert(msg);
                        });
                },
            },
            cancelar: {
                btnClass: 'btn-red',
                action: function () { }
            }
        }
    });
};

function mostraImagem(caminho, imagem) {
    $.ajax({
        url: caminho + "/" + imagem,
        type: 'HEAD',
        success: function () {
            $("img[name=foto]").attr("src", caminho + "/" + imagem);
        },
        error: function () {
            $("img[name=foto]").attr("src", caminho + "/nao_disponivel.jpg");
        },
    });

    $('#imagemModal').modal('show');
};

function editaProduto() {
    // get Edit Product     
    $('select[name=id_produto]').val(produto).trigger('change');
    
    // Call Modal Edit
    $('#addProdutoModal').modal('show');
};

function novoProduto() {
    // get Edit Product         
    $('select[name=id_produto]').val('').trigger('change');
    $('#addProdutoModal').modal('show');
};
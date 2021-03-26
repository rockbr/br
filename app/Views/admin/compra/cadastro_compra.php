<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card border-1 my-2">
                <div class="card-header">
                    <h1 class="text-center my-2"><?php echo $titulo; ?></h1>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger alert-dismissible fade show" <?php echo (isset($erro) ? '' : 'style="display: none;"'); ?> role="alert">
                        <?php echo ($erro) ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form method="post" action="<?= base_url($url_salvar) ?>" enctype="multipart/form-data">
                        <div class="card border-secondary mb-2" name="dados_principal">
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="col-md-4 dropdown">
                                        <label class="small mb-1" for="id_prioridade_compra">Prioridade</label></br>
                                        <select class="form-control" name="id_prioridade_compra" id="id_prioridade_compra" required <?= (isset($dados['id'])) ? ' disabled' : '' ?>>
                                            <option value="" disabled selected>Selecione</option>
                                            <?php if (!empty($prioridade_compra)) {
                                                foreach ($prioridade_compra as $frow) { ?>
                                                    <option value="<?php echo $frow['id']; ?>" <?= (isset($dados['id_prioridade_compra']) && $dados['id_prioridade_compra'] == $frow['id']) ? ' selected' : '' ?>>
                                                        <?php echo $frow['nome']; ?>
                                                    </option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 dropdown">
                                        <label class="small mb-1" for="id_categoria_compra">Departamento</label></br>
                                        <select class="form-control" name="id_categoria_compra" id="id_categoria_compra" required <?= (isset($dados['id'])) ? ' disabled' : '' ?>>
                                            <option value="" disabled selected>Selecione</option>
                                            <?php if (!empty($categoria_compra)) {
                                                foreach ($categoria_compra as $frow) { ?>
                                                    <option value="<?php echo $frow['id']; ?>" <?= (isset($dados['id_categoria_compra']) && $dados['id_categoria_compra'] == $frow['id']) ? ' selected' : '' ?>>
                                                        <?php echo $frow['nome']; ?>
                                                    </option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 dropdown">
                                        <label class="small mb-1" for="id_situacao_compra">Situação</label></br>
                                        <select class="form-control" name="id_situacao_compra" id="id_situacao_compra" required <?= (isset($dados['id_situacao_compra'])) && ($dados['id_situacao_compra'] == 26 || $dados['id_situacao_compra'] == 27) ? ' disabled' : '' ?>>
                                            <option value="" disabled selected>Selecione</option>
                                            <?php if (!empty($situacao_compra)) {
                                                foreach ($situacao_compra as $frow) { ?>
                                                    <option value="<?php echo $frow['id']; ?>" <?= (isset($dados['id_situacao_compra']) && $dados['id_situacao_compra'] == $frow['id']) ? ' selected' : '' ?>>
                                                        <?php echo $frow['nome']; ?>
                                                    </option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-4 dropdown">
                                        <label class="small mb-1" for="id_forma_pagamento">Forma Pagamento</label></br>
                                        <select class="form-control" name="id_forma_pagamento" id="id_forma_pagamento" required <?= (isset($dados['id'])) ? ' disabled' : '' ?>>
                                            <option value="" disabled selected>Selecione</option>
                                            <?php if (!empty($forma_pagamento)) {
                                                foreach ($forma_pagamento as $frow) { ?>
                                                    <option value="<?php echo $frow['id']; ?>" <?= (isset($dados['id_forma_pagamento']) && $dados['id_forma_pagamento'] == $frow['id']) ? ' selected' : '' ?>>
                                                        <?php echo $frow['nome']; ?>
                                                    </option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label class="small mb-1" for="quantidade">Parcelas</label>
                                        <input class="form-control" value="<?= isset($dados['parcela']) ? $dados['parcela'] : '' ?>" type="number" id="parcela" name="parcela" min="1" max="36" required>
                                    </div>
                                    <div class="col-md-2 form-group">
                                    <label class="small mb-1" for="vencimento">1° Vencimento</label>
                                            <input class="form-control" value="<?= isset($dados['vencimento']) ? $dados['vencimento'] : '' ?>" type="date" name="vencimento" id="vencimento" required>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label class="small mb-1" for="quantidade">Parcelamento</label>
                                        <input class="form-control" value="<?= isset($dados['parcelamento']) ? $dados['parcelamento'] : '' ?>" name="parcelamento" id="parcelamento" type="text" required />
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <input type="hidden" name="id_pessoa_selecionado" id="id_pessoa_selecionado" value="<?= isset($dados['id_pessoa']) ? str_replace('{', '[', str_replace('}', ']', $dados['id_pessoa'])) : '[]' ?>">
                                        <label class="small mb-1" for="id_pessoa">Fornecedor</label></br>
                                        <select class="form-control" name="id_pessoa" id="id_pessoa" required>
                                            <?php if (!empty($fornecedores)) {
                                                foreach ($fornecedores as $frow) { ?>
                                                    <option value="<?php echo $frow['id']; ?>">
                                                        <?php echo $frow['nome']; ?>
                                                    </option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label class="small mb-1" for="observacao">Observação</label>
                                        <textarea class="form-control" rows="4" id="observacao" name="observacao" placeholder="Digite a observação" required><?= isset($dados['observacao']) ? $dados['observacao'] : '' ?></textarea>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fa fa-save"></i>
                                        Salvar
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card border-secondary mb-2" name="dados_historico">
                            <div class="card-body">
                                <div class="form-group text-right">
                                    <a role="button" class="btn btn-primary btn-sm" href="javascript:func()" onclick="novoProduto()" class="dropdown-item" title="Novo Produto">
                                        <i class="fa fa-plus"></i> Novo Produto
                                    </a>
                                </div>

                                <table class="table table-striped table-bordered" id="tabela" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <?php if (!empty($acoes_editar) || !empty($acoes_deletar) || !empty($acoes_imagem)) { ?>
                                                <th width="90px">Ações</th>
                                            <?php } ?>
                                            <th>Produto</th>
                                            <th width="90px">Quantidade</th>
                                            <th width="110px">R$ Valor</th>
                                            <th width="120px">R$ Total</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <?php if (!empty($acoes_editar) || !empty($acoes_deletar) || !empty($acoes_imagem)) { ?>
                                                <th width="90px">Ações</th>
                                            <?php } ?>
                                            <th>Produto</th>
                                            <th width="90px">Quantidade</th>
                                            <th width="110px">R$ Valor</th>
                                            <th width="120px">R$ Total</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php if (!empty($table_historico_tbody)) {
                                            foreach ($table_historico_tbody as $num_row => $frow) { ?>
                                                <tr id="<?php echo $frow['id']; ?>">
                                                    <?php if (!empty($acoes_editar) || !empty($acoes_deletar) || !empty($acoes_imagem)) { ?>
                                                        <td>
                                                            <div class="dropdown">
                                                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    A&ccedil;&otilde;es
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    <?php if (!empty($acoes_editar)) { ?>
                                                                        <a role="button" href="<?php echo $acoes_editar . '/' . $frow['id']; ?>" class="dropdown-item">
                                                                            <i class="fa fa-pencil-alt"></i> Editar
                                                                        </a>
                                                                    <?php } ?>
                                                                    <?php if (!empty($acoes_deletar)) { ?>
                                                                        <a role="button" href="javascript:func()" id="<?php echo $frow['id']; ?>" onclick="deleteItem('<?php echo ($acoes_deletar . '\',\'' . $frow['id']); ?>')" class="delete-row dropdown-item" title="Delete">
                                                                            <i class="fa fa-trash text-danger"></i>
                                                                            <span class="text-danger"> Delete</span>
                                                                        </a>
                                                                    <?php } ?>
                                                                    <?php if (!empty($acoes_imagem)) { ?>
                                                                        <a role="button" href="javascript:func()" id="<?php echo $frow['id']; ?>" onclick="mostraImagem('<?php echo ($acoes_imagem . '\',\'' . $frow['imagem']); ?>')" class="imagem-row dropdown-item" title="Imagem">
                                                                            <i class="fas fa-image text-primary"></i>
                                                                            <span class="text-primary"> Imagem</span>
                                                                        </a>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    <?php } ?>
                                                    <td class="text-left"><?php echo $frow['descricao']; ?></td>
                                                    <td class="text-left"><?php echo $frow['quantidade']; ?></td>
                                                    <td class="text-left"><?php echo $frow['valor']; ?></td>
                                                    <td class="text-left"><?php echo $frow['total']; ?></td>
                                                </tr>
                                        <?php }
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'modal_produto.php'; ?>
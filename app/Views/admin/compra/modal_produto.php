<form action="<?= base_url($url_salvar_produtos) ?>" method="post">
    <div class="modal fade" id="addProdutoModal" tabindex="-1" role="dialog" aria-labelledby="produtoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="produtoModalLabel"><?= isset($dados['id']) ? 'Editar Produto' : 'Novo Produto' ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="id_produto_modal_selecionado" id="id_produto_modal_selecionado" value="<?= isset($dados['id_produto']) ? str_replace('{', '[', str_replace('}', ']', $dados['id_produto'])) : '[]' ?>">
                            <label class="small mb-1" for="id_produto_modal">Produto</label></br>
                            <select class="form-control" name="id_produto_modal" id="id_produto_modal" required>
                                <?php if (!empty($produtos)) {
                                    foreach ($produtos as $frow) { ?>
                                        <option value="<?php echo $frow['id']; ?>">
                                            <?php echo $frow['nome']; ?>
                                        </option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="form-group">
                                    <label class="small mb-1" for="quantidade">Quantidade</label>
                                    <input class="form-control" value="<?= isset($dados['quantidade']) ? $dados['quantidade'] : '' ?>" type="number" id="quantidade" name="quantidade" min="1" max="1000000" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="small mb-1" for="valor">R$ Valor</label>
                                <input class="form-control" value="<?= isset($dados['valor']) ? $dados['valor'] : '' ?>" name="valor" id="valor" type="text" required />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="small mb-1" for="total">R$ Total</label>
                                <input class="form-control" value="<?= isset($dados['total']) ? $dados['total'] : '' ?>" name="total" id="total" type="text" required readonly/>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button class="btn btn-primary" type="submit">
                            <i class="fa fa-save"></i> Salvar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
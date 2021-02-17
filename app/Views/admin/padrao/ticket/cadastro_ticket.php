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
                                        <label class="small mb-1" for="id_prioridade_ticket">Prioridade</label></br>
                                        <select class="form-control" name="id_prioridade_ticket" id="id_prioridade_ticket" required <?= (isset($dados['id'])) ? ' disabled' : '' ?>>
                                            <option value="" disabled selected>Selecione</option>
                                            <?php if (!empty($prioridade_ticket)) {
                                                foreach ($prioridade_ticket as $frow) { ?>
                                                    <option value="<?php echo $frow['id']; ?>" <?= (isset($dados['id_prioridade_ticket']) && $dados['id_prioridade_ticket'] == $frow['id']) ? ' selected' : '' ?>>
                                                        <?php echo $frow['nome']; ?>
                                                    </option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 dropdown">
                                        <label class="small mb-1" for="id_categoria_ticket">Departamento</label></br>
                                        <select class="form-control" name="id_categoria_ticket" id="id_categoria_ticket" required <?= (isset($dados['id'])) ? ' disabled' : '' ?>>
                                            <option value="" disabled selected>Selecione</option>
                                            <?php if (!empty($categoria_ticket)) {
                                                foreach ($categoria_ticket as $frow) { ?>
                                                    <option value="<?php echo $frow['id']; ?>" <?= (isset($dados['id_categoria_ticket']) && $dados['id_categoria_ticket'] == $frow['id']) ? ' selected' : '' ?>>
                                                        <?php echo $frow['nome']; ?>
                                                    </option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 dropdown">
                                        <label class="small mb-1" for="id_situacao_ticket">Situação</label></br>
                                        <select class="form-control" name="id_situacao_ticket" id="id_situacao_ticket" required <?= (isset($dados['id_situacao_ticket'])) && ($dados['id_situacao_ticket'] == 26 || $dados['id_situacao_ticket'] == 27) ? ' disabled' : '' ?>>
                                            <option value="" disabled selected>Selecione</option>
                                            <?php if (!empty($situacao_ticket)) {
                                                foreach ($situacao_ticket as $frow) { ?>
                                                    <option value="<?php echo $frow['id']; ?>" <?= (isset($dados['id_situacao_ticket']) && $dados['id_situacao_ticket'] == $frow['id']) ? ' selected' : '' ?>>
                                                        <?php echo $frow['nome']; ?>
                                                    </option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <?= csrf_field(); ?>
                                            <input type="hidden" name="id" id="id" value="<?= isset($dados['id']) ? $dados['id'] : '' ?>">
                                            <label class="small mb-1" for="assunto">Assunto</label>
                                            <input class="form-control" value="<?= isset($dados['assunto']) ? $dados['assunto'] : '' ?>" name="assunto" id="assunto" <?= (isset($dados['id'])) ? ' readonly' : '' ?> type="text" placeholder="Assunto" required />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card border-secondary mb-2" name="dados_descricao">
                            <div class="card-body">
                                <h5 class="header-title">Descrição</h5>
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <textarea class="form-control" rows="4" id="descricao" name="descricao" placeholder="Digite a Descrição" required><?= isset($dados['descricao']) ? $dados['descricao'] : '' ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label class="small mb-1" for="caminho_anexo">Anexo</label>
                                        <input class="form-control-file" name="caminho_anexo" id="caminho_anexo" type="file" accept="image/x-png,image/gif,image/jpeg,application/x-zip-compressed,application/zip" placeholder="Anexo" />
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
                                <h5 class="header-title">Histórico</h5>
                                <table class="table table-striped table-bordered" id="tabela" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th width="110px">Data</th>
                                            <th width="150px">Usuário</th>
                                            <th>Descrição</th>
                                            <th width="80px">Anexo</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th width="110px">Data</th>
                                            <th width="150px">Usuário</th>
                                            <th>Descrição</th>
                                            <th width="80px">Anexo</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php if (!empty($table_historico_tbody)) {
                                            foreach ($table_historico_tbody as $num_row => $frow) { ?>
                                                <tr id="<?php echo $frow['id']; ?>">
                                                    <td class="text-left"><?php echo $frow['data']; ?></td>
                                                    <td class="text-left"><?php echo $frow['usuario']; ?></td>
                                                    <td class="text-left"><?php echo $frow['descricao']; ?></td>
                                                    <td class="text-left">
                                                        <?php if (isset($frow['anexo']) && $frow['anexo'] != '') { ?>
                                                            <a class="nav-link collapsed" <?php echo 'href="' . site_url('anexo/' . $frow['anexo']) . '"'; ?>>
                                                                <div class="sb-nav-link-icon"><i class="fas fa-paperclip"></i></div>
                                                            </a>
                                                        <?php } ?>
                                                    </td>
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
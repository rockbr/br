<div class="container-fluid">
    <br />
    <div class="alert alert-<?php echo ($sucesso == '1' ? 'success' : 'primary'); ?> alert-dismissible fade show" <?php echo ($sucesso != null ? '' : 'style="display: none;"'); ?> role="alert">
        <?php echo ($sucesso == '1' ? 'Gravação realiza com sucesso!' : 'Alteração realiza com sucesso!'); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <form method="post" action="<?= $url ?>" enctype="multipart/form-data">
        <div class="card mb-4">
            <div class="card-header">
                <div class="row">
                    <div class=" col-md-6 text-left">
                        <span class="align-middle" style="font-weight:bold">
                            <i class="fas fa-table mr-1"></i> <?php echo $subtitulo; ?>
                        </span>
                    </div>
                    <div class="col-md-6 text-right">
                        <?php if (!empty($acoes_filtro)) { ?>
                            <span class="align-middle">
                                <a type="button" onclick="filtroList()" class="btn btn-secondary btn-sm"> <i class="fa fa-filter"></i> Filtros</a>
                            </span>
                        <?php } ?>
                        <?php if (!empty($acoes_novo)) { ?>
                            <span class="align-middle">
                                <a type="button" href="<?php echo $acoes_novo; ?>" class="btn btn-primary btn-sm"> <i class="fa fa-plus"></i> Novo</a>
                            </span>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="card-body">

                <?php if (!empty($acoes_filtro)) { ?>
                    <div class="card border-secondary mb-2 collapse" name="filtros_list">
                        <div class="card-body">
                            <h6 class="header-title">Filtros</h6>
                            <?php foreach ($acoes_filtro as $value) { ?>

                                <?php if ($value[0] == '<') { ?>
                                    <div class="form-row">
                                    <?php } ?>

                                    <div class="form-group col-md-6">
                                        <label class="small mb-1" for=<?= $value[4]; ?>><?= $value[1]; ?></label>
                                        <input class="form-control form-control-sm" value="<?= $value[2] ?>" name="<?= $value[4]; ?>" id="<?= $value[4]; ?>" type="<?= $value[3]; ?>" placeholder="<?= $value[1]; ?>" />
                                    </div>

                                    <?php if ($value[0] == '>') { ?>
                                    </div>
                                <?php } ?>

                            <?php } ?>
                            <div class="text-right">
                                <input type="submit" class="btn btn-primary btn-sm" name="paginacao" id="paginacao" value="Pesquisar" />
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <div class="table-responsive">
                    <!-- Button trigger modal -->
                    <table class="table table-striped table-bordered" id="lista" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <?php if (!empty($acoes_editar) || !empty($acoes_deletar) || !empty($acoes_imagem)) { ?>
                                    <th width="90px">Ações</th>
                                <?php } ?>

                                <?php if (!empty($table_thead)) {
                                    foreach ($table_thead as $frow) { ?>
                                        <th><?php echo $frow; ?></th>
                                <?php }
                                } ?>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <?php if (!empty($acoes_editar) || !empty($acoes_deletar) || !empty($acoes_imagem)) { ?>
                                    <th>Ações</th>
                                <?php } ?>

                                <?php if (!empty($table_thead)) {
                                    foreach ($table_thead as $frow) { ?>
                                        <th><?php echo $frow; ?></th>
                                <?php }
                                } ?>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php if (!empty($table_tbody)) {
                                foreach ($table_tbody as $num_row => $frow) { ?>
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
                                        <?php foreach ($frow as $innerRow => $value) { ?>
                                            <?php if ($innerRow != "id" && $innerRow != "imagem") { ?>
                                                <td class="text-left"><?php echo $value; ?></td>
                                            <?php } ?>
                                        <?php } ?>
                                    </tr>
                            <?php }
                            } ?>
                        </tbody>
                    </table>
                    <?php include 'includes/admin_paginacao.php'; ?>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modal -->
<div class="modal fade" id="imagemModal" tabindex="-1" role="dialog" aria-labelledby="imagemModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imagemModalLabel">Foto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img src="" id="foto" name="foto" class="rounded mx-auto d-block" alt="Foto" width="250" height="300">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
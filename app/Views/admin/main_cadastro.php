<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card border-1 my-2">
                <div class="card-header">
                    <h1 class="text-center my-2"><?php echo $titulo; ?></h1>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger alert-dismissible fade show"
                        <?php echo (isset($erro) ? '' : 'style="display: none;"'); ?> role="alert">
                        <?php echo ($erro) ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form method="post" action="<?= base_url($url_salvar) ?>" enctype="multipart/form-data">
                        <div class="form-row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <?= csrf_field(); ?>
                                    <input type="hidden" name="id" id="id"
                                        value="<?= isset($dados['id']) ? $dados['id'] : '' ?>">
                                    <label class="small mb-1" for="nome">Nome</label>
                                    <input class="form-control"
                                        value="<?= isset($dados['nome']) ? $dados['nome'] : '' ?>"
                                        name="nome" id="nome" type="text" placeholder="Digite o nome" required />
                                </div>
                            </div>
                        </div>

                        <div class="text-right">
                            <button class="btn btn-primary" type="submit">
                                <i class="fa fa-save"></i>
                                <?= isset($dados['id']) ? ' Alterar' : ' Cadastrar' ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
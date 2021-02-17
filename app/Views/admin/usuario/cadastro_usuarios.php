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

                        <div class="card border-secondary mb-2">
                            <div class="card-body">
                                <h5 class="header-title">Dados Gerais</h5>
                                <div class="text-right">
                                    <div class="custom-control custom-switch custom-control-inline">
                                        <input type="checkbox" class="custom-control-input" name="acesso_admin" id="acesso_admin" <?= isset($dados['acesso_admin']) && $dados['acesso_admin'] == 't' ? 'checked' : '' ?>>
                                        <label class="custom-control-label" for="acesso_admin">Acesso Admin</label>
                                    </div>

                                    <div class="custom-control custom-switch custom-control-inline">
                                        <input type="checkbox" class="custom-control-input" name="bloqueado" id="bloqueado" <?= isset($dados['bloqueado']) && $dados['bloqueado'] == 't' ? 'checked' : '' ?>>
                                        <label class="custom-control-label" for="bloqueado">Bloqueado</label>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <?= csrf_field(); ?>
                                            <input type="hidden" name="id" id="id" value="<?= isset($dados['id']) ? $dados['id'] : '' ?>">
                                            <label class="small mb-1" for="nome">Nome</label>
                                            <input class="form-control" value="<?= isset($dados['nome']) ? $dados['nome'] : '' ?>" name="nome" id="nome" type="text" placeholder="Digite seu nome" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6 dropdown">
                                        <label class="small mb-1" for="id_grupo_usuario">Grupo</label></br>
                                        <select class="form-control" name="id_grupo_usuario" id="id_grupo_usuario" required>
                                            <option value="" disabled selected>Selecione</option>
                                            <?php if (!empty($grupo_usuario)) {
                                                foreach ($grupo_usuario as $frow) { ?>
                                                    <option value="<?php echo $frow['id']; ?>" <?= (isset($dados['id_grupo_usuario']) && $dados['id_grupo_usuario'] == $frow['id']) ? ' selected' : '' ?>>
                                                        <?php echo $frow['nome']; ?>
                                                    </option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label class="small mb-1" for="email">E-mail</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-envelope"></i></div>
                                        </div>
                                        <input class="form-control" value="<?= isset($dados['login']) ? $dados['login'] : '' ?>" name="email" id="email" type="email" aria-describedby="emailHelp" placeholder="E-mail" <?= isset($dados['id']) ? ' readonly' : '' ?> required />
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group"><label class="small mb-1" for="senha">Senha</label>
                                            <input class="form-control" name="senha" id="senha" type="password" placeholder="Digite a senha" required /></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group"><label class="small mb-1" for="senhaConfirmacao">Confirmação</label>
                                            <input class="form-control" name="senhaConfirmacao" id="senhaConfirmacao" type="password" placeholder="Confirmação de Senha" required /></div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <input type="hidden" name="id_categoria_lista_selecionado" id="id_categoria_lista_selecionado" value="<?= isset($dados['id_categoria_lista']) ? str_replace('{', '[', str_replace('}', ']', $dados['id_categoria_lista'])) : '[]' ?>">
                                        <label class="small mb-1" for="id_categoria_lista">Categorias</label></br>
                                        <select class="form-control" multiple="multiple" name="id_categoria_lista[]" id="id_categoria_lista">
                                            <?php if (!empty($categorias)) {
                                                foreach ($categorias as $frow) { ?>
                                                    <option value="<?php echo $frow['id']; ?>">
                                                        <?php echo $frow['nome']; ?>
                                                    </option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>              
                        <div class="text-right">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i><?= isset($dados['id']) ? ' Alterar' : ' Cadastrar' ?></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
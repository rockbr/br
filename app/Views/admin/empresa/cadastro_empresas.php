<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card border-1 my-2">
                <div class="card-header">
                    <h2 class="text-center my-2"><?php echo $titulo; ?></h2>
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
                                <h5 class="header-title">Dados principais</h5>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <?= csrf_field(); ?>
                                        <label class="small mb-1" for="nome">Nome</label>
                                        <input class="form-control" value="<?= isset($dados['nome']) ? $dados['nome'] : '' ?>" name="nome" id="nome" type="text" placeholder="Nome" required />
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="small mb-1" for="cnpj">CNPJ</label>
                                        <input class="form-control" value="<?= isset($dados['cnpj']) ? $dados['cnpj'] : '' ?>" name="cnpj" id="cnpj" type="text" onkeypress="$(this).mask('00.000.000/0000-00')" placeholder="CNPJ" />
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="small mb-1" for="ie">Inscrição Estadual</label>
                                        <input class="form-control" value="<?= isset($dados['ie']) ? $dados['ie'] : '' ?>" name="ie" id="ie" type="text" placeholder="Inscrição Estadual" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-secondary mb-2">
                            <div class="card-body">
                                <h5 class="header-title">Informações de contato</h5>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="small mb-1" for="email">E-mail</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fa fa-envelope"></i></div>
                                            </div>
                                            <input type="text" class="form-control" value="<?= isset($dados['email']) ? $dados['email'] : '' ?>"  id="email" name="email" type="email" aria-describedby="emailHelp" placeholder="E-mail">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="small mb-1" for="twitter">Twitter</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fab fa-twitter"></i></div>
                                            </div>
                                            <input type="text" class="form-control" value="<?= isset($dados['twitter']) ? $dados['twitter'] : '' ?>" id="twitter" name="twitter" placeholder="Twitter">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="small mb-1" for="facebook">Facebook</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fab fa-facebook-f"></i></div>
                                            </div>
                                            <input type="text" class="form-control" value="<?= isset($dados['facebook']) ? $dados['facebook'] : '' ?>" id="facebook" name="facebook" type="facebook" placeholder="Facebook">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="small mb-1" for="instagram">Instagram</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fab fa-instagram"></i></div>
                                            </div>
                                            <input type="text" class="form-control" value="<?= isset($dados['instagram']) ? $dados['instagram'] : '' ?>" id="instagram" name="instagram" placeholder="Instagram">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="small mb-1" for="telefone_fixo">Telefone Fixo</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fa fa-phone"></i></div>
                                            </div>
                                            <input type="text" class="form-control" value="<?= isset($dados['telefone_fixo']) ? $dados['telefone_fixo'] : '' ?>" id="telefone_fixo" name="telefone_fixo" type="telefone" onkeypress="$(this).mask('(00) 0000-0000')" placeholder="Telefone Fixo">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="small mb-1" for="telefone_celular">Telefone Celular</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fa fa-mobile"></i></div>
                                            </div>
                                            <input type="text" class="form-control" value="<?= isset($dados['telefone_celular']) ? $dados['telefone_celular'] : '' ?>"  id="telefone_celular" name="telefone_celular" onkeypress="$(this).mask('(00) 00000-0000')" placeholder="Telefone Celular">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-secondary mb-2">
                            <div class="card-body">
                                <h5 class="header-title">Endereço</h5>
                                <div class="form-row">
                                    <div class="form-group col-md-2">
                                        <label class="small mb-1" for="auto_cep">CEP</label>
                                        <input class="form-control" value="<?= isset($dados['auto_cep']) ? $dados['auto_cep'] : '' ?>" id="auto_cep" name="auto_cep" onkeypress="$(this).mask('00.000-000')" placeholder="CEP" type="text" />
                                    </div>
                                    <div class="form-group col-md-9">
                                        <label class="small mb-1" for="logradouro">Endereço</label>
                                        <input class="form-control" value="<?= isset($dados['logradouro']) ? $dados['logradouro'] : '' ?>" placeholder="Endereço" id="logradouro" name="logradouro" type="text" />
                                    </div>
                                    <div class="form-group col-md-1">
                                        <label class="small mb-1" for="numero">Número</label>
                                        <input class="form-control" value="<?= isset($dados['numero']) ? $dados['numero'] : '' ?>" name="numero" id="numero" placeholder="N°" type="text" />
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="small mb-1" for="bairro">Bairro</label>
                                        <input class="form-control" value="<?= isset($dados['bairro']) ? $dados['bairro'] : '' ?>" name="bairro" id="bairro" placeholder="Bairro" type="text" />
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="small mb-1" for="complemento">Complemento</label>
                                        <input class="form-control" value="<?= isset($dados['complemento']) ? $dados['complemento'] : '' ?>" name="complemento" placeholder="Complemento" id="complemento" type="text" />
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-8">
                                        <label class="small mb-1" for="auto_cidade">Cidade</label>
                                        <input type="hidden" name="id_auto_cidade" id="id_auto_cidade" value="">
                                        <input class="form-control" value="<?= isset($dados['auto_cidade']) ? $dados['auto_cidade'] : '' ?>" name="auto_cidade" placeholder="Cidade" id="auto_cidade" type="text" />
                                    </div>
                                    <div class="col-md-4 dropdown">
                                        <label class="small mb-1" for="id_estado">UF</label></br>
                                        <select class="form-control" name="id_estado" id="id_estado">
                                            <option value="" disabled selected>Selecione</option>
                                            <?php if (!empty($estados)) {
                                                foreach ($estados as $frow) { ?>
                                                    <option value="<?php echo $frow['id']; ?>" <?= (isset($dados['id_estado']) && $dados['id_estado'] == $frow['id']) ? ' selected' : '' ?>>
                                                        <?php echo $frow['nome']; ?>
                                                    </option>
                                            <?php }
                                            } ?>
                                        </select>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-secondary mb-2">
                            <div class="card-body">
                                <h5 class="header-title">Observações</h5>
                                <div class="form-row">
                                    <div class="form-group col-md-12">                                        
                                        <textarea class="form-control" rows="5" id="observacao" name="observacao"><?= isset($dados['observacao']) ? $dados['observacao'] : '' ?></textarea>
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
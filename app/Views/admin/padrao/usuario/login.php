<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Fetx</title>
    <link rel="stylesheet" href="<?= base_url("css/styles.css") ?>">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous">
    </script>
</head>

<body class="sb-sidenav-dark">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5 text-dark">
                                <div class="card-header">
                                    <div class="alert alert-danger alert-dismissible fade show" <?php echo (isset($erro) ? '' : 'style="display: none;"'); ?> role="alert">
                                        <?php echo ($erro) ?>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <h3 class="text-center font-weight my-4 ">Login</h3>
                                </div>
                                <div class="card-body">
                                    <form method="post" action="<?= base_url('login') ?>" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <?= csrf_field(); ?>
                                            <label class="small mb-1" for="login"><i class="fa fa-envelope" aria-hidden="true"></i> E-mail</label>
                                            <input class="form-control py-4" value="<?= isset($login) ? $login : '' ?>" id="login" name="login" type="email" placeholder="Digite seu e-mail" required />
                                        </div>
                                        <div class="form-group">
                                            <label class="small mb-1" for="senha"><i class="fa fa-lock" aria-hidden="true"></i> Senha</label>
                                            <input class="form-control py-4" value="<?= isset($senha) ? $senha : '' ?>" id="senha" name="senha" type="password" placeholder="Digite sua senha" required autocomplete="cc-csc" />
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" id="lembrete" name="lembrete" type="checkbox" <?= isset($lembrete) ? $lembrete : '' ?> />
                                                <label class="custom-control-label" for="lembrete">Lembrar senha</label>
                                            </div>
                                        </div>
                                        <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <a href="<?php echo site_url('resetsenha'); ?>" class="small">Esqueceu a senha?</a>
                                            <button class="btn btn-primary" type="submit">Login</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutAuthentication_footer">
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; 2020</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <!--<script src="js/scripts.js"></script>-->
</body>

</html>
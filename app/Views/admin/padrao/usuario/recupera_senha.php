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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
</head>

<body class="sb-sidenav-dark">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5 text-dark ">
                                <div class="card-header">
                                    <div class="alert alert-<?php echo ($sucesso == '1' ? 'success' : 'danger'); ?> fade show" <?php echo ($sucesso != null ? '' : 'style="display: none;"'); ?> role="alert">
                                        <?php echo ($sucesso == '1' ? 'Um link para redefinição de senha foi enviado para seu e-mail. Por questões de segurança o link é valido por 2 horas.' : 'Login não localizado'); ?>
                                    </div>

                                    <?php echo ($sucesso == '1'
                                        ? '<a class="small" href="' . site_url() . '">Retornar ao login</a>'
                                        : '<h3 class="text-center font-weight my-4">Recuperação de senha</h3>'); ?>

                                </div>
                                <div class="card-body" <?php echo ($sucesso == '1' ? 'style="display: none;"' : ''); ?>>
                                    <div class="small mb-3 text-muted">Digite seu login e enviaremos um link para redefinir sua senha.</div>
                                    <form method="post" action="<?= base_url('emailresetsenha') ?>" enctype="multipart/form-data">
                                        <?= csrf_field(); ?>
                                        <div class="form-group"><label class="small mb-1" for="login">Login</label>
                                            <input class="form-control py-4" id="login" name="login" type="email" aria-describedby="emailHelp" placeholder="Digite seu e-mail" required /></div>
                                        <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <a class="small" href="<?= site_url() ?>">Retornar ao login</a>
                                            <button class="btn btn-primary" type="submit">Enviar E-mail</button>
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
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>
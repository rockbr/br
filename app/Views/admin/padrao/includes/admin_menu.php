<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading">Menu</div>
                    <a class="nav-link collapsed" <?php echo 'href="' . site_url('download') . '"'; ?>>
                        <div class="sb-nav-link-icon"><i class="fas fa-download"></i></div>
                        Download
                    </a>
                    <a class="nav-link collapsed" <?php echo 'href="' . site_url('consultatickets') . '"'; ?>>
                        <div class="sb-nav-link-icon"><i class="fas fa-ticket-alt"></i></div>
                        Tickets
                    </a>
                    <a class="nav-link collapsed" <?php echo 'href="' . site_url('consultacompras') . '"'; ?>>
                        <div class="sb-nav-link-icon"><i class="fas fa-store-alt"></i></div>
                        Compras
                    </a>
                    <div class="sb-sidenav-menu-heading">Administração</div>
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCadastro" aria-expanded="false" aria-controls="collapseCadastro">
                        <div class="sb-nav-link-icon"><i class="fas fa-pen-square"></i></div>
                        Cadastros
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseCadastro" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <?php echo '<a class="nav-link" href="' . site_url('consultagruposusuarios') . '">Grupo Usuários</a>'; ?>
                        </nav>
                        <?php if ($session->get('super') == 'true' || $session->get('super') == 't') : ?>
                            <?php echo '<nav class="sb-sidenav-menu-nested nav">'; ?>
                            <?php echo '<a class="nav-link" href="' . site_url('consultaempresas') . '">Empresas</a>'; ?>
                            <?php echo '</nav>'; ?>
                        <?php endif; ?>
                        <nav class="sb-sidenav-menu-nested nav">
                            <?php echo '<a class="nav-link" href="' . site_url('consultausuarios') . '">Usuários</a>'; ?>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="sb-sidenav-footer">
                <div class="small">Logado como: </div>
                <?php echo $session->get('nome_usuario'); ?>
            </div>
        </nav>
    </div>
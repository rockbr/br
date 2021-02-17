<div class="container-fluid">
    <h1 class="mt-4">Download</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><?php echo '<a href="' . site_url('home') . '">'; ?>Home</a></li>
        <li class="breadcrumb-item active">Download</li>
    </ol>
    <div class="row">
        <?= csrf_field(); ?>
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-download mr-1"></i>Arquivos para Download</div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-mobile"></i> App Ponto Certo</span>
                            
                            <span>
                            <a href="https://api.whatsapp.com/send?text=https://play.google.com/store/apps/details?id=br.com.fetx.tradecheckapp">
                                <span class="badge badge-success badge-pill"><i class="fab fa-whatsapp"></i></span>
                            </a>

                            <a href="https://play.google.com/store/apps/details?id=br.com.fetx.tradecheckapp">
                                <span class="badge badge-primary badge-pill"><i class="fas fa-download"></i></span>
                            </a>
                            </span>
                        </li>
                        <li class="list-group-item list-group-item-action list-group-item-secondary d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-laptop-house"></i> AnyDesk</span>
                            <a href=<?php echo '"' . site_url('download/anydesk.exe') . '"'; ?>>
                                <span class="badge badge-primary badge-pill"><i class="fas fa-download"></i></span>
                            </a>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
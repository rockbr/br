<div class="container-fluid">
    <h1 class="mt-4">Gráficos</h1>
    <ol class="breadcrumb mb-4">        
        <li class="breadcrumb-item active">Pagar e Receber</li>
    </ol>
    <div class="row">
        <?= csrf_field(); ?>
        <div class="col-lg-6">
            <div class="card mb-4">

                <div class="card-header"><i class="fas fa-chart-bar mr-1"></i>Pagar</div>
                <div class="card-body">
                    <div id="chartReportPagarPorMes">
                        <canvas id="chartPagarPorMes" width="100%" height="50"></canvas>
                    </div>
                    <div class="input-group card-title">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                        </div>
                        <select class="form-control" name="mes_ano" id="mes_ano" required>
                            <option value="" disabled selected>Selecione</option>
                            <?php if (!empty($pagar_mes_ano)) {
                                foreach ($pagar_mes_ano as $frow) { ?>
                                    <option value="<?php echo $frow['mes_ano']; ?>" <?= (isset($dados['mes_ano']) && $dados['mes_ano'] == $frow['mes_ano']) ? ' selected' : '' ?>>
                                        <?php echo $frow['mes_ano']; ?>
                                    </option>
                            <?php }
                            } ?>
                        </select>
                    </div>
                </div>                
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-chart-bar mr-1"></i>Receber</div>
                <div class="card-body">
                <div id="chartReportReceberPorMes">
                        <canvas id="chartReceberPorMes" width="100%" height="50"></canvas>
                    </div>

                    <div class="input-group card-title">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                        </div>
                        <select class="form-control" name="mes_ano" id="mes_ano" required>
                            <option value="" disabled selected>Selecione</option>
                            <?php if (!empty($receber_mes_ano)) {
                                foreach ($receber_mes_ano as $frow) { ?>
                                    <option value="<?php echo $frow['mes_ano']; ?>" <?= (isset($dados['mes_ano']) && $dados['mes_ano'] == $frow['mes_ano']) ? ' selected' : '' ?>>
                                        <?php echo $frow['mes_ano']; ?>
                                    </option>
                            <?php }
                            } ?>
                        </select>
                    </div>

                </div>                
            </div>
        </div>
    </div>
</div>
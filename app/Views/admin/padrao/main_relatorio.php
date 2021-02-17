
<div class="container">

<div class="table-responsive">
    <table class="table table-bordered">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Cidade</th>
            <th>Estado</th>
            <th>Jan</th>
            <th>Fev</th>
            <th>Mar</th>
            <th>Abr</th>
            <th>Mai</th>
            <th>Jun</th>
            <th>Jul</th>
            <th>Ago</th>
            <th>Set</th>
            <th>Out</th>
            <th>Nov</th>
            <th>Dez</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($promotoras as $promotor): ?>
        <tr>
            <?php echo "<td scope='row'>{$promotor->nome}</td>" ?>
            <?php echo "<td>{$promotor->cidade}</td>" ?>
            <?php echo "<td>{$promotor->id_estado}</td>" ?>
            <?php echo "<td>{$promotor->jan}</td>" ?>
            <?php echo "<td>{$promotor->fev}</td>" ?>
            <?php echo "<td>{$promotor->mar}</td>" ?>
            <?php echo "<td>{$promotor->abr}</td>" ?>
            <?php echo "<td>{$promotor->mai}</td>" ?>
            <?php echo "<td>{$promotor->jun}</td>" ?>
            <?php echo "<td>{$promotor->jul}</td>" ?>
            <?php echo "<td>{$promotor->ago}</td>" ?>
            <?php echo "<td>{$promotor->set}</td>" ?>
            <?php echo "<td>{$promotor->out}</td>" ?>
            <?php echo "<td>{$promotor->nov}</td>" ?>
            <?php echo "<td>{$promotor->dez}</td>" ?>
            <?php echo "<td>{$promotor->total}</td>" ?>
            
        </tr>
        <?php endforeach; ?>
    </tbody>
       
</div>    

<hr>
</div> 
<h2 class="text-center">Promotoras</h2>
<a href="<?php echo base_url(); ?>/Relatorio/gerarExcel" class="botao btn btn-primary btn-sm" id="geraExcel" target="blank"> Gerar Excel</a>     

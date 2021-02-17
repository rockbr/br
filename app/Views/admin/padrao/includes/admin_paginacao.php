    <?= csrf_field(); ?>
    <div class="dataTables_info" id="paginacao_info" role="status" aria-live="polite"><?php echo (!empty($table_tbody) ? 'Mostrando de ' . $paginacao_de . ' até ' . $paginacao_ate . ' de ' . $paginacao_total . ' registros' : '') ?></div>


    <div class="btn-group" role="group" style="display: flex; justify-content: flex-end" aria-label="Paginacao">
        <input type="hidden" id="pagina_anterior" name="pagina_anterior" value=<?php echo ($pagina == 0 ? 0 : ($pagina - 1)); ?>>
        <input type="hidden" id="pagina_proximo" name="pagina_proximo" value=<?php echo ($pagina + 1); ?>>

        <div class="btn-group" role="group">
            <select class="btn btn-secondary btn-sm" name="pagina_limite" id="pagina_limite" onchange="this.form.submit()">
                <option value="10" <?= ($limite == '10') ? ' selected' : '' ?>>Mostrar 10</option>
                <option value="25" <?= ($limite == '25') ? ' selected' : '' ?>>Mostrar 25</option>
                <option value="50" <?= ($limite == '50') ? ' selected' : '' ?>>Mostrar 50</option>
                <option value="75"  <?= ($limite == '75') ? ' selected' : '' ?>>Mostrar 75</option>
                <option value="100" <?= ($limite == '100') ? ' selected' : '' ?>>Mostrar 100</option>
            </select>
        </div>

        <?php if ($pagina != 0) { ?>
            <input type="submit" class="btn btn-primary btn-sm" name="paginacao" id="paginacao" value="Anterior" />
        <?php } ?>
        <?php if ($paginacao_ate != $paginacao_total) { ?>
            <input type="submit" class="btn btn-info btn-sm" name="paginacao" id="paginacao" value="Próximo" />
        <?php } ?>
    </div>
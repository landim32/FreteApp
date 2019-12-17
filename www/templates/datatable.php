<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-table"></i> Exemplo de DataTable</h3>
    </div>
    <div class="panel-body">
        <table class="table table-striped table-hover table-responsive datatable">
            <thead>
            <tr>
                <th><a href="#">Nome</a></th>
                <th><a href="#">Tipo</a></th>
                <th><a href="#">Idade</a></th>
                <th><a href="#">Situação</a></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($pessoas as $pessoa) : ?>
            <tr>
                <td><a href="#"><?php echo $pessoa->nome; ?></a></td>
                <td><?php echo $pessoa->tipo; ?></td>
                <td><?php echo $pessoa->idade; ?></td>
                <td><?php echo $pessoa->situacao; ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
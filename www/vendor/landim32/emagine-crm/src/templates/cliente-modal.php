<?php

namespace Emagine\CRM;

use Emagine\CRM\BLL\ClienteBLL;
$regraCliente = new ClienteBLL();

?>
<div id="clienteModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" style="max-width: 400px;" role="document">
        <div class="modal-content">
            <form class="form-vertical" method="POST">
                <input type="hidden" id="id_cliente" name="id_cliente" value="0">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-user-circle"></i> Novo cliente</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-fw fa-user"></i></span>
                            <input type="text" id="nome" name="nome" class="form-control" placeholder="Preencha o nome" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-fw fa-envelope"></i></span>
                            <input type="email" id="email1" name="email1" class="form-control" placeholder="Preencha o email" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-fw fa-phone"></i></span>
                                    <input type="text" id="telefone1" name="telefone1" class="form-control telefone" placeholder="Telefone" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-fw fa-phone"></i></span>
                                    <input type="text" id="telefone2" name="telefone2" class="form-control telefone" placeholder="Celular" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-fw fa-tags"></i></span>
                            <input type="text" id="tags" name="tags" class="form-control tagsinput" placeholder="Preencha as tags" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-fw fa-building"></i></span>
                                    <input type="text" id="empresa" name="empresa" class="form-control" placeholder="Empresa (opcional)" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <select id="cod_situacao" name="cod_situacao" class="form-control">
                                    <?php foreach ($regraCliente->listarSituacao() as $cod_situacao => $situacao) : ?>
                                    <option value="<?php echo $cod_situacao; ?>"><?php echo $situacao; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-remove"></i> Fechar</button>
                    <button type="button" class="btn btn-primary cliente-submit">Gravar <i class="fa fa-chevron-right"></i></button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
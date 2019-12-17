<?php
namespace Emagine\Frete;

use Emagine\Base\EmagineApp;
use Emagine\Frete\Model\TipoVeiculoInfo;
use Emagine\Login\Controls\UsuarioPerfil;

/**
 * @var EmagineApp $app
 * @var TipoVeiculoInfo $veiculo
 * @var array<int,string> $tipos
 */
?>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?php echo UsuarioPerfil::render(); ?>
            <?php echo $app->getMenu("lateral"); ?>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-car"></i> Veículo</h3>
                </div>
                <div class="panel-body">
                    <form method="post" action="<?php echo $app->getBaseUrl() . "/veiculo"; ?>" enctype="multipart/form-data" class="form-horizontal">
                        <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
                        <input type="hidden" name="id_tipo" value="<?php echo $veiculo->getId(); ?>" />
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="foto">Imagem:</label>
                            <div class="col-md-9">
                                <input type="file" id="foto" name="foto" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <label class="col-md-3 control-label" for="nome">Nome:</label>
                            <div class="col-md-9">
                                <input type="text" id="nome" name="nome" class="form-control" value="<?php echo $veiculo->getNome(); ?>" />
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <label class="col-md-3 control-label" for="cod_tipo">Tipo:</label>
                            <div class="col-md-9">
                                <select id="cod_tipo" name="cod_tipo" class="form-control">
                                    <option value="">--selecione--</option>
                                    <?php foreach ($tipos as $codTipo => $nomeTipo) : ?>
                                        <option value="<?php echo $codTipo; ?>" <?php
                                        echo ($veiculo->getCodTipo() == $codTipo) ? " selected='selected'" : "";
                                        ?>><?php echo $nomeTipo; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <label class="col-md-3 control-label" for="capacidade">Capacidade:</label>
                            <div class="col-md-9">
                                <input type="number" id="capacidade" name="capacidade" class="form-control" value="<?php echo $veiculo->getCapacidade(); ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12 text-right">
                                <a href="<?php echo $app->getBaseUrl() . "/veiculo/listar"; ?>" class="btn btn-lg btn-default"><i class="fa fa-chevron-left"></i> Voltar</a>
                                <button type="submit" class="btn btn-lg btn-primary"><i class="fa fa-check"></i> Gravar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
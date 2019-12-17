<?php
namespace Emagine\Produto;

use Emagine\Base\EmagineApp;
use Emagine\Frete\Model\TipoVeiculoInfo;
use Emagine\Login\Controls\UsuarioPerfil;

/**
 * @var EmagineApp $app
 * @var TipoVeiculoInfo $veiculo
 */

$urlAlterar = $app->getBaseUrl() . "/veiculo/" .
    strtolower(sanitize_slug($veiculo->getNome())) . "_" .
    $veiculo->getId() . "/alterar";
$urlExcluir = $app->getBaseUrl() . "/veiculo/" .
    strtolower(sanitize_slug($veiculo->getNome())) . "_" .
    $veiculo->getId() . "/excluir";
?>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?php echo UsuarioPerfil::render(); ?>
            <?php echo $app->getMenu("lateral"); ?>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-9">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <?php if (!isNullOrEmpty($veiculo->getFoto())) : ?>
                                        <img src="<?php echo $veiculo->getFotoUrl(80, 80); ?>" alt="<?php echo $veiculo->getNome(); ?>" />
                                    <?php else : ?>
                                        <i class="fa fa-balance-scale" style="font-size: 80px;"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-9">
                                    <h2><?php echo $veiculo->getNome(); ?></h2>
                                </div>
                            </div>
                            <hr />
                            <dl class="dl-horizontal">
                                <dt>Id:</dt>
                                <dd><?php echo $veiculo->getId(); ?></dd>
                                <dt>Nome:</dt>
                                <dd><?php echo $veiculo->getNome(); ?></dd>
                                <dt>Capacidade:</dt>
                                <dd><?php echo $veiculo->getCapacidade(); ?></dd>
                            </dl>
                            <hr />
                            <div class="text-right">
                                <a href="<?php echo $app->getBaseUrl() . "/veiculo/listar"; ?>" class="btn btn-lg btn-default">
                                    <i class="fa fa-chevron-left"></i> Voltar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3" style="padding-top: 40px;">
                    <a href="<?php echo $urlAlterar; ?>"><i class="fa fa-pencil"></i> Alterar</a><br />
                    <a class="confirm" href="<?php echo $urlExcluir; ?>"><i class="fa fa-trash"></i> Excluir</a>
                </div>
            </div>

        </div>
    </div>
</div>
<?php
namespace Emagine\Frete;

use Emagine\Base\EmagineApp;
use Emagine\Frete\Model\TipoVeiculoInfo;
use Emagine\Login\Controls\UsuarioPerfil;

/**
 * @var EmagineApp $app
 * @var TipoVeiculoInfo[] $veiculos
 * @var string $erro
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
                <div class="panel-body">
                    <h3 style="margin-top: 5px; margin-bottom: 5px;"><i class="fa fa-car"></i> Veículos</h3>
                    <hr />
                    <?php if (!isNullOrEmpty($erro)) : ?>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <i class="fa fa-warning"></i> <?php echo $erro; ?>
                        </div>
                    <?php endif; ?>
                    <table class="table table-striped table-hover table-responsive">
                        <thead>
                        <tr>
                            <th><a href="#">Imagem</a></th>
                            <th><a href="#">Veículo</a></th>
                            <th class="text-right"><a href="#">Capacidade</a></th>
                            <th class="text-right"><a href="#"><i class="fa fa-cog"></i></a></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (count($veiculos) > 0) : ?>
                            <?php foreach ($veiculos as $veiculo) : ?>
                                <?php
                                $slug = strtolower(sanitize_slug($veiculo->getNome())) . "_" . $veiculo->getId();
                                $urlVeiculo = $app->getBaseUrl() . "/veiculo/" . $slug;
                                $urlExcluir = $urlVeiculo . "/excluir";
                                ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo $urlVeiculo; ?>">
                                            <img src="<?php echo $veiculo->getFotoUrl(20, 20); ?>" alt="<?php echo $veiculo->getNome(); ?>" />
                                        </a>
                                    </td>
                                    <td><a href="<?php echo $urlVeiculo; ?>"><?php echo $veiculo->getNome(); ?></a></td>
                                    <td class="text-right">
                                        <a href="<?php echo $urlVeiculo; ?>">
                                            <?php echo $veiculo->getCapacidade(); ?>
                                        </a>
                                    </td>
                                    <td class="text-right">
                                        <a class="confirm" href="<?php echo $urlExcluir; ?>">
                                            <i class="fa fa-remove"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="2">
                                    <i class="fa fa-warning"></i> Nenhum veículo cadastrado!
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-3" style="padding-top: 40px;">
            <a href="<?php echo $app->getBaseUrl() . "/veiculo/novo"; ?>"><i class="fa fa-plus"></i> Novo Veículo</a><br />
        </div>
    </div>
</div>
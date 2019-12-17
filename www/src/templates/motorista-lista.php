<?php

namespace Emagine\Frete;

use Emagine\Base\EmagineApp;
use Emagine\Frete\Model\MotoristaInfo;
use Emagine\Login\Controls\UsuarioPerfil;
use Emagine\Login\Model\UsuarioInfo;

/**
 * @var EmagineApp $app
 * @var MotoristaInfo[] $motoristas
 */
?>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?php echo UsuarioPerfil::render(); ?>
            <?php echo $app->getMenu("lateral"); ?>
        </div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3 style="margin-top: 5px; margin-bottom: 5px;"><i class="fa fa-car"></i> Motoristas</h3>
                    <hr />
                    <table class="table table-striped table-hover table-responsive">
                        <thead>
                        <tr>
                            <th><a href="#">Nome</a></th>
                            <th><a href="#">Veículo</a></th>
                            <th><a href="#">Placa</a></th>
                            <th><a href="#">Disponibilidade</a></th>
                            <th><a href="#">Posição</a></th>
                            <th><a href="#">Situação</a></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($motoristas as $motorista) : ?>
                            <?php
                            $usuario = $motorista->getUsuario();
                            $url = $app->getBaseUrl() . "/motorista/" . $usuario->getSlug();
                            ?>
                            <tr>
                                <td><a href="<?php echo $url; ?>"><?php echo $usuario->getNome(); ?></a></td>
                                <td><a href="<?php echo $url; ?>"><?php echo $motorista->getVeiculo(); ?></a></td>
                                <td><a href="<?php echo $url; ?>"><?php echo $motorista->getPlaca(); ?></a></td>
                                <td><a href="<?php echo $url; ?>"><?php echo $motorista->getDisponibilidadeStr(); ?></a></td>
                                <td><a href="<?php echo $url; ?>"><?php echo $motorista->getPosicaoStr(); ?></a></td>
                                <td><a href="<?php echo $url; ?>"><?php echo $motorista->getSituacaoStr(); ?></a></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

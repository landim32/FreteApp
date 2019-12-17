<?php

namespace Emagine\Frete;

use Emagine\Base\EmagineApp;
use Emagine\Login\Controls\UsuarioPerfil;
use Emagine\Login\Model\UsuarioInfo;

/**
 * @var EmagineApp $app
 * @var UsuarioInfo[] $usuarios
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
                    <h3 style="margin-top: 5px; margin-bottom: 5px;"><i class="fa fa-user-circle"></i> Clientes</h3>
                    <hr />
                    <table class="table table-striped table-hover table-responsive">
                        <thead>
                        <tr>
                            <th><a href="#">Nome</a></th>
                            <th><a href="#">Email</a></th>
                            <th><a href="#">Telefone</a></th>
                            <th><a href="#">Situação</a></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($usuarios as $usuario) : ?>
                            <?php $url = $app->getBaseUrl() . "/cliente/" . $usuario->getSlug(); ?>
                            <tr>
                                <td><a href="<?php echo $url; ?>"><?php echo $usuario->getNome(); ?></a></td>
                                <td><a href="<?php echo $url; ?>"><?php echo $usuario->getEmail(); ?></a></td>
                                <td><a href="<?php echo $url; ?>"><?php echo $usuario->getTelefoneStr(); ?></a></td>
                                <td><a href="<?php echo $url; ?>"><?php echo $usuario->getSituacaoStr(); ?></a></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
namespace Emagine\Frete;

use Emagine\Base\EmagineApp;
use Emagine\Frete\Model\FreteInfo;
use Emagine\Login\Controls\UsuarioPerfil;

/**
 * @var EmagineApp $app
 * @var FreteInfo[] $fretes
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
                    <div class="row">
                        <div class="col-md-9">
                            <h3 style="margin-top: 5px; margin-bottom: 5px;"><i class="fa fa-truck"></i> Fretes</h3>
                        </div>
                        <div class="col-md-3 text-right">

                        </div>
                    </div>
                    <hr />
                    <table class="table table-striped table-hover table-responsive">
                        <thead>
                        <tr>
                            <th><a href="#">Destino</a></th>
                            <th><a href="#">Data</a></th>
                            <th><a href="#">Usuário</a></th>
                            <th><a href="#">Motorista</a></th>
                            <th class="text-right"><a href="#">Preço</a></th>
                            <th class="text-right"><a href="#">Distância</a></th>
                            <th><a href="#">Situação</a></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($fretes as $frete) : ?>
                            <?php
                            $urlFrete = $app->getBaseUrl() . "/frete/" . $frete->getId();
                            $urlCliente = $app->getBaseUrl() . "/cliente/" . $frete->getUsuario()->getSlug();
                            $urlMotorista = "#";
                            $nomeMotorista = "";
                            if ($frete->getIdMotorista() > 0) {
                                $nomeMotorista = $frete->getMotorista()->getUsuario()->getNome();
                                $urlMotorista = $app->getBaseUrl() . "/motorista/" . $frete->getMotorista()->getUsuario()->getSlug();
                            }
                            //$ditanciaStr = number_format($frete->getDistancia() / 1000, 1, ",", ".") . "km";
                            ?>
                            <tr>
                                <td>
                                    <a href="<?php echo $urlFrete; ?>"><?php
                                        if (!isNullOrEmpty($frete->getEnderecoDestino())) {
                                            echo $frete->getEnderecoDestino();
                                        }
                                        else {
                                            $local = $frete->getDestino();
                                            if (!is_null($local)) {
                                                echo $local->getPosicaoStr();
                                            }
                                            else {
                                                echo "Desconhecido";
                                            }
                                        }
                                    ?></a>
                                </td>
                                <td>
                                    <a href="<?php echo $urlFrete; ?>"><?php
                                        $dataCriacao = strtotime($frete->getDataInclusao());
                                        echo humanizeDateDiff(time(), $dataCriacao);
                                    ?></a>
                                </td>
                                <td><a href="<?php echo $urlCliente; ?>"><?php echo $frete->getUsuario()->getNome(); ?></a></td>
                                <td><a href="<?php echo $urlMotorista; ?>"><?php echo $nomeMotorista; ?></a></td>
                                <td class="text-right">
                                    <a href="<?php echo $urlFrete; ?>"><?php
                                        if ($frete->getPreco() > 0) {
                                            echo $frete->getPrecoStr();
                                        }
                                    ?></a>
                                </td>
                                <td class="text-right"><a href="<?php echo $urlFrete; ?>"><?php echo $frete->getDistanciaStr(); ?></a></td>
                                <td><a href="<?php echo $urlFrete; ?>"><?php echo $frete->getSituacaoStr(); ?></a></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
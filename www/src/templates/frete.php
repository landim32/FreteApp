<?php
namespace Emagine\Frete;

use Emagine\Base\EmagineApp;
use Emagine\Frete\Model\FreteHistoricoInfo;
use Emagine\Frete\Model\FreteInfo;
use Emagine\Login\Controls\UsuarioPerfil;

/**
 * @var EmagineApp $app
 * @var FreteInfo $frete
 * @var FreteHistoricoInfo[] $historicos
 * @var string $urlMapaPrevisao
 * @var string $urlMapaExecutado
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
                    <img src="<?php echo $urlMapaPrevisao; ?>" class="img-responsive" />
                    <hr />
                    <dl class="dl-horizontal">
                        <dt>Situação:</dt>
                        <dd><?php echo $frete->getSituacaoStr(); ?></dd>
                        <dt>Origem(O):</dt>
                        <dd><?php
                            if (!isNullOrEmpty($frete->getEnderecoOrigem())) {
                                echo $frete->getEnderecoOrigem();
                            }
                            else {
                                $local = $frete->getOrigem();
                                if (!is_null($local)) {
                                    echo $local->getPosicaoStr();
                                }
                            }
                            ?></dd>
                        <dt>Destino(D):</dt>
                        <dd><?php
                            if (!isNullOrEmpty($frete->getEnderecoDestino())) {
                                echo $frete->getEnderecoDestino();
                            }
                            else {
                                $local = $frete->getDestino();
                                if (!is_null($local)) {
                                    echo $local->getPosicaoStr();
                                }
                            }
                            ?></dd>
                        <?php if ($frete->getPreco() > 0) : ?>
                        <dt>Preço:</dt>
                        <dd><?php echo $frete->getPrecoStr(); ?></dd>
                        <?php endif; ?>
                        <?php if ($frete->getPeso() > 0) : ?>
                        <dt>Peso:</dt>
                        <dd><?php echo number_format($frete->getPeso(), 1, ",", ".") . "kg"; ?></dd>
                        <?php endif; ?>
                        <?php if ($frete->getLargura() > 0) : ?>
                            <dt>Largura:</dt>
                            <dd><?php echo number_format($frete->getLargura(), 1, ",", ".") . "cm"; ?></dd>
                        <?php endif; ?>
                        <?php if ($frete->getAltura() > 0) : ?>
                            <dt>Altura:</dt>
                            <dd><?php echo number_format($frete->getAltura(), 1, ",", ".") . "cm"; ?></dd>
                        <?php endif; ?>
                        <?php if ($frete->getProfundidade() > 0) : ?>
                            <dt>Profundidade:</dt>
                            <dd><?php echo number_format($frete->getProfundidade(), 1, ",", ".") . "cm"; ?></dd>
                        <?php endif; ?>
                        <?php if ($frete->getDistancia() > 0) : ?>
                        <?php $ditanciaStr = number_format($frete->getDistancia() / 1000, 1, ",", ".") . "km"; ?>
                        <dt>Distância:</dt>
                        <dd><?php echo $ditanciaStr; ?></dd>
                        <?php endif; ?>
                        <?php if ($frete->getTempo() > 0) : ?>
                            <dt>Previsão:</dt>
                            <dd><?php echo $frete->getTempoStr(); ?></dd>
                        <?php endif; ?>
                        <?php $dataRetirada = strtotime($frete->getDataRetirada()); ?>
                        <?php if ($dataRetirada > 0) : ?>
                            <dt>Data de Retirada:</dt>
                            <dd>
                                <?php echo humanizeDateDiff(time(), $dataRetirada); ?>
                                <small class="text-muted">(<?php echo $frete->getDataRetiradaStr(); ?>)</small>
                            </dd>
                        <?php endif; ?>
                        <?php $dataEntrega = strtotime($frete->getDataEntrega()); ?>
                        <?php if ($dataEntrega > 0) : ?>
                            <dt>Data de Entrega:</dt>
                            <dd>
                                <?php echo humanizeDateDiff(time(), $dataEntrega); ?>
                                <small class="text-muted">(<?php echo $frete->getDataEntregaStr(); ?>)</small>
                            </dd>
                        <?php endif; ?>
                        <?php $dataInclusao = strtotime($frete->getDataInclusao()); ?>
                        <dt>Data de Criação:</dt>
                        <dd>
                            <?php echo humanizeDateDiff(time(), $dataInclusao); ?>
                            <small class="text-muted">(<?php echo $frete->getDataInclusaoStr(); ?>)</small>
                        </dd>
                        <?php $ultimaAlteracao = strtotime($frete->getUltimaAlteracao()); ?>
                        <dt>Última Alteração:</dt>
                        <dd>
                            <?php echo humanizeDateDiff(time(), $ultimaAlteracao); ?>
                            <small class="text-muted">(<?php echo $frete->getUltimaAlteracaoStr(); ?>)</small>
                        </dd>
                        <?php if (!isNullOrEmpty($frete->getObservacao())) : ?>
                            <dt>Observação:</dt>
                            <dd><?php echo $frete->getObservacao(); ?></dd>
                        <?php endif; ?>
                    </dl>
                    <?php if (!isNullOrEmpty($frete->getFoto())) : ?>
                        <hr />
                        <img src="<?php echo $app->getBaseUrl() . "/" . $frete->getFotoUrl(); ?>" class="img-responsive" />
                    <?php endif; ?>

                </div>
            </div>
            <?php if (count($historicos) >= 2) : ?>
            <br />
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="fa fa-map-marker"></i> Histórico de Navegação
                    </h3>
                </div>
                <div class="panel-body">
                    <img src="<?php echo $urlMapaExecutado; ?>" class="img-responsive" />
                    <hr />
                    <dl class="dl-horizontal">
                        <?php $dataRetirada = strtotime($frete->getDataRetiradaExecutada()); ?>
                        <?php if ($dataRetirada > 0) : ?>
                            <dt>Data de Retirada:</dt>
                            <dd>
                                <?php echo humanizeDateDiff(time(), $dataRetirada); ?>
                                <small class="text-muted">(<?php echo $frete->getDataRetiradaExecutadaStr(); ?>)</small>
                            </dd>
                        <?php endif; ?>
                        <?php $dataEntrega = strtotime($frete->getDataEntregaExecutada()); ?>
                        <?php if ($dataEntrega > 0) : ?>
                            <dt>Data de Entrega:</dt>
                            <dd>
                                <?php echo humanizeDateDiff(time(), $dataEntrega); ?>
                                <small class="text-muted">(<?php echo $frete->getDataEntregaExecutadaStr(); ?>)</small>
                            </dd>
                        <?php endif; ?>
                    </dl>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <div class="col-md-3">
            <a class="btn btn-danger btn-block confirm" href="<?php echo $app->getBaseUrl() . "/frete/excluir/" . $frete->getId(); ?>">
                <i class="fa fa-trash"></i> Excluir
            </a>
            <br />
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="fa fa-user-o"></i> Passageiro
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            <i class="fa fa-3x fa-user-circle"></i>
                        </div>
                        <div class="col-md-9">
                            <strong>
                                <a href="<?php echo $app->getBaseUrl() . "/cliente/" . $frete->getUsuario()->getSlug() ?>">
                                    <?php echo $frete->getUsuario()->getNome(); ?>
                                </a>
                            </strong>
                            <p style="font-size: 80%">
                                <i class="fa fa-fw fa-envelope"></i>
                                <a href="mailto:<?php echo $frete->getUsuario()->getEmail(); ?>">
                                    <?php echo $frete->getUsuario()->getEmail(); ?>
                                </a><br />
                                <i class="fa fa-fw fa-phone"></i>
                                <?php echo $frete->getUsuario()->getTelefoneStr(); ?><br />
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <?php if ($frete->getIdMotorista() > 0) : ?>
            <?php
                $motorista = $frete->getMotorista();
                $usuarioMotorista = $motorista->getUsuario();
             ?>
            <br />
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="fa fa-car"></i> Motorista
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            <i class="fa fa-3x fa-user-circle"></i>
                        </div>
                        <div class="col-md-9">
                            <strong>
                                <a href="<?php echo $app->getBaseUrl() . "/motorista/" . $usuarioMotorista->getSlug(); ?>">
                                    <?php echo $usuarioMotorista->getNome(); ?>
                                </a>
                            </strong>
                            <p style="font-size: 80%">
                                <i class="fa fa-fw fa-envelope"></i>
                                <a href="mailto:<?php echo $usuarioMotorista->getEmail(); ?>">
                                    <?php echo $usuarioMotorista->getEmail(); ?>
                                </a><br />
                                <i class="fa fa-fw fa-phone"></i>
                                <?php echo $usuarioMotorista->getTelefoneStr(); ?><br />
                            </p>
                        </div>
                    </div>
                    <div class="row" style="font-size: 80%">
                        <label class="col-md-4 text-right">Tipo:</label>
                        <div class="col-md-8">
                            <?php echo $motorista->getTipoStr(); ?>
                        </div>
                    </div>
                    <?php if ($motorista->getIdCarroceria() > 0) : ?>
                    <div class="row" style="font-size: 80%">
                        <label class="col-md-4 text-right">Carroceria:</label>
                        <div class="col-md-8">
                            <?php echo $motorista->getCarroceriaStr(); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if (!isNullOrEmpty($motorista->getVeiculo())) : ?>
                        <div class="row" style="font-size: 80%">
                            <label class="col-md-4 text-right">Veículo:</label>
                            <div class="col-md-8">
                                <?php echo $motorista->getVeiculo(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (!isNullOrEmpty($motorista->getAntt())) : ?>
                        <div class="row" style="font-size: 80%">
                            <label class="col-md-4 text-right">ANTT:</label>
                            <div class="col-md-8">
                                <?php echo $motorista->getAntt(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (!isNullOrEmpty($motorista->getPlaca())) : ?>
                        <div class="row" style="font-size: 80%">
                            <label class="col-md-4 text-right">Placa:</label>
                            <div class="col-md-8">
                                <?php echo $motorista->getPlaca(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (!isNullOrEmpty($motorista->getCNH())) : ?>
                    <div class="row" style="font-size: 80%">
                        <label class="col-md-4 text-right">CNH:</label>
                        <div class="col-md-8">
                            <?php echo $motorista->getCNH(); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if ($motorista->getValorHora() > 0) : ?>
                        <div class="row" style="font-size: 80%">
                            <label class="col-md-4 text-right">Preço/H:</label>
                            <div class="col-md-8">
                                <?php echo "R$ " . number_format($motorista->getValorHora(), 2, ",", "."); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

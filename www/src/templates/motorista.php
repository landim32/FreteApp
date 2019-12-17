<?php

namespace Emagine\Frete;

use Emagine\Base\EmagineApp;
use Emagine\Frete\Model\MotoristaInfo;
use Emagine\Login\Controls\UsuarioPerfil;

/**
 * @var EmagineApp $app
 * @var MotoristaInfo $motorista
 */

$usuario = $motorista->getUsuario();

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
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <i class="fa fa-5x fa-car"></i>
                        </div>
                        <div class="col-md-9">
                            <h2 style="margin: 10px 0px 0px 0px"><?php echo $usuario->getNome(); ?></h2>
                            <div>
                                <span class="badge"><?php echo $motorista->getSituacaoStr(); ?></span>
                                <span class="badge"><?php echo $motorista->getDisponibilidadeStr(); ?></span>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <dl class="dl-horizontal">
                        <?php if ($motorista->getIdTipo() > 0) : ?>
                            <dt>Tipo:</dt>
                            <dd><?php echo $motorista->getTipoStr(); ?></dd>
                        <?php endif; ?>
                        <?php if (!isNullOrEmpty($motorista->getVeiculo())) : ?>
                        <dt>Veículo:</dt>
                        <dd><?php echo $motorista->getVeiculo(); ?></dd>
                        <?php endif; ?>
                        <?php if (!isNullOrEmpty($motorista->getCNH())) : ?>
                            <dt>CNH:</dt>
                            <dd><?php echo $motorista->getCNH(); ?></dd>
                        <?php endif; ?>
                        <?php if (!isNullOrEmpty($motorista->getPlaca())) : ?>
                        <dt>Placa:</dt>
                        <dd><?php echo $motorista->getPlaca(); ?></dd>
                        <?php endif; ?>
                        <?php if (!isNullOrEmpty($motorista->getPosicaoStr())) : ?>
                            <dt>Posição Atual:</dt>
                            <dd><?php echo $motorista->getPosicaoStr(); ?></dd>
                        <?php endif; ?>
                    </dl>
                    <hr />
                    <dl class="dl-horizontal">
                        <dt>Data de Inclusão:</dt>
                        <dd><?php echo $usuario->getDataInclusaoStr(); ?></dd>
                        <dt>Última alteração:</dt>
                        <dd><?php echo $usuario->getUltimaAlteracaoStr(); ?></dd>
                        <?php if (!isNullOrEmpty($usuario->getUltimoLogin())) : ?>
                            <dt>Último Login:</dt>
                            <dd><?php echo $usuario->getUltimoLoginStr(); ?></dd>
                        <?php endif; ?>
                        <dt>Email:</dt>
                        <dd><a href="mailto:<?php echo $usuario->getEmail(); ?>"><i class="fa fa-envelope"></i> <?php echo $usuario->getEmail(); ?></a></dd>
                        <?php if (!isNullOrEmpty($usuario->getTelefone())) : ?>
                            <dt>Telefone:</dt>
                            <dd><?php echo $usuario->getTelefone(); ?></dd>
                        <?php endif; ?>
                        <dt>Situação:</dt>
                        <dd><?php echo $usuario->getSituacaoStr(); ?></dd>
                    </dl>
                    <?php foreach ($usuario->listarEndereco() as $endereco) : ?>
                        <hr />
                        <dl class="dl-horizontal">
                            <?php if (!isNullOrEmpty($endereco->getLogradouro())) : ?>
                                <dt>Logradouro:</dt>
                                <dd><?php echo $endereco->getLogradouro(); ?></dd>
                            <?php endif; ?>
                            <?php if (!isNullOrEmpty($endereco->getComplemento())) : ?>
                                <dt>Complemento:</dt>
                                <dd><?php echo $endereco->getComplemento(); ?></dd>
                            <?php endif; ?>
                            <?php if (!isNullOrEmpty($endereco->getNumero())) : ?>
                                <dt>Numero:</dt>
                                <dd><?php echo $endereco->getNumero(); ?></dd>
                            <?php endif; ?>
                            <?php if (!isNullOrEmpty($endereco->getCep())) : ?>
                                <dt>Cep:</dt>
                                <dd><?php echo $endereco->getCep(); ?></dd>
                            <?php endif; ?>
                            <?php if (!isNullOrEmpty($endereco->getBairro())) : ?>
                                <dt>Bairro:</dt>
                                <dd><?php echo $endereco->getBairro(); ?></dd>
                            <?php endif; ?>
                            <?php if (!isNullOrEmpty($endereco->getCidade())) : ?>
                                <dt>Cidade:</dt>
                                <dd><?php echo $endereco->getCidade(); ?></dd>
                            <?php endif; ?>
                            <?php if (!isNullOrEmpty($endereco->getUf())) : ?>
                                <dt>Uf:</dt>
                                <dd><?php echo $endereco->getUf(); ?></dd>
                            <?php endif; ?>
                        </dl>
                    <?php endforeach; ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="thumbnail">
                                <img src="<?php echo $app->getBaseUrl() . "/" . $motorista->getFotoCpfUrl(400, 300); ?>" class="img-responsive" />
                                <div class="caption">
                                    <h5>CPF</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="thumbnail">
                                <img src="<?php echo $app->getBaseUrl() . "/" . $motorista->getFotoCarteiraUrl(400, 300); ?>" class="img-responsive" />
                                <div class="caption">
                                    <h5>Carteira de Motorista</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="thumbnail">
                                <img src="<?php echo $app->getBaseUrl() . "/" . $motorista->getFotoVeiculoUrl(400, 300); ?>" class="img-responsive" />
                                <div class="caption">
                                    <h5>Veículo</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="thumbnail">
                                <img src="<?php echo $app->getBaseUrl() . "/" . $motorista->getFotoEnderecoUrl(400, 300); ?>" class="img-responsive" />
                                <div class="caption">
                                    <h5>Comprovante de Endereço</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if (count($usuario->listarPreferencia()) > 0) : ?>
                    <hr />
                    <table class="table table-striped table-hover table-responsive">
                        <thead>
                        <tr>
                            <th>Chave</th>
                            <th>Valor</th>
                            <th><i class="fa fa-cog"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($usuario->listarPreferencia() as $preferencia) : ?>
                            <tr>
                                <td><?php echo $preferencia->getChave(); ?></td>
                                <td><?php echo $preferencia->getValor(); ?></td>
                                <td><a class="remove" href="<?php echo $app->getBaseUrl(); ?>/api/login/usuario-preferencia/excluir/<?php echo $preferencia->getIdUsuario(); ?>-<?php echo $preferencia->getChave(); ?>"><i class="fa fa-remove"></i></a></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-3" style="padding-top: 40px;">
            <a class="confirm" href="<?php echo $app->getBaseUrl() . "/motorista/excluir/" . $usuario->getId(); ?>">
                <i class="fa fa-fw fa-trash"></i> Excluir</a>
            <br />
            <?php if ($motorista->getCodSituacao() == MotoristaInfo::ATIVO) : ?>
                <a class="confirm" href="<?php echo $app->getBaseUrl() . "/motorista/" . $usuario->getSlug() . "/situacao/" . MotoristaInfo::REPROVADO;  ?>">
                    <i class="fa fa-fw fa-minus-circle"></i> Reprovar
                </a>
            <?php elseif ($motorista->getCodSituacao() == MotoristaInfo::REPROVADO) : ?>
                <a class="confirm" href="<?php echo $app->getBaseUrl() . "/motorista/" . $usuario->getSlug() . "/situacao/" . MotoristaInfo::ATIVO;  ?>">
                    <i class="fa fa-fw fa-check-circle"></i> Aprovar
                </a>
            <?php elseif ($motorista->getCodSituacao() == MotoristaInfo::AGUARDANDO_APROVACAO) : ?>
                <a class="confirm" href="<?php echo $app->getBaseUrl() . "/motorista/" . $usuario->getSlug() . "/situacao/" . MotoristaInfo::ATIVO;  ?>">
                    <i class="fa fa-fw fa-check-circle"></i> Aprovar
                </a><br />
                <a class="confirm" href="<?php echo $app->getBaseUrl() . "/motorista/" . $usuario->getSlug() . "/situacao/" . MotoristaInfo::REPROVADO;  ?>">
                    <i class="fa fa-fw fa-minus-circle"></i> Reprovar
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
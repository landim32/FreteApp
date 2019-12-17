<?php
namespace Emagine\Frete;

use Emagine\Base\EmagineApp;
use Emagine\Frete\Model\FreteInfo;
use Emagine\Frete\Model\FreteHistoricoInfo;

/**
 * @var EmagineApp $app
 * @var FreteInfo $frete
 * @var FreteHistoricoInfo[] $historicos
 * @var double $preco
 * @var int $distancia
 * @var string $distanciaStr
 * @var int $tempo
 * @var string $tempoStr
 * @var int $tempoExecutado
 * @var string $tempoExecutadoStr
 * @var string $urlMapaPrevisao
 * @var string $urlMapaExecutado
 */


?>
<html>
<head>
    <style type="text/css">
        body {
            background-color: #fff;
        }
    </style>
</head>
<body>
<table width="600" border="0" cellpadding="7" align="center" style="background-color: #eee">
    <tr>
        <td>
            <h3 style="font-family: tahoma, verdana, arial; font-size: 16px; color: #000; margin: 0px">
                <?php echo $frete->getUsuario()->getNome() ?>, obrigado por viajar com <?php echo $frete->getMotorista()->getUsuario()->getNome(); ?>
            </h3>
            <span style="font-family: tahoma, verdana, arial; font-size: 12px; color: #535353">
                Atendimento solicitado em <?php echo $frete->getDataInclusaoStr() ?>
            </span>
        </td>
    </tr>
    <tr>
        <td align="center">
            <table width="560" border="0" cellpadding="8" align="center" style="background-color: #fff">
                <tr>
                    <td width="50%" valign="top">
                        <h5 style="font-family: tahoma, verdana, arial; font-size: 12px; color: #000; margin: 0">
                            Previsão
                        </h5>
                        <table width="100%" border="0" align="center" cellspacing="3" style="font-family: tahoma, verdana, arial; font-size: 11px;">
                            <?php if ($frete->getDistancia() > 0) : ?>
                                <tr>
                                    <td align="right">Distância:</td>
                                    <th align="right">
                                        <?php echo $frete->getDistanciaStr(); ?>
                                    </th>
                                </tr>
                            <?php endif; ?>
                            <?php if ($tempo > 0) : ?>
                                <tr>
                                    <td align="right">Duração:</td>
                                    <th align="right">
                                        <?php echo $tempoStr; ?>
                                    </th>
                                </tr>
                            <?php endif; ?>
                            <?php if ($frete->getPreco() > 0) : ?>
                                <tr>
                                    <td align="right">Preço:</td>
                                    <th align="right">
                                        <?php echo $frete->getPrecoStr(); ?>
                                    </th>
                                </tr>
                            <?php endif; ?>
                        </table>
                    </td>
                    <td width="50%" valign="top">
                        <h5 style="font-family: tahoma, verdana, arial; font-size: 12px; color: #000; margin: 0">
                            Resumo
                        </h5>
                        <table width="100%" border="0" align="center" cellspacing="3" style="font-family: tahoma, verdana, arial; font-size: 11px;">
                            <?php if ($distancia > 0) : ?>
                                <tr>
                                    <td align="right">Distância:</td>
                                    <th align="right">
                                        <?php echo $distanciaStr; ?>
                                    </th>
                                </tr>
                            <?php endif; ?>
                            <?php if ($tempoExecutado > 0) : ?>
                                <tr>
                                    <td align="right">Duração:</td>
                                    <th align="right">
                                        <?php echo $tempoExecutadoStr; ?>
                                    </th>
                                </tr>
                            <?php endif; ?>
                            <?php if ($preco > 0) : ?>
                                <tr>
                                    <td align="right">Preço:</td>
                                    <th align="right">
                                        <?php echo "R$ " . number_format($preco, 2, ",", "."); ?>
                                    </th>
                                </tr>
                            <?php endif; ?>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td align="center">
            <table width="560" border="0" cellpadding="8" align="center" style="background-color: #fff">
                <tr>
                    <td valign="top">
                        <img src="<?php echo $urlMapaPrevisao; ?>" border="0" />
                    </td>
                    <td>
                        <ul style="font-family: tahoma, verdana, arial; font-size: 12px; color: #000;">
                            <?php $dataRetirada = strtotime($frete->getDataRetirada()); ?>
                            <?php if ($dataRetirada > 0) : ?>
                                <li>
                                    <strong>Saída agendada para <?php echo date("d/M \à\s H\hi", $dataRetirada); ?></strong><br />
                                    <?php if (!isNullOrEmpty($frete->getEnderecoOrigem())) : ?>
                                        <span><?php echo $frete->getEnderecoOrigem(); ?></span>
                                    <?php else : ?>
                                        <?php $local = $frete->getOrigem(); ?>
                                        <?php if (!is_null($local)) : ?>
                                            <span>Posição no GPS: <?php echo $local->getPosicaoStr(); ?></span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <br /><br />
                                </li>
                            <?php endif; ?>
                            <?php $dataEntrega = strtotime($frete->getDataEntrega()); ?>
                            <?php if ($dataEntrega > 0) : ?>
                                <li>
                                    <strong>Chegada agendada em <?php echo date("d/M \à\s H\hi", $dataEntrega); ?></strong><br />
                                    <?php if (!isNullOrEmpty($frete->getEnderecoDestino())) : ?>
                                        <span><?php echo $frete->getEnderecoDestino(); ?></span>
                                    <?php else : ?>
                                        <?php $local = $frete->getDestino(); ?>
                                        <?php if (!is_null($local)) : ?>
                                            <span>Posição no GPS: <?php echo $local->getPosicaoStr(); ?></span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <br /><br />
                                </li>
                            <?php endif; ?>
                            <li>
                                <span style="color: #535353">
                                    Previsão de <?php echo $frete->getDistanciaStr(); ?> em <?php echo $tempoStr; ?>
                                </span>
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td align="center">
            <table width="560" border="0" cellpadding="8" align="center" style="background-color: #fff">
                <tr>
                    <td>
                        <ul style="font-family: tahoma, verdana, arial; font-size: 12px; color: #000;">
                            <?php $dataRetirada = strtotime($frete->getDataRetiradaExecutada()); ?>
                            <?php if ($dataRetirada > 0) : ?>
                                <li>
                                    <strong>Partiu em <?php echo date("d/M \à\s H\hi", $dataRetirada); ?></strong><br />
                                    <?php if (!isNullOrEmpty($frete->getEnderecoOrigem())) : ?>
                                        <span><?php echo $frete->getEnderecoOrigem(); ?></span>
                                    <?php else : ?>
                                        <?php $local = $frete->getOrigem(); ?>
                                        <?php if (!is_null($local)) : ?>
                                            <span>Posição no GPS: <?php echo $local->getPosicaoStr(); ?></span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <br /><br />
                                </li>
                            <?php endif; ?>
                            <?php $dataEntrega = strtotime($frete->getDataEntregaExecutada()); ?>
                            <?php if ($dataEntrega > 0) : ?>
                                <li>
                                    <strong>Chegou em <?php echo date("d/M \à\s H\hi", $dataEntrega); ?></strong><br />
                                    <?php if (!isNullOrEmpty($frete->getEnderecoDestino())) : ?>
                                        <span><?php echo $frete->getEnderecoDestino(); ?></span>
                                    <?php else : ?>
                                        <?php $local = $frete->getDestino(); ?>
                                        <?php if (!is_null($local)) : ?>
                                            <span>Posição no GPS: <?php echo $local->getPosicaoStr(); ?></span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <br /><br />
                                </li>
                            <?php endif; ?>
                            <li>
                                <span style="color: #535353">
                                    <?php echo $distanciaStr; ?> em <?php echo $tempoExecutadoStr; ?>
                                </span>
                            </li>
                        </ul>
                    </td>
                    <td valign="top">
                        <img src="<?php echo $urlMapaExecutado; ?>" border="0" />
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td align="center">
            <table width="560" border="0" cellpadding="8" align="center" style="background-color: #fff">
                <tr>
                    <td valign="top">
                        <?php $usuario = $frete->getUsuario(); ?>
                        <h5 style="font-family: tahoma, verdana, arial; font-size: 12px; color: #000; margin: 0">
                            Passageiro
                        </h5>
                        <table border="0" align="left" cellspacing="3" style="font-family: tahoma, verdana, arial; font-size: 11px;">
                            <tr>
                                <th colspan="2" align="center" style="font-size: 14px">
                                    <?php echo $usuario->getNome() ?>
                                </th>
                            </tr>
                            <tr>
                                <td align="right">Email:</td>
                                <th align="left"><a href="mailto:<?php echo $usuario->getEmail(); ?>"><?php echo $usuario->getEmail(); ?></a></th>
                            </tr>
                            <tr>
                                <td align="right">Telefone:</td>
                                <th align="left"><?php echo $usuario->getTelefoneStr(); ?></th>
                            </tr>
                        </table>
                    </td>
                    <td valign="top">
                        <?php $motorista = $frete->getMotorista(); ?>
                        <?php $usuarioMotorista = $motorista->getUsuario(); ?>
                        <h5 style="font-family: tahoma, verdana, arial; font-size: 12px; color: #000; margin: 0">
                            Marinheiro
                        </h5>
                        <table border="0" align="left" cellspacing="3" style="font-family: tahoma, verdana, arial; font-size: 11px;">
                            <tr>
                                <th colspan="2" align="center" style="font-size: 14px">
                                    <?php echo $usuarioMotorista->getNome() ?>
                                </th>
                            </tr>
                            <tr>
                                <td align="right">Email:</td>
                                <th align="left"><a href="mailto:<?php echo $usuarioMotorista->getEmail(); ?>"><?php echo $usuarioMotorista->getEmail(); ?></a></th>
                            </tr>
                            <tr>
                                <td align="right">Telefone:</td>
                                <th align="left"><?php echo $usuarioMotorista->getTelefoneStr(); ?></th>
                            </tr>
                            <tr>
                                <td align="right">Embarcação:</td>
                                <th align="left"><?php echo $motorista->getTipoStr(); ?></th>
                            </tr>
                            <?php if ($motorista->getIdCarroceria() > 0) : ?>
                            <tr>
                                <td align="right">Carroceria:</td>
                                <th align="left"><?php echo $motorista->getCarroceriaStr(); ?></th>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <td align="right">Nome Embar.:</td>
                                <th align="left"><?php echo $motorista->getVeiculo(); ?></th>
                            </tr>
                            <?php if (!isNullOrEmpty($motorista->getPlaca())) : ?>
                            <tr>
                                <td align="right">Nº Embar.:</td>
                                <th align="left"><?php echo $motorista->getPlaca(); ?></th>
                            </tr>
                            <?php endif; ?>
                            <?php if ($motorista->getValorHora() > 0) : ?>
                                <tr>
                                    <td align="right">Valor/Hora:</td>
                                    <th align="left"><?php echo "R$ " . number_format($motorista->getValorHora(), 2, ",", "."); ?></th>
                                </tr>
                            <?php endif; ?>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
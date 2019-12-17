<?php
namespace Emagine\CRM;

use Emagine\Base\EmagineApp;
use Emagine\Base\Utils\HtmlUtils;
use Emagine\Base\Utils\StringUtils;
use Emagine\CRM\Model\AtendimentoInfo;
use Emagine\Login\Controls\UsuarioPerfil;

/**
 * @var EmagineApp $app
 * @var AtendimentoInfo $atendimento
 */

$cliente = $atendimento->getCliente();
$opcoes = $cliente->listarOpcao();

$quantidadeEnviado = "Nenhum email enviado";
if ($cliente->getQuantidadeEnviado() > 0) {
    if ($cliente->getQuantidadeEnviado() == 1) {
        $quantidadeEnviado = $cliente->getQuantidadeEnviado() . " email enviado";
    }
    else {
        $quantidadeEnviado = $cliente->getQuantidadeEnviado() . " emails enviados";
    }
}

$urlCliente = $app->getBaseUrl() . "/cliente/" .
    strtolower(StringUtils::sanitize_slug($cliente->getNome())) . "_" . $cliente->getId();

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
                    <h1><?php echo $atendimento->getTitulo(); ?></h1>
                    <?php if (!isNullOrEmpty($atendimento->getUrl())) : ?>
                        <a href="<?php echo $atendimento->getUrl(); ?>" target="_blank"><?php echo $atendimento->getUrl(); ?></a>
                    <?php endif; ?>
                    <div>
                        <?php if (!isNullOrEmpty($atendimento->listarTag())) : ?>
                            <?php foreach ($atendimento->listarTag() as $tag) : ?>
                                <span class="badge"><?php echo $tag->getNome(); ?></span>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php echo $cliente->getSituacaoHtml(); ?>
                    </div>
                    <hr />
                    <?php foreach ($atendimento->getAndamentos() as $andamento) : ?>
                        <?php
                        $urlClienteLocal = $app->getBaseUrl() . "/cliente/" .
                            strtolower(StringUtils::sanitize_slug($andamento->getCliente()->getNome())) . "_" .
                            $andamento->getCliente()->getId();
                        ?>
                        <div class="row">
                            <?php if ($andamento->getIdCliente() > 0) : ?>
                                <div class="col-md-3 text-center">
                                    <a href="<?php echo $urlClienteLocal; ?>">
                                        <img src="<?php echo HtmlUtils::get_gravatar($andamento->getCliente()->getEmail(), 60); ?>"
                                             class="img-circle" alt="<?php echo $andamento->getCliente()->getNome(); ?>" />
                                    </a><br />
                                    <a href="<?php echo $urlClienteLocal; ?>">
                                        <?php echo $andamento->getCliente()->getNome(); ?>
                                    </a><br />
                                    <?php echo $andamento->getSituacaoHtml(); ?>
                                </div>
                            <?php endif;?>
                            <div class="col-md-9">
                                <div class="well" style="overflow: hidden;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php if ($andamento->getValorProposta() > 0) : ?>
                                                <small class="text-muted">Proposta: <strong><?php echo $andamento->getValorPropostaStr(); ?></strong></small>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <small class="text-muted"><?php echo $andamento->getDataInclusaoStr(); ?></small>
                                        </div>
                                    </div>
                                    <?php echo str_replace("\n", "<br />\n", $andamento->getMensagem()); ?>
                                </div>
                            </div>
                            <?php if ($andamento->getIdUsuario() > 0) : ?>
                                <div class="col-md-3">
                                    <?php echo $andamento->getUsuario()->getNome(); ?>
                                </div>
                            <?php endif;?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-default text-center">
                <div class="panel-body">
                    <a href="<?php echo $urlCliente; ?>">
                        <img src="<?php echo HtmlUtils::get_gravatar($cliente->getEmail1(), 100); ?>" class="img-circle" />
                    </a>
                    <h4>
                        <a href="<?php echo $urlCliente; ?>">
                            <?php echo $cliente->getNome(); ?>
                        </a>
                    </h4>
                    <div>
                        <?php if (!StringUtils::isNullOrEmpty($cliente->listarTag())) : ?>
                            <?php foreach ($cliente->listarTag() as $tag) : ?>
                                <span class="badge"><?php echo $tag->getNome(); ?></span>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php echo $cliente->getSituacaoHtml(); ?>
                    </div>
                </div>
            </div>
            <i class="fa fa-fw fa-eye"></i> <?php echo $quantidadeEnviado; ?><br />
            <a href="<?php echo $urlCliente . "/alterar"; ?>">
                <i class="fa fa-fw fa-edit"></i> Alterar Cliente
            </a>
            <hr />
            <?php if (array_key_exists("zendesk_id", $opcoes)) : ?>
                <?php $urlZendesk = "https://emaginebr.zendesk.com/agent/users/" . $opcoes["zendesk_id"] . "/requested_tickets"; ?>
                <a target="_blank" href="<?php echo $urlZendesk; ?>"><i class="fa fa-fw fa-user-circle"></i> Abrir no Zendesk</a><br />
            <?php endif; ?>
            <?php if (!is_null($atendimento)) : ?>
                <hr />
                <?php if (!StringUtils::isNullOrEmpty($atendimento->getUrl())) : ?>
                    <a target="_blank" href="<?php echo $atendimento->getUrl(); ?>">
                        <i class="fa fa-fw fa-globe"></i> Link de Origem
                    </a><br />
                <?php endif; ?>
                <a href="<?php echo $app->getBaseUrl() . "/atendimento/" . $atendimento->getId() . "/excluir"; ?>">
                    <i class="fa fa-fw fa-trash"></i> Excluir
                </a><br />
            <?php endif; ?>
        </div>
    </div>
</div>

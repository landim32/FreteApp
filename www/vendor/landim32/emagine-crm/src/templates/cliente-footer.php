<?php

namespace Emagine\CRM;

use Emagine\Base\EmagineApp;
use Emagine\Base\Utils\StringUtils;
use Emagine\CRM\Model\AtendimentoInfo;
use Emagine\CRM\Model\ClienteInfo;

/**
 * @var EmagineApp $app
 * @var ClienteInfo $cliente
 * @var AtendimentoInfo $atendimento
 */

$quantidadeEnviado = "Nenhum email enviado";
if ($cliente->getQuantidadeEnviado() > 0) {
    if ($cliente->getQuantidadeEnviado() == 1) {
        $quantidadeEnviado = $cliente->getQuantidadeEnviado() . " email enviado";
    }
    else {
        $quantidadeEnviado = $cliente->getQuantidadeEnviado() . " emails enviados";
    }
}

$urlExcluir = $app->getBaseUrl() . "/cliente/" . strtolower(StringUtils::sanitize_slug($cliente->getNome())) .
    "_" . $cliente->getId() . "/excluir";
?>
                <hr />
                <?php if (count($cliente->listarOpcao()) > 0) : ?>
                    <dl class="dl-horizontal">
                        <?php foreach ($cliente->listarOpcao() as $chave => $valor) : ?>
                            <dt><?php echo $chave; ?></dt>
                            <dd><?php echo $valor; ?></dd>
                        <?php endforeach; ?>
                    </dl>
                <?php endif; ?>
            </div><!--panel-body-->
        </div><!--panel-->
    </div><!--col-md-9-->
    <div class="col-md-3" style="padding-top: 30px;">
        <i class="fa fa-fw fa-eye"></i> <?php echo $quantidadeEnviado; ?><br />
        <a href="#" class="cliente-alterar" data-cliente="<?php echo $cliente->getId(); ?>"><i class="fa fa-fw fa-edit"></i> Alterar</a><br />
        <a href="<?php echo $urlExcluir; ?>" class="confirm">
            <i class="fa fa-fw fa-remove"></i> Excluir
        </a>
        <hr />
        <?php if (array_key_exists("zendesk_id", $cliente->listarOpcao())) : ?>
            <?php $opcoes = $cliente->listarOpcao(); ?>
            <?php $urlZendesk = "https://emaginebr.zendesk.com/agent/users/" . $opcoes["zendesk_id"] . "/requested_tickets"; ?>
            <a target="_blank" href="<?php echo $urlZendesk; ?>"><i class="fa fa-fw fa-user-circle"></i> Abrir no Zendesk</a><br />
        <?php endif; ?>
        <?php if (!is_null($atendimento)) : ?>
            <hr />
            <a target="_blank" href="<?php echo $atendimento->getUrl(); ?>"><i class="fa fa-fw fa-globe"></i> Link de Origem</a><br />
            <a href="#" class="atendimento-excluir" data-cliente="<?php echo $atendimento->getId(); ?>"><i class="fa fa-fw fa-remove"></i> Excluir Atendimento</a><br />
        <?php endif; ?>
    </div><!--col-md-3-->
</div><!--row-->

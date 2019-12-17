<?php
namespace Emagine\CRM;

use Emagine\Base\EmagineApp;
use Emagine\CRM\Model\AndamentoInfo;
use Emagine\CRM\Model\AtendimentoInfo;

/**
 * @var EmagineApp $app
 * @var string $palavra_chave
 * @var AtendimentoInfo[] $atendimentos
 * @var string $paginacao
 */
?>
<div class="panel panel-default">
    <div class="panel-body">
        <form method="GET" class="form-horizontal">
            <div class="input-group input-group-lg">
                <input type="text" name="p" class="form-control" placeholder="Buscar por..." value="<?php echo $palavra_chave; ?>">
                <span class="input-group-btn">
                    <button class="btn btn-primary" type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div><!-- /input-group -->
        </form>
    </div>
</div>
<?php if (count($atendimentos) > 0) : ?>
    <?php foreach ($atendimentos as $atendimento) : ?>
        <?php
        $urlAtendimento = $app->getBaseUrl() . "/atendimento/" . $atendimento->getId();
        $urlCliente = $app->getBaseUrl() . "/cliente/" . $atendimento->getIdCliente();
        $andamento = null;
        if (count($atendimento->getAndamentos()) > 0) {
            /** @var AndamentoInfo $andamento */
            $andamento = array_values($atendimento->getAndamentos())[0];
        }
        ?>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8">
                        <h2 style="margin: 0px">
                            <a href="<?php echo $urlAtendimento; ?>"><?php echo $atendimento->getTitulo(); ?></a></a>
                        </h2>
                        <?php if (!isNullOrEmpty($atendimento->listarTag())) : ?>
                            <?php foreach ($atendimento->listarTag() as $tag) : ?>
                                <span class="badge"><?php echo $tag->getNome(); ?></span>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <hr />
                        <?php if (!is_null($andamento)) : ?>
                            <p><?php echo $andamento->getMensagem(); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4">
                        <div class="text-right">
                            <?php echo $atendimento->getSituacaoHtml(); ?>
                        </div>
                        <span>Cliente:</span> <a href="<?php echo $urlCliente; ?>" style="font-weight: bold"><?php echo $atendimento->getCliente()->getNome(); ?></a><br />
                        <span>Última Alteração:</span> <strong><?php echo $atendimento->getUltimaAlteracaoStr(); ?></strong><br />
                        <?php if (!isNullOrEmpty($atendimento->getUltimaPropostaStr())) : ?>
                        <span>Valor da Proposta:</span> <strong><?php echo $atendimento->getUltimaPropostaStr(); ?></strong><br />
                        <?php endif; ?>
                        <br />
                        <a href="<?php echo $urlAtendimento; ?>" class="btn btn-block btn-primary">Novo andamento</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (!is_null($paginacao)) : ?>
        <div class="text-center"><?php echo $paginacao; ?></div>
    <?php endif; ?>
<?php else : ?>
    <div class="alert alert-danger">
        <i class="fa fa-warning"></i> Nenhum atendimento encontrado!
    </div>
<?php endif; ?>
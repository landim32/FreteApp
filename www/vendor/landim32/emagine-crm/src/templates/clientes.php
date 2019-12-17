<?php
namespace Emagine\CRM;

use Emagine\Base\EmagineApp;
use Emagine\Base\Utils\StringUtils;
use Emagine\CRM\Model\ClienteInfo;

/**
 * @var EmagineApp $app
 * @var ClienteInfo[] $clientes
 * @var string $paginacao
 */

?>
<div class="panel panel-default">
    <div class="panel-body">
            <table class="table table-striped table-hover table-responsive">
                <thead>
                <tr>
                    <th><a href="#">Cliente</a></th>
                    <th><a href="#"><i class="fa fa-tags"></i> Tags</a></th>
                    <th class="text-center" data-hide="phone,tablet"><a href="#"><i class="fa fa-phone"></i></a></th>
                    <th data-hide="phone,tablet"><a href="#"><i class="fa fa-envelope"></i> Email</a></th>
                    <th><a href="#">Situação</a></th>
                    <th class="text-right"><a href="#"><i class="fa fa-eye"></i></a></th>
                    <th class="text-right"><a href="#"><i class="fa fa-cog"></i></a></th>
                </tr>
                </thead>
                <tbody>
                <?php if (count($clientes) > 0) : ?>
                    <?php foreach ($clientes as $cliente) : ?>
                    <?php
                        $url = $app->getBaseUrl() . "/cliente/" . strtolower(StringUtils::sanitize_slug($cliente->getNome())) .
                            "_" . $cliente->getId();
                        $urlExcluir = $url . "/excluir";
                        ?>
                        <tr>
                            <td><a href="<?php echo $url; ?>"><?php echo $cliente->getNome(); ?></a></td>
                            <td><?php echo $cliente->getTagsHtml(); ?></td>
                            <td><a href="<?php echo $url; ?>"><?php echo $cliente->getTelefone1(); ?></a></td>
                            <td><a href="<?php echo $url; ?>"><?php echo $cliente->getEmail(); ?></a></td>
                            <td><?php echo $cliente->getSituacaoHtml(); ?></td>
                            <td class="text-right">
                                <a href="<?php echo $url; ?>">
                                    <?php echo $cliente->getQuantidadeEnviado(); ?>
                                </a>
                            </td>
                            <td class="text-right">
                                <a href="<?php echo $urlExcluir; ?>">
                                    <i class="fa fa-remove"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7"><i class="fa fa-warning"></i> Nenhum encontrado encontrado!</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if (!is_null($paginacao)) : ?>
    <div class="text-center"><?php echo $paginacao; ?></div>
<?php endif; ?>
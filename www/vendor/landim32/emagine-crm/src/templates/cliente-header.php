<?php

namespace Emagine\CRM;

use Emagine\Base\Utils\HtmlUtils;
use Emagine\Base\Utils\StringUtils;
use Emagine\CRM\Model\ClienteInfo;

/**
 * @var ClienteInfo $cliente
 */

?>
<div class="row">
    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3">
                        <img src="<?php echo HtmlUtils::get_gravatar($cliente->getEmail(), 100); ?>" class="img-circle" />
                    </div>
                    <div class="col-md-9">
                        <h2><?php echo $cliente->getNome(); ?></h2>
                        <div class="row">
                            <div class="col-md-9">
                                <div>
                                    <?php if (!StringUtils::isNullOrEmpty($cliente->listarTag())) : ?>
                                        <?php foreach ($cliente->listarTag() as $tag) : ?>
                                            <span class="badge"><?php echo $tag->getNome(); ?></span>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <?php echo $cliente->getSituacaoHtml(); ?>
                                </div>
                                <div>
                                    <?php if (!StringUtils::isNullOrEmpty($cliente->getEmpresa())) : ?>
                                        <i class="fa fa-building"></i> <?php echo $cliente->getEmpresa(); ?>
                                    <?php endif; ?>
                                    <?php if (!StringUtils::isNullOrEmpty($cliente->getEmail())) : ?>
                                        <i class="fa fa-envelope"></i> <?php echo $cliente->getEmail(); ?>
                                    <?php endif; ?>
                                    <?php if (!StringUtils::isNullOrEmpty($cliente->getTelefone1())) : ?>
                                        <i class="fa fa-phone"></i> <?php echo $cliente->getTelefone1(); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr />
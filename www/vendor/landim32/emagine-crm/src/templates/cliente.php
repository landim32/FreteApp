<?php
namespace Emagine\CRM;

use Emagine\Base\EmagineApp;
use Emagine\Base\Utils\StringUtils;
use Emagine\CRM\Model\ClienteInfo;

/**
 * @var EmagineApp $app
 * @var ClienteInfo $cliente
 */

?>
<dl class="dl-horizontal">
    <?php if (!StringUtils::isNullOrEmpty($cliente->getTelefone1())) : ?>
        <dt>Telefone:</dt>
        <dd><?php echo $cliente->getTelefone1Str(); ?></dd>
    <?php endif; ?>
    <?php if (!StringUtils::isNullOrEmpty($cliente->getTelefone2())) : ?>
        <dt>Celular:</dt>
        <dd><?php echo $cliente->getTelefone2Str(); ?></dd>
    <?php endif; ?>
    <?php if (!StringUtils::isNullOrEmpty($cliente->getDataInclusao())) : ?>
        <dt>Data de Inclusão:</dt>
        <dd><?php echo $cliente->getDataInclusaoStr(); ?></dd>
    <?php endif; ?>
    <?php if (!StringUtils::isNullOrEmpty($cliente->getUltimaAlteracao())) : ?>
        <dt>Última alteração:</dt>
        <dd><?php echo $cliente->getUltimaAlteracaoStr(); ?></dd>
    <?php endif; ?>
    <?php if (!StringUtils::isNullOrEmpty($cliente->getRg())) : ?>
        <dt>RG:</dt>
        <dd><?php echo $cliente->getRg(); ?></dd>
    <?php endif; ?>
    <?php if (!StringUtils::isNullOrEmpty($cliente->getCpfCnpj())) : ?>
        <dt>CPF/CNPJ:</dt>
        <dd><?php echo $cliente->getCpfCnpjStr(); ?></dd>
    <?php endif; ?>
    <?php if (!StringUtils::isNullOrEmpty($cliente->getNacionalidade())) : ?>
        <dt>Nacionalidade:</dt>
        <dd><?php echo $cliente->getNacionalidade(); ?></dd>
    <?php endif; ?>
    <?php if (!StringUtils::isNullOrEmpty($cliente->getEstadoCivil())) : ?>
        <dt>Estado Civil:</dt>
        <dd><?php echo $cliente->getEstadoCivil(); ?></dd>
    <?php endif; ?>
    <?php if (!StringUtils::isNullOrEmpty($cliente->getProfissao())) : ?>
        <dt>Profissão:</dt>
        <dd><?php echo $cliente->getProfissao(); ?></dd>
    <?php endif; ?>
    <?php if (!StringUtils::isNullOrEmpty($cliente->getEmpresa())) : ?>
        <dt>Empresa:</dt>
        <dd><?php echo $cliente->getEmpresa(); ?></dd>
    <?php endif; ?>
    <?php if (!StringUtils::isNullOrEmpty($cliente->getSiteUrl())) : ?>
        <dt>Url:</dt>
        <dd><a href="<?php echo "http://" . $cliente->getSiteUrl(); ?>"><?php echo $cliente->getSiteUrl(); ?></a></dd>
    <?php endif; ?>
</dl>
<?php if (count($cliente->listarEndereco()) > 0) : ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <table class="table table-striped table-hover table-responsive">
                <thead>
                <tr>
                    <th><i class="fa fa-map-marker"></i> Endereço</th>
                    <th class="text-right"><i class="fa fa-cog"></i></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($cliente->listarEndereco() as $endereco) : ?>
                    <?php
                    $clienteSlug = strtolower(StringUtils::sanitize_slug($cliente->getNome())) . "_" . $cliente->getId();
                    $urlExcluir = $app->getBaseUrl() . "/cliente/" . $clienteSlug . "/excluir-endereco/" . $endereco->getId();
                    ?>
                    <tr>
                        <td><?php echo $endereco->getLogradouro(); ?></td>
                        <td class="text-right"><a class="confirm" href="<?php echo $urlExcluir; ?>"><i class="fa fa-remove"></i></a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>
<?php //var_dump($cliente); ?>


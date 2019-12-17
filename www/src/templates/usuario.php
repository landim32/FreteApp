<?php

namespace Emagine\Frete;

use Emagine\Base\EmagineApp;
use Emagine\Login\Controls\UsuarioPerfil;
use Emagine\Login\Model\UsuarioInfo;
/**
 * @var EmagineApp $app
 * @var UsuarioInfo $usuario
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
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <i class="fa fa-5x fa-user-circle"></i>
                        </div>
                        <div class="col-md-9">
                            <h2 style="margin: 10px 0px 0px 0px"><?php echo $usuario->getNome(); ?></h2>
                            <div>
                                <?php foreach ($usuario->listarGrupo() as $grupo) : ?>
                                    <span class="badge"><?php echo $grupo->getNome(); ?></span>
                                <?php endforeach; ?>
                            </div>
                            <div>
                                <a href="mailto:<?php echo $usuario->getEmail(); ?>"><i class="fa fa-envelope"></i> <?php echo $usuario->getEmail(); ?></a>
                            </div>
                        </div>
                    </div>
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
                        <dd><?php echo $usuario->getEmail(); ?></dd>
                        <dt>Nome:</dt>
                        <dd><?php echo $usuario->getNome(); ?></dd>
                        <dt>Situação:</dt>
                        <dd><?php echo $usuario->getSituacaoStr(); ?></dd>
                        <?php if (!isNullOrEmpty($usuario->getTelefone())) : ?>
                            <dt>Telefone:</dt>
                            <dd><?php echo $usuario->getTelefone(); ?></dd>
                        <?php endif; ?>
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
            <a href="<?php echo "#usuario/" . $usuario->getSlug() . "/trocar-senha"; ?>"><i class="fa fa-fw fa-lock"></i> Trocar senha</a><br />
            <a class="remove" href="<?php echo $app->getBaseUrl(); ?>/api/auth/usuario/excluir/<?php echo $usuario->getId(); ?>"><i class="fa fa-fw fa-trash"></i> Excluir</a>

        </div>
    </div>
</div>
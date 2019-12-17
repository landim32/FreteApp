<?php

namespace Emagine\Frete;

use Emagine\Base\EmagineApp;
use Emagine\Login\Model\UsuarioInfo;
use Landim32\BtMenu\BtMenu;
use Landim32\BtMenu\BtMenuSeparator;
use Slim\Views\PhpRenderer;
use Emagine\Login\BLL\UsuarioBLL;

$app = EmagineApp::getApp();

//$currentUrl = $app->getCurrentUrl();

$container = $app->getContainer();
$container['frete'] = function ($container) {
    return new PhpRenderer(dirname(__DIR__) . '/templates/');
};

$usuario = UsuarioBLL::pegarUsuarioAtual();
$eAdmin = false;
if (!is_null($usuario)) {
    $eAdmin = $usuario->temPermissao(UsuarioInfo::ADMIN);
}

$menuMain = $app->getMenu("main");
if (!is_null($menuMain) && $eAdmin) {
    $menuFrete = $menuMain->addMenu(new BtMenu("Fretes", "#", "fa fa-truck"));
    $menuFrete->addSubMenu(new BtMenu("Todos", $app->getBaseUrl() . "/frete/listar", "fa fa-fw fa-truck"));

    $menuCliente = $menuMain->addMenu(new BtMenu("Clientes", "#", "fa fa-user-circle"));
    $menuCliente->addSubMenu(new BtMenu("Todos", $app->getBaseUrl() . "/cliente/listar", "fa fa-fw fa-user-circle"));

    $menuMotorista = $menuMain->addMenu(new BtMenu("Motoristas", "#", "fa fa-car"));
    $menuMotorista->addSubMenu(new BtMenu("Todos", $app->getBaseUrl() . "/motorista/listar", "fa fa-fw fa-car"));
}

$menuLateral = $app->getMenu("lateral");
if (!is_null($menuLateral) && $eAdmin) {
    $menuLateral->addMenu(new BtMenu("Fretes", $app->getBaseUrl() . "/frete/listar", "fa fa-fw fa-truck"));
    $menuLateral->addMenu(new BtMenu("Clientes", $app->getBaseUrl() . "/cliente/listar", "fa fa-fw fa-user-circle"));
    $menuLateral->addMenu(new BtMenu("Motoristas", $app->getBaseUrl() . "/motorista/listar", "fa fa-fw fa-car"));
}
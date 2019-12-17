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
<?php

namespace Emagine\CRM;

use Emagine\Base\EmagineApp;
use Slim\Views\PhpRenderer;

$app = EmagineApp::getApp();

$container = $app->getContainer();
$container['crm'] = function ($container) {
    return new PhpRenderer(dirname(__DIR__) . '/templates/');
};
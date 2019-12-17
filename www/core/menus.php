<?php

namespace Emagine\Frete;

use Emagine\Base\EmagineApp;
use Landim32\BtMenu\BtMenu;

$app = EmagineApp::getApp();

$mainMenu = $app->getMenu("main");
$mainMenu->insertMenu(new BtMenu("Home", $app->getBaseUrl(), "fa fa-home"));
//$mainMenu->addMenu(new BtMenu("DataTable", $app->getBaseUrl() . "/datatable", "fa fa-table"));

$menuLateral = $app->getMenu("lateral");
$menuLateral->insertMenu(new BtMenu("Home", $app->getBaseUrl() . "/", "fa fa-fw fa-home"));
//$menuLateral->addMenu(new BtMenu("DataTable", $app->getBaseUrl() . "/#datatable", "fa fa-table"));

//$app->setMenu("right", UsuarioMenu::create());
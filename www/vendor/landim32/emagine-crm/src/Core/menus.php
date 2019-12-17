<?php

namespace Emagine\CRM;

use Emagine\Base\EmagineApp;
use Landim32\BtMenu\BtMenu;

$app = EmagineApp::getApp();

$mainMenu = $app->getMenu("main");
if (!is_null($mainMenu)) {
    //$mainMenu->addMenu(new BtMenu("Home", $app->getBaseUrl(), "fa fa-home"));
    $menuCrm = $mainMenu->addMenu(new BtMenu("CRM", "#", "fa fa-users"));
    $menuCrm->addSubMenu(new BtMenu("Atendimentos", $app->getBaseUrl() . "/atendimento", "fa fa-fw fa-user"));
    $menuCrm->addSubMenu(new BtMenu("Clientes", $app->getBaseUrl() . "/cliente", "fa fa-fw fa-users"));
}
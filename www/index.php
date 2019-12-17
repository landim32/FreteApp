<?php

namespace Emagine\Frete;

use Emagine\Base\EmagineApp;
use Landim32\BtMenu\BtMainMenu;
use Landim32\BtMenu\BtMenu;

require __DIR__ . "/core/config.php";
$loader = require __DIR__ . "/vendor/autoload.php";
$loader->addPsr4('Landim32\\EasyDB\\', __DIR__ . '/vendor/landim32/easydb/src');
$loader->addPsr4('Landim32\\BtMenu\\', __DIR__ . '/vendor/landim32/btmenu/src');
$loader->addPsr4('Landim32\\GoogleDirectionApi\\', __DIR__ . '/vendor/landim32/google-directions/src');
$loader->addPsr4('Emagine\\Base\\', __DIR__ . '/vendor/landim32/emagine-base/src');
$loader->addPsr4('Emagine\\Endereco\\', __DIR__ . '/vendor/landim32/emagine-endereco/src');
$loader->addPsr4('Emagine\\Login\\', __DIR__ . '/vendor/landim32/emagine-login/src');
$loader->addPsr4('Emagine\\CRM\\', __DIR__ . '/vendor/landim32/emagine-crm/src');
$loader->addPsr4('Emagine\\Pagamento\\', __DIR__ . '/vendor/landim32/emagine-pagamento/src');
$loader->addPsr4('Emagine\\Frete\\', __DIR__ . '/src');


$config = EmagineApp::getConfig(__DIR__);
$app = new EmagineApp($config);
$app->includeModule(__DIR__ . "/vendor/landim32/emagine-endereco/src", "/vendor/landim32/emagine-endereco/src");
$app->includeModule(__DIR__ . "/vendor/landim32/emagine-login/src", "/vendor/landim32/emagine-login/src");
$app->includeModule(__DIR__ . "/vendor/landim32/emagine-crm/src", "/vendor/landim32/emagine-crm/src");
$app->includeModule(__DIR__ . "/vendor/landim32/emagine-pagamento/src", "/vendor/landim32/emagine-pagamento/src");
$app->includeModule(__DIR__ . "/src", "/src");
$app->setDatatableEnabled(true);
$app->setJQueryUIEnabled(true);
$app->setBootstrapSliderEnabled(true);
EmagineApp::setApp($app);

$app->setMenu("main", new BtMainMenu());
$app->setMenu("right", new BtMainMenu(BtMenu::NAVBAR, "navbar-right"));
$app->setMenu("lateral", new BtMainMenu(BtMenu::LIST_GROUP));

$app->doDependencies();
$app->doRoutes();

require __DIR__ . "/core/menus.php";
require __DIR__ . "/core/routes.php";

//var_dump($app->getModules());
$app->run();
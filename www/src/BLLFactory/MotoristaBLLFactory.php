<?php
namespace Emagine\Frete\BLLFactory;

use Emagine\Frete\BLL\MotoristaBLL;
use Emagine\Frete\IBLL\IMotoristaBLL;

class MotoristaBLLFactory
{
    private static $instance;

    /**
     * @return IMotoristaBLL
     */
    public static function create() {
        if (is_null(MotoristaBLLFactory::$instance)) {
            if (defined("BLL_MOTORISTA")) {
                $bllClass = BLL_MOTORISTA;
                MotoristaBLLFactory::$instance = new $bllClass();
            }
            else {
                MotoristaBLLFactory::$instance = new MotoristaBLL();
            }
        }
        return MotoristaBLLFactory::$instance;
    }
}
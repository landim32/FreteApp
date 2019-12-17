<?php
namespace Emagine\Frete\BLLFactory;

use Emagine\Frete\BLL\FreteBLL;
use Emagine\Frete\IBLL\IFreteBLL;

class FreteBLLFactory
{
    private static $instance;

    /**
     * @return IFreteBLL
     */
    public static function create() {
        if (is_null(FreteBLLFactory::$instance)) {
            if (defined("BLL_FRETE")) {
                $bllClass = BLL_FRETE;
                FreteBLLFactory::$instance = new $bllClass();
            }
            else {
                FreteBLLFactory::$instance = new FreteBLL();
            }
        }
        return FreteBLLFactory::$instance;
    }
}
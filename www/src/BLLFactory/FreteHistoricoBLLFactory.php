<?php
namespace Emagine\Frete\BLLFactory;

use Emagine\Frete\BLL\FreteHistoricoBLL;
use Emagine\Frete\IBLL\IFreteHistoricoBLL;

class FreteHistoricoBLLFactory
{
    private static $instance;

    /**
     * @return IFreteHistoricoBLL
     */
    public static function create() {
        if (is_null(FreteHistoricoBLLFactory::$instance)) {
            if (defined("BLL_FRETE_HISTORICO")) {
                $bllClass = BLL_FRETE_HISTORICO;
                FreteHistoricoBLLFactory::$instance = new $bllClass();
            }
            else {
                FreteHistoricoBLLFactory::$instance = new FreteHistoricoBLL();
            }
        }
        return FreteHistoricoBLLFactory::$instance;
    }
}
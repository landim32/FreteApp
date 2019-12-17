<?php
namespace Emagine\Frete\DALFactory;

use Emagine\Frete\DAL\TipoCarroceriaDAL;
use Emagine\Frete\IDAL\ITipoCarroceriaDAL;

class TipoCarroceriaDALFactory
{
    private static $instance;

    /**
     * @return ITipoCarroceriaDAL
     */
    public static function create() {
        if (is_null(TipoCarroceriaDALFactory::$instance)) {
            if (defined("DAL_TIPO_CARROCERIA")) {
                $dalClass = DAL_TIPO_CARROCERIA;
                TipoCarroceriaDALFactory::$instance = new $dalClass();
            }
            else {
                TipoCarroceriaDALFactory::$instance = new TipoCarroceriaDAL();
            }
        }
        return TipoCarroceriaDALFactory::$instance;
    }
}
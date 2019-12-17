<?php
namespace Emagine\Frete\DALFactory;

use Emagine\Frete\DAL\FreteTipoCarroceriaDAL;
use Emagine\Frete\IDAL\IFreteTipoCarroceriaDAL;

class FreteTipoCarroceriaDALFactory
{
    private static $instance;

    /**
     * @return IFreteTipoCarroceriaDAL
     */
    public static function create() {
        if (is_null(FreteTipoCarroceriaDALFactory::$instance)) {
            if (defined("DAL_FRETE_TIPO_CARROCERIA")) {
                $dalClass = DAL_FRETE_TIPO_CARROCERIA;
                FreteTipoCarroceriaDALFactory::$instance = new $dalClass();
            }
            else {
                FreteTipoCarroceriaDALFactory::$instance = new FreteTipoCarroceriaDAL();
            }
        }
        return FreteTipoCarroceriaDALFactory::$instance;
    }
}
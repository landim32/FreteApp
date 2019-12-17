<?php
namespace Emagine\Frete\DALFactory;

use Emagine\Frete\DAL\FreteLocalDAL;
use Emagine\Frete\IDAL\IFreteLocalDAL;

class FreteLocalDALFactory
{
    private static $instance;

    /**
     * @return IFreteLocalDAL
     */
    public static function create() {
        if (is_null(FreteLocalDALFactory::$instance)) {
            if (defined("DAL_FRETE_LOCAL")) {
                $dalClass = DAL_FRETE_LOCAL;
                FreteLocalDALFactory::$instance = new $dalClass();
            }
            else {
                FreteLocalDALFactory::$instance = new FreteLocalDAL();
            }
        }
        return FreteLocalDALFactory::$instance;
    }
}
<?php
namespace Emagine\Frete\DALFactory;

use Emagine\Frete\DAL\MotoristaDAL;
use Emagine\Frete\IDAL\IMotoristaDAL;

class MotoristaDALFactory
{
    private static $instance;

    /**
     * @return IMotoristaDAL
     */
    public static function create() {
        if (is_null(MotoristaDALFactory::$instance)) {
            if (defined("DAL_MOTORISTA")) {
                $dalClass = DAL_MOTORISTA;
                MotoristaDALFactory::$instance = new $dalClass();
            }
            else {
                MotoristaDALFactory::$instance = new MotoristaDAL();
            }
        }
        return MotoristaDALFactory::$instance;
    }
}
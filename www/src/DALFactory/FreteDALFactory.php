<?php
namespace Emagine\Frete\DALFactory;

use Emagine\Frete\DAL\FreteDAL;
use Emagine\Frete\IDAL\IFreteDAL;

class FreteDALFactory
{
    private static $instance;

    /**
     * @return IFreteDAL
     */
    public static function create() {
        if (is_null(FreteDALFactory::$instance)) {
            if (defined("DAL_FRETE")) {
                $dalClass = DAL_FRETE;
                FreteDALFactory::$instance = new $dalClass();
            }
            else {
                FreteDALFactory::$instance = new FreteDAL();
            }
        }
        return FreteDALFactory::$instance;
    }
}
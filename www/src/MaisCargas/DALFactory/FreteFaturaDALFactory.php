<?php
namespace Emagine\Frete\MaisCargas\DALFactory;

use Emagine\Frete\MaisCargas\IDAL\IFreteFaturaDAL;
use Emagine\Frete\MaisCargas\DAL\FreteFaturaDAL;

class FreteFaturaDALFactory
{
    private static $instance;

    /**
     * @return IFreteFaturaDAL
     */
    public static function create() {
        if (is_null(FreteFaturaDALFactory::$instance)) {
            FreteFaturaDALFactory::$instance = new FreteFaturaDAL();
        }
        return FreteFaturaDALFactory::$instance;
    }
}
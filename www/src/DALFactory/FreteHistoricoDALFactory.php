<?php
namespace Emagine\Frete\DALFactory;

use Emagine\Frete\DAL\FreteHistoricoDAL;
use Emagine\Frete\IDAL\IFreteHistoricoDAL;

class FreteHistoricoDALFactory
{
    private static $instance;

    /**
     * @return IFreteHistoricoDAL
     */
    public static function create() {
        if (is_null(FreteHistoricoDALFactory::$instance)) {
            if (defined("DAL_FRETE_HISTORICO")) {
                $dalClass = DAL_FRETE_HISTORICO;
                FreteHistoricoDALFactory::$instance = new $dalClass();
            }
            else {
                FreteHistoricoDALFactory::$instance = new FreteHistoricoDAL();
            }
        }
        return FreteHistoricoDALFactory::$instance;
    }
}
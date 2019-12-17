<?php
namespace Emagine\Frete\DALFactory;

use Emagine\Frete\DAL\TipoVeiculoDAL;
use Emagine\Frete\IDAL\ITipoVeiculoDAL;

class TipoVeiculoDALFactory
{
    private static $instance;

    /**
     * @return ITipoVeiculoDAL
     */
    public static function create() {
        if (is_null(TipoVeiculoDALFactory::$instance)) {
            if (defined("DAL_TIPO_VEICULO")) {
                $dalClass = DAL_TIPO_VEICULO;
                TipoVeiculoDALFactory::$instance = new $dalClass();
            }
            else {
                TipoVeiculoDALFactory::$instance = new TipoVeiculoDAL();
            }
        }
        return TipoVeiculoDALFactory::$instance;
    }
}
<?php
namespace Emagine\Frete\DALFactory;

use Emagine\Frete\DAL\FreteTipoVeiculoDAL;
use Emagine\Frete\IDAL\IFreteTipoVeiculoDAL;

class FreteTipoVeiculoDALFactory
{
    private static $instance;

    /**
     * @return IFreteTipoVeiculoDAL
     */
    public static function create() {
        if (is_null(FreteTipoVeiculoDALFactory::$instance)) {
            if (defined("DAL_FRETE_TIPO_VEICULO")) {
                $dalClass = DAL_FRETE_TIPO_VEICULO;
                FreteTipoVeiculoDALFactory::$instance = new $dalClass();
            }
            else {
                FreteTipoVeiculoDALFactory::$instance = new FreteTipoVeiculoDAL();
            }
        }
        return FreteTipoVeiculoDALFactory::$instance;
    }
}
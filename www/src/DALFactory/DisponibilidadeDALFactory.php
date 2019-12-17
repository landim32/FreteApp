<?php
namespace Emagine\Frete\DALFactory;

use Emagine\Frete\DAL\DisponibilidadeDAL;
use Emagine\Frete\IDAL\IDisponibilidadeDAL;

class DisponibilidadeDALFactory
{
    private static $instance;

    /**
     * @return IDisponibilidadeDAL
     */
    public static function create() {
        if (is_null(DisponibilidadeDALFactory::$instance)) {
            if (defined("DAL_DISPONIBILIDADE")) {
                $dalClass = DAL_DISPONIBILIDADE;
                DisponibilidadeDALFactory::$instance = new $dalClass();
            }
            else {
                DisponibilidadeDALFactory::$instance = new DisponibilidadeDAL();
            }
        }
        return DisponibilidadeDALFactory::$instance;
    }
}
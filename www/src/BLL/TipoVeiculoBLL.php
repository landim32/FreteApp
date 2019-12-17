<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 22/03/2018
 * Time: 17:27
 */

namespace Emagine\Frete\BLL;

use Emagine\Frete\DALFactory\TipoVeiculoDALFactory;
use Exception;
use Emagine\Frete\DAL\TipoVeiculoDAL;
use Emagine\Frete\Model\TipoVeiculoInfo;

class TipoVeiculoBLL
{
    /**
     * @return TipoVeiculoInfo[]
     * @throws Exception
     */
    public function listar() {
        $dal = TipoVeiculoDALFactory::create();
        return $dal->listar();
    }

    /**
     * @param int $id_tipo
     * @return TipoVeiculoInfo
     * @throws Exception
     */
    public function pegar($id_tipo) {
        $dal = TipoVeiculoDALFactory::create();
        return $dal->pegar($id_tipo);
    }
}
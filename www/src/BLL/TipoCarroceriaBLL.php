<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 22/03/2018
 * Time: 17:27
 */

namespace Emagine\Frete\BLL;

use Emagine\Frete\DALFactory\TipoCarroceriaDALFactory;
use Exception;
use Emagine\Frete\DAL\TipoCarroceriaDAL;
use Emagine\Frete\Model\TipoCarroceriaInfo;

class TipoCarroceriaBLL
{
    /**
     * @return TipoCarroceriaInfo[]
     * @throws Exception
     */
    public function listar() {
        $dal = TipoCarroceriaDALFactory::create();
        return $dal->listar();
    }

    /**
     * @param int $id_carroceria
     * @return TipoCarroceriaInfo
     * @throws Exception
     */
    public function pegar($id_carroceria) {
        $dal = TipoCarroceriaDALFactory::create();
        return $dal->pegar($id_carroceria);
    }
}
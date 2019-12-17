<?php
namespace Emagine\Frete\MaisCargas\BLL;

use Exception;
use Emagine\Frete\MaisCargas\DALFactory\FreteFaturaDALFactory;
use Emagine\Frete\MaisCargas\Model\FreteFaturaInfo;

class FreteFaturaBLL
{
    /**
     * @param $id_usuario
     * @return FreteFaturaInfo[]
     * @throws Exception
     */
    public function listar($id_usuario) {
        $dal = FreteFaturaDALFactory::create();
        return $dal->listar($id_usuario);
    }
}
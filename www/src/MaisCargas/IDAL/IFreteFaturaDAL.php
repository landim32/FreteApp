<?php
namespace Emagine\Frete\MaisCargas\IDAL;

use Emagine\Frete\MaisCargas\Model\FreteFaturaInfo;

interface IFreteFaturaDAL
{
    /**
     * @param int $id_usuario
     * @return FreteFaturaInfo[]
     */
    public function listar($id_usuario);
}
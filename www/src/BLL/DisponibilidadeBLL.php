<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 16/04/2018
 * Time: 16:08
 */

namespace Emagine\Frete\BLL;

use Exception;
use Emagine\Frete\DALFactory\DisponibilidadeDALFactory;
use Emagine\Frete\DAL\DisponibilidadeDAL;
use Emagine\Frete\Model\DisponibilidadeInfo;

class DisponibilidadeBLL
{
    /**
     * @param int $id_usuario
     * @return DisponibilidadeInfo[]
     * @throws Exception
     */
    public function listar($id_usuario) {
        $dal = DisponibilidadeDALFactory::create();
        return $dal->listar($id_usuario);
    }

    /**
     * @param int $id_disponibilidade
     * @return DisponibilidadeInfo
     * @throws Exception
     */
    public function pegar($id_disponibilidade) {
        $dal = DisponibilidadeDALFactory::create();
        return $dal->pegar($id_disponibilidade);
    }

    /**
     * @throws Exception
     * @param DisponibilidadeInfo $disponibilidade
     * @return int
     */
    public function inserir($disponibilidade) {
        $dal = DisponibilidadeDALFactory::create();
        return $dal->inserir($disponibilidade);
    }

    /**
     * @param DisponibilidadeInfo $disponibilidade
     * @throws Exception
     */
    public function alterar($disponibilidade) {
        $dal = DisponibilidadeDALFactory::create();
        $dal->alterar($disponibilidade);
    }

    /**
     * @param int $id_disponibilidade
     * @throws Exception
     */
    public function excluir($id_disponibilidade) {
        $dal = DisponibilidadeDALFactory::create();
        $dal->excluir($id_disponibilidade);
    }
}
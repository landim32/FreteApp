<?php
namespace Emagine\Frete\IDAL;

use Exception;
use Emagine\Frete\Model\TipoCarroceriaInfo;

interface IFreteTipoCarroceriaDAL {

    /**
     * @throws Exception
     * @param int $id_frete
     * @return TipoCarroceriaInfo[]
     */
    public function listarPorFrete($id_frete);

    /**
     * @param int $id_frete
     * @param int $id_carroceria
     * @throws Exception
     */
    public function inserir($id_frete, $id_carroceria);

    /**
     * @throws Exception
     * @param int $id_frete
     */
    public function limpar($id_frete);

}


<?php
namespace Emagine\Frete\IDAL;

use Exception;
use Emagine\Frete\Model\TipoVeiculoInfo;

interface IFreteTipoVeiculoDAL {

    /**
     * @throws Exception
     * @param int $id_frete
     * @return TipoVeiculoInfo[]
     */
    public function listarPorFrete($id_frete);

    /**
     * @param int $id_frete
     * @param int $id_tipo
     * @throws Exception
     */
    public function inserir($id_frete, $id_tipo);

    /**
     * @throws Exception
     * @param int $id_frete
     */
    public function limpar($id_frete);

}


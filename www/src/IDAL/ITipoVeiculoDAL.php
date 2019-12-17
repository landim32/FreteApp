<?php
namespace Emagine\Frete\IDAL;

use Exception;
use Emagine\Frete\Model\TipoVeiculoInfo;

interface ITipoVeiculoDAL {

    /**
     * @return string
     */
    public function query();

	/**
     * @throws Exception
	 * @return TipoVeiculoInfo[]
	 */
	public function listar();

	/**
     * @throws Exception
	 * @param int $id_tipo
	 * @return TipoVeiculoInfo
	 */
	public function pegar($id_tipo);

    /**
     * @throws Exception
     * @param TipoVeiculoInfo $veiculo
     * @return int
     */
    public function inserir(TipoVeiculoInfo $veiculo);

    /**
     * @throws Exception
     * @param TipoVeiculoInfo $veiculo
     */
    public function alterar(TipoVeiculoInfo $veiculo);

    /**
     * @throws Exception
     * @param int $id_tipo
     */
    public function excluir($id_tipo);
}


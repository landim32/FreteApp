<?php
namespace Emagine\Frete\IDAL;

use Exception;
use Emagine\Frete\Model\TipoVeiculoInfo;

interface ITipoVeiculoDAL {

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

}


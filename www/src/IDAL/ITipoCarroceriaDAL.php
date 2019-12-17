<?php
namespace Emagine\Frete\IDAL;

use Exception;
use Emagine\Frete\Model\TipoCarroceriaInfo;

interface ITipoCarroceriaDAL {

	/**
     * @throws Exception
	 * @return TipoCarroceriaInfo[]
	 */
	public function listar();

	/**
     * @throws Exception
	 * @param int $id_carroceria
	 * @return TipoCarroceriaInfo
	 */
	public function pegar($id_carroceria);

}


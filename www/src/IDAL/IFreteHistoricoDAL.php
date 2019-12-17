<?php

namespace Emagine\Frete\IDAL;

use Emagine\Frete\Model\FreteHistoricoInfo;

interface IFreteHistoricoDAL {

	/**
	 * @param int $id_frete
	 * @return FreteHistoricoInfo[]
	 */
	public function listar($id_frete);

	/**
	 * @param FreteHistoricoInfo $historico
	 * @return int
	 */
	public function inserir($historico);

	/**
	 * @param int $id_frete
	 */
	public function limpar($id_frete);

}


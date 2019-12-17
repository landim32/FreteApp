<?php
namespace Emagine\Frete\IDAL;

use Emagine\Frete\Model\FreteLocalInfo;

interface IFreteLocalDAL {

	/**
	 * @param int $id_frete
	 * @return FreteLocalInfo[]
	 */
	public function listar($id_frete);

	/**
	 * @param int $id_local
	 * @return FreteLocalInfo
	 */
	public function pegar($id_local);

	/**
	 * @param FreteLocalInfo $local
	 * @return int
	 */
	public function inserir($local);

	/**
	 * @param int $id_frete
	 */
	public function limpar($id_frete);

}


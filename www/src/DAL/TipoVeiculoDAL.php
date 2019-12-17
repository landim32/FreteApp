<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 08/12/2017
 * Time: 01:10
 * Tablename: frete_local
 */

namespace Emagine\Frete\DAL;

use PDO;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Frete\IDAL\ITipoVeiculoDAL;
use Emagine\Frete\Model\TipoVeiculoInfo;

class TipoVeiculoDAL implements ITipoVeiculoDAL {

	/**
	 * @return string
	 */
	protected function query() {
		return "
			SELECT 
				veiculo_tipo.id_tipo,
                veiculo_tipo.nome,
                veiculo_tipo.cod_tipo,
                veiculo_tipo.foto,
                veiculo_tipo.capacidade
			FROM veiculo_tipo
		";
	}

	/**
     * @throws Exception
	 * @return TipoVeiculoInfo[]
	 */
	public function listar() {
		$query = $this->query();
		$db = DB::getDB()->prepare($query);
		return DB::getResult($db,"Emagine\\Frete\\Model\\TipoVeiculoInfo");
	}

	/**
     * @throws Exception
	 * @param int $id_tipo
	 * @return TipoVeiculoInfo
	 */
	public function pegar($id_tipo) {
		$query = $this->query() . "
			WHERE veiculo_tipo.id_tipo = :id_tipo
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_tipo", $id_tipo, PDO::PARAM_INT);
		return DB::getValueClass($db,"Emagine\\Frete\\Model\\TipoVeiculoInfo");
	}

}


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
use Emagine\Frete\IDAL\ITipoCarroceriaDAL;
use Emagine\Frete\Model\TipoCarroceriaInfo;

class TipoCarroceriaDAL implements ITipoCarroceriaDAL {

	/**
	 * @return string
	 */
	protected function query() {
		return "
			SELECT 
				veiculo_carroceria.id_carroceria,
                veiculo_carroceria.nome,
                veiculo_carroceria.foto
			FROM veiculo_carroceria
		";
	}

	/**
     * @throws Exception
	 * @return TipoCarroceriaInfo[]
	 */
	public function listar() {
		$query = $this->query();
		$db = DB::getDB()->prepare($query);
		return DB::getResult($db,"Emagine\\Frete\\Model\\TipoCarroceriaInfo");
	}

	/**
     * @throws Exception
	 * @param int $id_carroceria
	 * @return TipoCarroceriaInfo
	 */
	public function pegar($id_carroceria) {
		$query = $this->query() . "
			WHERE veiculo_carroceria.id_carroceria = :id_carroceria
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_carroceria", $id_carroceria, PDO::PARAM_INT);
		return DB::getValueClass($db,"Emagine\\Frete\\Model\\TipoCarroceriaInfo");
	}

}


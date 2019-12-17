<?php

namespace Emagine\Frete\DAL;

use PDO;
use PDOStatement;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Frete\IDAL\IFreteHistoricoDAL;
use Emagine\Frete\Model\FreteHistoricoInfo;

class FreteHistoricoDAL implements IFreteHistoricoDAL {

	/**
	 * @return string
	 */
	public function query() {
		return "
			SELECT 
                frete_historico.id_historico,
                frete_historico.id_frete,
                frete_historico.data_inclusao,
                frete_historico.latitude,
                frete_historico.longitude,
                frete_historico.endereco
			FROM frete_historico
		";
	}

	/**
     * @throws Exception
	 * @param int $id_frete
	 * @return FreteHistoricoInfo[]
	 */
	public function listar($id_frete) {
		$query = $this->query() . "
			WHERE frete_historico.id_frete = :id_frete
			ORDER BY frete_historico.data_inclusao
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_frete", $id_frete, PDO::PARAM_INT);
		return DB::getResult($db,"Emagine\\Frete\\Model\\FreteHistoricoInfo");
	}

	/**
	 * @param PDOStatement $db
	 * @param FreteHistoricoInfo $historico
	 */
	public function preencherCampo(PDOStatement $db, FreteHistoricoInfo $historico) {
		$db->bindValue(":id_frete", $historico->getIdFrete());
		$db->bindValue(":latitude", $historico->getLatitude());
		$db->bindValue(":longitude", $historico->getLongitude());
		$db->bindValue(":endereco", $historico->getEndereco());
	}

	/**
     * @throws Exception
	 * @param FreteHistoricoInfo $historico
	 * @return int
	 */
	public function inserir($historico) {
		$query = "
			INSERT INTO frete_historico (
				id_frete,
				data_inclusao,
				latitude,
				longitude,
				endereco
			) VALUES (
				:id_frete,
				NOW(),
				:latitude,
				:longitude,
				:endereco
			)
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $historico);
		$db->execute();
		return DB::lastInsertId();
	}

	/**
     * @throws Exception
	 * @param int $id_frete
	 */
	public function limpar($id_frete) {
		$query = "
			DELETE FROM frete_historico
			WHERE id_frete = :id_frete
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_frete", $id_frete, PDO::PARAM_INT);
		$db->execute();
	}

}


<?php
namespace Emagine\Frete\MaisCargas\DAL;

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
                driverposition.Id AS 'id_historico',
                driverposition.FreightId AS 'id_frete',
                driverposition.Date AS 'data_inclusao',
                driverposition.Lat AS 'latitude',
                driverposition.Lon AS 'longitude',
                null AS 'endereco'
			FROM driverposition
		";
	}

	/**
     * @throws Exception
	 * @param int $id_frete
	 * @return FreteHistoricoInfo[]
	 */
	public function listar($id_frete) {
		$query = $this->query() . "
			WHERE driverposition.FreightId = :id_frete
			ORDER BY driverposition.Date
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
		$db->bindValue(":FreightId", $historico->getIdFrete());
		$db->bindValue(":Lat", $historico->getLatitude());
		$db->bindValue(":Lon", $historico->getLongitude());
	}

    /**
     * @throws Exception
     * @param int $id_frete
     * @return int
     */
	private function pegarIdMotorista($id_frete) {
        $query = "
            SELECT DriverId
            FROM freightdriver
			WHERE freightdriver.FreightId = :id_frete
			AND freightdriver.Status = 1
			LIMIT 1
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_frete", $id_frete, PDO::PARAM_INT);
        return intval(DB::getValue($db,"DriverId"));
    }

	/**
     * @throws Exception
	 * @param FreteHistoricoInfo $historico
	 * @return int
	 */
	public function inserir($historico) {
	    $id_motorista = $this->pegarIdMotorista($historico->getIdFrete());
	    if (!($id_motorista > 0)) {
	        return 0;
        }
		$query = "
			INSERT INTO driverposition (
				DriverId,
				FreightId,
				Lat,
				Lon,
				Date
			) VALUES (
				:DriverId,
				:FreightId,
				:Lat,
				:Lon,
				NOW()
			)
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $historico);
        $db->bindValue(":DriverId", $id_motorista, PDO::PARAM_INT);
		$db->execute();
		return DB::lastInsertId();
	}

	/**
     * @throws Exception
	 * @param int $id_frete
	 */
	public function limpar($id_frete) {
		$query = "
			DELETE FROM freightdriver
			WHERE FreightId = :id_frete
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_frete", $id_frete, PDO::PARAM_INT);
		$db->execute();
	}

}


<?php
namespace Emagine\Frete\MaisCargas\DAL;

use PDO;
use PDOStatement;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Frete\IDAL\IFreteLocalDAL;
use Emagine\Frete\Model\FreteLocalInfo;

/**
 * Class FreteLocalDAL
 * @package Emagine\Frete\DAL
 * @tablename frete_local
 * @author EmagineCRUD
 */
class FreteLocalDAL implements IFreteLocalDAL {

	/**
	 * @return string
	 */
	public function queryOrigem() {
		return "
			SELECT 
				freightwithdrawal.Id AS 'id_local',
				freightwithdrawal.FreightId AS 'id_frete',
				:cod_origem AS 'cod_tipo',
				freightwithdrawal.CEP AS 'cep',
				freightwithdrawal.Street AS 'logradouro',
				null AS 'complemento',
				freightwithdrawal.Number AS 'numero',
				null AS 'bairro',
				freightwithdrawal.City AS 'cidade',
				freightwithdrawal.UF AS 'uf',
				1 AS 'ordem',
				null AS 'latitude',
				null AS 'longitude'
			FROM freightwithdrawal
		";
	}

    /**
     * @return string
     */
    public function queryDestino() {
        return "
			SELECT 
				freightdelivery.Id AS 'id_local',
				freightdelivery.FreightId AS 'id_frete',
				:cod_destino AS 'cod_tipo',
				freightdelivery.CEP AS 'cep',
				freightdelivery.Street AS 'logradouro',
				null AS 'complemento',
				freightdelivery.Number AS 'numero',
				null AS 'bairro',
				freightdelivery.City AS 'cidade',
				freightdelivery.UF AS 'uf',
				2 AS 'ordem',
				null AS 'latitude',
				null AS 'longitude'
			FROM freightdelivery
		";
    }

	/**
     * @throws Exception
	 * @param int $id_frete
	 * @return FreteLocalInfo[]
	 */
	public function listar($id_frete) {
		$query = $this->queryOrigem() . "
			WHERE freightwithdrawal.FreightId = :id_origem
            UNION
		";
        $query .= $this->queryDestino() . "
			WHERE freightdelivery.FreightId = :id_destino
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_origem", $id_frete, PDO::PARAM_INT);
        $db->bindValue(":id_destino", $id_frete, PDO::PARAM_INT);
        $db->bindValue(":cod_origem", FreteLocalInfo::ORIGEM, PDO::PARAM_INT);
        $db->bindValue(":cod_destino", FreteLocalInfo::DESTINO, PDO::PARAM_INT);
		return DB::getResult($db,"Emagine\\Frete\\Model\\FreteLocalInfo");
	}

	/**
     * @throws Exception
	 * @param int $id_local
	 * @return FreteLocalInfo
	 */
	public function pegar($id_local) {
        $query = $this->queryOrigem() . "
			WHERE freightwithdrawal.Id = :id_local
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_local", $id_local, PDO::PARAM_INT);
        $db->bindValue(":cod_origem", FreteLocalInfo::ORIGEM, PDO::PARAM_INT);
		$local = DB::getValueClass($db,"Emagine\\Frete\\Model\\FreteLocalInfo");
		if (!is_null($local)) {
		    return $local;
        }

        $query = $this->queryDestino() . "
			WHERE freightdelivery.Id = :id_local
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_local", $id_local, PDO::PARAM_INT);
        $db->bindValue(":cod_destino", FreteLocalInfo::DESTINO, PDO::PARAM_INT);
        return DB::getValueClass($db,"Emagine\\Frete\\Model\\FreteLocalInfo");
	}

	/**
	 * @param PDOStatement $db
	 * @param FreteLocalInfo $local
	 */
	public function preencherCampo(PDOStatement $db, FreteLocalInfo $local) {
		$db->bindValue(":FreightId", $local->getIdFrete());
        $db->bindValue(":CEP", $local->getCep());
        $db->bindValue(":UF", $local->getUf());
        $db->bindValue(":City", $local->getCidade());
        $db->bindValue(":Street", $local->getLogradouro());
        $db->bindValue(":Number", $local->getNumero());
	}

	/**
     * @throws Exception
	 * @param FreteLocalInfo $local
	 * @return int
	 */
	public function inserir($local) {
	    $tabela = ($local->getCodTipo() == FreteLocalInfo::DESTINO) ? "freightdelivery" : "freightwithdrawal";
		$query = "
			INSERT INTO " . $tabela . " (
                FreightId,
                CEP,
                UF,
                City,
                Street,
                Number
			) VALUES (
                :FreightId,
                :CEP,
                :UF,
                :City,
                :Street,
                :Number
			)
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $local);
		$db->execute();
		return DB::lastInsertId();
	}

	/**
     * @throws Exception
	 * @param int $id_frete
	 */
	public function limpar($id_frete) {
		$query = "
			DELETE FROM freightwithdrawal
			WHERE FreightId = :id_frete
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_frete", $id_frete, PDO::PARAM_INT);
		$db->execute();

        $query = "
			DELETE FROM freightdelivery
			WHERE FreightId = :id_frete
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_frete", $id_frete, PDO::PARAM_INT);
        $db->execute();
	}

}


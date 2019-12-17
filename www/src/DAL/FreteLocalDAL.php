<?php
namespace Emagine\Frete\DAL;

use PDO;
use PDOStatement;
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
	public function query() {
		return "
			SELECT 
				frete_local.id_local,
				frete_local.id_frete,
				frete_local.cod_tipo,
				frete_local.cep,
				frete_local.logradouro,
				frete_local.complemento,
				frete_local.numero,
				frete_local.bairro,
				frete_local.cidade,
				frete_local.uf,
				frete_local.ordem,
				frete_local.latitude,
				frete_local.longitude
			FROM frete_local
		";
	}

	/**
     * @throws \Exception
	 * @param int $id_frete
	 * @return FreteLocalInfo[]
	 */
	public function listar($id_frete) {
		$query = $this->query() . "
			WHERE frete_local.id_frete = :id_frete
			ORDER BY frete_local.ordem
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_frete", $id_frete, PDO::PARAM_INT);
		return DB::getResult($db,"Emagine\\Frete\\Model\\FreteLocalInfo");
	}

	/**
     * @throws \Exception
	 * @param int $id_local
	 * @return FreteLocalInfo
	 */
	public function pegar($id_local) {
		$query = $this->query() . "
			WHERE frete_local.id_local = :id_local
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_local", $id_local, PDO::PARAM_INT);
		return DB::getValueClass($db,"Emagine\\Frete\\Model\\FreteLocalInfo");
	}

	/**
	 * @param PDOStatement $db
	 * @param FreteLocalInfo $local
	 */
	public function preencherCampo(PDOStatement $db, FreteLocalInfo $local) {
		$db->bindValue(":id_frete", $local->getIdFrete());
		$db->bindValue(":cod_tipo", $local->getCodTipo());
        $db->bindValue(":cep", $local->getCep());
        $db->bindValue(":logradouro", $local->getLogradouro());
        $db->bindValue(":complemento", $local->getComplemento());
        $db->bindValue(":numero", $local->getNumero());
        $db->bindValue(":bairro", $local->getBairro());
        $db->bindValue(":cidade", $local->getCidade());
        $db->bindValue(":uf", $local->getUf());
		$db->bindValue(":ordem", $local->getOrdem(), PDO::PARAM_INT);
		$db->bindValue(":latitude", $local->getLatitude());
		$db->bindValue(":longitude", $local->getLongitude());
	}

	/**
     * @throws \Exception
	 * @param FreteLocalInfo $local
	 * @return int
	 */
	public function inserir($local) {
		$query = "
			INSERT INTO frete_local (
				id_frete,
				cod_tipo,
				cep,
				logradouro,
				complemento,
				numero,
				bairro,
				cidade,
				uf,
				ordem,
				latitude,
				longitude
			) VALUES (
				:id_frete,
				:cod_tipo,
				:cep,
				:logradouro,
				:complemento,
				:numero,
				:bairro,
				:cidade,
				:uf,
				:ordem,
				:latitude,
				:longitude
			)
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $local);
		$db->execute();
		return DB::lastInsertId();
	}

	/**
     * @throws \Exception
	 * @param int $id_frete
	 */
	public function limpar($id_frete) {
		$query = "
			DELETE FROM frete_local
			WHERE id_frete = :id_frete
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_frete", $id_frete, PDO::PARAM_INT);
		$db->execute();
	}

}


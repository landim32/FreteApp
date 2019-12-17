<?php
namespace Emagine\Frete\MaisCargas\DAL;

use PDO;
use PDOStatement;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Login\IDAL\IUsuarioEnderecoDAL;
use Emagine\Login\Model\UsuarioEnderecoInfo;

class UsuarioEnderecoDAL implements IUsuarioEnderecoDAL {

	/**
	 * @return string
	 */
	private function query() {
		return "
			SELECT 
				enterpriseaddress.Id AS 'id_endereco',
				enterpriseaddress.EnterprisesId AS 'id_usuario',
				enterpriseaddress.CEP AS 'cep',
				enterpriseaddress.Street AS 'logradouro',
				null AS 'complemento',
				enterpriseaddress.Number AS 'numero',
				null AS 'bairro',
				enterpriseaddress.City AS 'cidade',
				enterpriseaddress.UF AS 'uf',
				null AS 'latitude',
				null AS 'longitude'
			FROM enterpriseaddress
		";
	}

	/**
     * @throws Exception
	 * @param int $id_usuario
	 * @return UsuarioEnderecoInfo[]
	 */
	public function listar($id_usuario) {
		$query = $this->query() . "
			WHERE enterpriseaddress.EnterprisesId = :id_usuario
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
		return DB::getResult($db,"Emagine\\Login\\Model\\UsuarioEnderecoInfo");
	}

	/**
	 * @param PDOStatement $db
	 * @param UsuarioEnderecoInfo $endereco
	 */
	public function preencherCampo(PDOStatement $db, UsuarioEnderecoInfo $endereco) {
        $db->bindValue(":UF", $endereco->getUf());
        $db->bindValue(":City", $endereco->getCidade());
        $db->bindValue(":Street", $endereco->getLogradouro());
        $db->bindValue(":Number", $endereco->getNumero());
        $db->bindValue(":CEP", $endereco->getCep());
	}

	/**
     * @throws Exception
	 * @param UsuarioEnderecoInfo $endereco
	 */
	public function inserir($endereco) {
		$query = "
			INSERT INTO enterpriseaddress (
			    EnterprisesId,
			    UF,
			    City,
			    Street,
			    Number,
			    CEP
			) VALUES (
				:EnterprisesId,
			    :UF,
			    :City,
			    :Street,
			    :Number,
			    :CEP
			)
		";
		$db = DB::getDB()->prepare($query);
        $db->bindValue(":EnterprisesId", $endereco->getIdUsuario(), PDO::PARAM_INT);
		$this->preencherCampo($db, $endereco);
		$db->execute();
	}

	/**
     * @throws Exception
	 * @param UsuarioEnderecoInfo $endereco
	 */
	public function alterar($endereco) {
		$query = "
			UPDATE enterpriseaddress SET
			    UF = :UF,
			    City = :City,
			    Street = :Street,
			    Number = :Number,
			    CEP = :CEP
			WHERE enterpriseaddress.Id = :id_endereco
		";
		$db = DB::getDB()->prepare($query);
        $this->preencherCampo($db, $endereco);
        $db->bindValue(":id_endereco", $endereco->getId(), PDO::PARAM_INT);
		$db->execute();
	}

	/**
     * @throws Exception
	 * @param int $id_endereco
	 */
	public function excluir($id_endereco) {
		$query = "
			DELETE FROM enterpriseaddress
			WHERE Id = :id_endereco
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_endereco", $id_endereco, PDO::PARAM_INT);
		$db->execute();
	}
	/**
     * @throws Exception
	 * @param int $id_usuario
	 */
	public function limpar($id_usuario) {
		$query = "
			DELETE FROM enterpriseaddress
			WHERE EnterprisesId = :id_usuario
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
		$db->execute();
	}

}


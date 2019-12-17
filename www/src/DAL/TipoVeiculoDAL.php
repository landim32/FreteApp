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
use PDOStatement;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Frete\IDAL\ITipoVeiculoDAL;
use Emagine\Frete\Model\TipoVeiculoInfo;

class TipoVeiculoDAL implements ITipoVeiculoDAL {

	/**
	 * @return string
	 */
	public function query() {
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

    /**
     * @param PDOStatement $db
     * @param TipoVeiculoInfo $veiculo
     */
    public function preencherCampo(PDOStatement $db, TipoVeiculoInfo $veiculo) {
        $db->bindValue(":nome", $veiculo->getNome());
        $db->bindValue(":cod_tipo", $veiculo->getCodTipo(), PDO::PARAM_INT);
        $db->bindValue(":foto", $veiculo->getFoto());
        $db->bindValue(":capacidade", $veiculo->getCapacidade(), PDO::PARAM_INT);
    }

    /**
     * @throws Exception
     * @param TipoVeiculoInfo $veiculo
     * @return int
     */
    public function inserir(TipoVeiculoInfo $veiculo) {
        $query = "
			INSERT INTO veiculo_tipo (
                nome,
                cod_tipo,
                foto,
                capacidade
			) VALUES (
                :nome,
                :cod_tipo,
                :foto,
                :capacidade
			)
		";
        $db = DB::getDB()->prepare($query);
        $this->preencherCampo($db, $veiculo);
        $db->execute();
        return DB::lastInsertId();
    }

    /**
     * @throws Exception
     * @param TipoVeiculoInfo $veiculo
     */
    public function alterar(TipoVeiculoInfo $veiculo) {
        $query = "
			UPDATE veiculo_tipo SET 
                nome = :nome,
                cod_tipo = :cod_tipo,
                foto = :foto,
                capacidade = :capacidade
			WHERE id_tipo = :id_tipo
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_tipo", $veiculo->getId(), PDO::PARAM_INT);
        $this->preencherCampo($db, $veiculo);
        $db->execute();
    }

    /**
     * @throws Exception
     * @param int $id_tipo
     */
    public function excluir($id_tipo) {
        $query = "
			DELETE FROM veiculo_tipo
			WHERE id_tipo = :id_tipo
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_tipo", $id_tipo, PDO::PARAM_INT);
        $db->execute();
    }

}


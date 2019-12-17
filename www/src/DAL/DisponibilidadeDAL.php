<?php

namespace Emagine\Frete\DAL;

use PDO;
use PDOStatement;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Frete\IDAL\IDisponibilidadeDAL;
use Emagine\Frete\Model\DisponibilidadeInfo;

class DisponibilidadeDAL implements IDisponibilidadeDAL {

	/**
	 * @return string
	 */
	private function query() {
		return "
			SELECT 
              disponibilidade.id_disponibilidade,
              disponibilidade.id_usuario,
              disponibilidade.data,
              disponibilidade.uf,
              disponibilidade.cidade
			FROM disponibilidade
		";
	}

	/**
     * @throws Exception
	 * @param int $id_usuario
	 * @return DisponibilidadeInfo[]
	 */
	public function listar($id_usuario) {
		$query = $this->query() . "
			WHERE disponibilidade.id_usuario = :id_usuario
			ORDER BY disponibilidade.data DESC
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
		return DB::getResult($db,"Emagine\\Frete\\Model\\DisponibilidadeInfo");
	}

    /**
     * @throws Exception
     * @param int $id_disponibilidade
     * @return DisponibilidadeInfo
     */
    public function pegar($id_disponibilidade) {
        $query = $this->query() . "
			WHERE disponibilidade.id_disponibilidade = :id_disponibilidade
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_disponibilidade", $id_disponibilidade, PDO::PARAM_INT);
        return DB::getValueClass($db,"Emagine\\Frete\\Model\\DisponibilidadeInfo");
    }

	/**
	 * @param PDOStatement $db
	 * @param DisponibilidadeInfo $disponibilidade
	 */
	public function preencherCampo(PDOStatement $db, DisponibilidadeInfo $disponibilidade) {
        $db->bindValue(":id_usuario", $disponibilidade->getIdUsuario());
        $db->bindValue(":data", $disponibilidade->getData());
        $db->bindValue(":uf", $disponibilidade->getUf());
        $db->bindValue(":cidade", $disponibilidade->getCidade());
	}

	/**
     * @throws Exception
	 * @param DisponibilidadeInfo $disponibilidade
	 * @return int
	 */
	public function inserir($disponibilidade) {
		$query = "
			INSERT INTO disponibilidade (
                id_usuario,
                data,
                uf,
                cidade
			) VALUES (
                :id_usuario,
                :data,
                :uf,
                :cidade
			)
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $disponibilidade);
		$db->execute();
		return DB::lastInsertId();
	}

    /**
     * @throws Exception
     * @param DisponibilidadeInfo $disponibilidade
     */
    public function alterar($disponibilidade) {
        $query = "
			UPDATE disponibilidade SET
                id_usuario = :id_usuario,
                data = :data,
                uf = :uf,
                cidade = :cidade
            WHERE id_disponibilidade = :id_disponibilidade
		";
        $db = DB::getDB()->prepare($query);
        $this->preencherCampo($db, $disponibilidade);
        $db->bindValue(":id_disponibilidade", $disponibilidade->getId(), PDO::PARAM_INT);
        $db->execute();
    }

    /**
     * @param int $id_disponibilidade
     * @throws Exception
     */
    public function excluir($id_disponibilidade) {
        $query = "
			DELETE FROM disponibilidade
			WHERE id_disponibilidade = :id_disponibilidade
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_disponibilidade", $id_disponibilidade, PDO::PARAM_INT);
        $db->execute();
    }

	/**
     * @throws Exception
	 * @param int $id_usuario
	 */
	public function limpar($id_usuario) {
		$query = "
			DELETE FROM disponibilidade
			WHERE id_usuario = :id_usuario
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
		$db->execute();
	}

}


<?php

namespace Emagine\CRM\DAL;

use PDO;
use PDOStatement;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\CRM\Model\ClienteInfo;
use Emagine\CRM\Model\TagInfo;

class TagDAL
{
    /**
     * @param bool $distinct
     * @return string
     */
    private function query($distinct = false) {
        return "
            SELECT " . (($distinct) ? "DISTINCT" : "") . "
                tag.id_tag,
                tag.slug,
                tag.nome
            FROM tag
        ";
    }

    /**
     * @throws Exception
     * @return TagInfo[]
     */
    public function listar() {
        $query = $this->query() . "
            ORDER BY tag.nome
        ";
        $db = DB::getDB()->prepare($query);
        $db->execute();
        return DB::getResult($db, "Emagine\\CRM\\Model\\TagInfo");
    }

    /**
     * @throws Exception
     * @return TagInfo[]
     */
    public function listarPopular() {
        $query = $this->query() . "
            INNER JOIN cliente_tag ON cliente_tag.id_tag = tag.id_tag
            INNER JOIN cliente ON cliente.id_cliente = cliente_tag.id_artigo
            WHERE cliente.cod_situacao = :cod_situacao
            GROUP BY
                tag.id_tag,
                tag.slug,
                tag.nome
            HAVING COUNT(cliente.id_cliente) > 0 
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":cod_situacao", ClienteInfo::ATIVO, PDO::PARAM_INT);
        $db->execute();
        return DB::getResult($db, "Emagine\\CRM\\Model\\TagInfo");
    }

    /**
     * @throws Exception
     * @param int $id_tag
     * @return TagInfo
     */
    public function pegar($id_tag) {
        $query = $this->query() . " 
            WHERE tag.id_tag = :id_tag
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_tag", $id_tag, PDO::PARAM_INT);
        $db->execute();
        return DB::getValueClass($db, "Emagine\\CRM\\Model\\TagInfo");
    }

    /**
     * @throws Exception
     * @param string $slug
     * @return TagInfo
     */
    public function pegarPorSlug($slug) {
        $query = $this->query() . " 
            WHERE tag.slug = :slug 
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":slug", $slug);
        $db->execute();
        return DB::getValueClass($db, "Emagine\\CRM\\Model\\TagInfo");
    }

    /**
     * @throws Exception
     * @param string $nome
     * @return TagInfo
     */
    public function pegarPorNome($nome) {
        $query = $this->query() . " 
            WHERE tag.nome = :nome 
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":nome", $nome);
        $db->execute();
        return DB::getValueClass($db, "Emagine\\CRM\\Model\\TagInfo");
    }

    /**
     * @throws Exception
     * @param int $id_atendimento
     * @return TagInfo[]
     */
    public function listarPorAtendimento($id_atendimento) {
        $query = $this->query() . "
            INNER JOIN atendimento_tag on atendimento_tag.id_tag = tag.id_tag
            WHERE atendimento_tag.id_atendimento = :id_atendimento
            ORDER BY tag.nome
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_atendimento", $id_atendimento, PDO::PARAM_INT);
        $db->execute();
        return DB::getResult($db, "Emagine\\CRM\\Model\\TagInfo");
    }

    /**
     * @throws Exception
     * @param int $id_cliente
     * @return TagInfo[]
     */
    public function listarPorCliente($id_cliente) {
        $query = $this->query() . "
            INNER JOIN cliente_tag on cliente_tag.id_tag = tag.id_tag
            WHERE cliente_tag.id_cliente = :id_cliente
            ORDER BY tag.nome
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_cliente", $id_cliente, PDO::PARAM_INT);
        $db->execute();
        return DB::getResult($db, "Emagine\\CRM\\Model\\TagInfo");
    }

    /**
     * @param PDOStatement $db
     * @param TagInfo $tag
     */
    private function preencharCampo($db, $tag) {
        $db->bindValue(":slug", $tag->getSlug());
        $db->bindValue(":nome", $tag->getNome());
    }

    /**
     * @throws Exception
     * @param TagInfo $tag
     * @return int
     */
    public function inserir($tag) {
        $query = "
            INSERT INTO tag (
                slug,
                nome
            ) VALUES (
                :slug,
                :nome
            )
        ";
        $db = DB::getDB()->prepare($query);
        $this->preencharCampo($db, $tag);
        $db->execute();
        return DB::lastInsertId();
    }

    /**
     * @throws Exception
     * @param TagInfo $tag
     */
    public function alterar($tag) {
        $query = "
            UPDATE tag SET
                slug = :slug,
                nome = :nome
            WHERE id_tag = :id_tag
        ";
        $db = DB::getDB()->prepare($query);
        $this->preencharCampo($db, $tag);
        $db->bindValue(":id_tag", $tag->getId(), PDO::PARAM_INT);
        $db->execute();
    }

    /**
     * @throws Exception
     * @param int $id_tag
     */
    public function excluir($id_tag) {
        $query = "
            DELETE FROM tag 
            WHERE id_tag = :id_tag
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_tag", $id_tag, PDO::PARAM_INT);
        $db->execute();
    }
}
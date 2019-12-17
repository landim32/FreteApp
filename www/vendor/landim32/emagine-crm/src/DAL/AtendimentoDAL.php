<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 30/06/2017
 * Time: 10:30
 */

namespace Emagine\CRM\DAL;

use PDO;
use PDOStatement;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\CRM\Model\AtendimentoRetornoInfo;
use Emagine\CRM\Model\AtendimentoInfo;

class AtendimentoDAL
{
    /**
     * @param bool $paginado
     * @return string
     */
    private function query($paginado = false) {
        return "
            SELECT " . (($paginado) ? "SQL_CALC_FOUND_ROWS" : "") . "
                atendimento.id_atendimento,
                atendimento.id_cliente,
                atendimento.id_usuario,
                atendimento.titulo,
                atendimento.url
            FROM atendimento
        ";
    }

    /**
     * @throws Exception
     * @param int $cod_situacao
     * @param int|null $id_usuario
     * @param int|null $id_cliente
     * @param string $palavra_chave
     * @param int $pg
     * @param int $numpg
     * @return AtendimentoRetornoInfo
     */
    public function listarPaginado($cod_situacao = 0, $id_usuario = null, $id_cliente = null, $palavra_chave = '', $pg = 1, $numpg = 10) {
        $query = $this->query(true);
        $query .= " WHERE (1=1) ";
        if ($cod_situacao > 0) {
            $query .= " 
                AND (
                    SELECT andamento.cod_situacao
                    FROM andamento
                    WHERE andamento.id_atendimento = atendimento.id_atendimento
                    ORDER BY andamento.data_inclusao DESC
                    LIMIT 1
                ) = :cod_situacao
            ";
        }
        if ($id_usuario > 0) {
            $query .= " AND atendimento.id_usuario = :id_usuario ";
        }
        if ($id_cliente > 0) {
            $query .= " AND atendimento.id_cliente = :id_cliente ";
        }
        if (!isNullOrEmpty($palavra_chave)) {
            $query .= " AND (
                atendimento.titulo LIKE :titulo OR
                atendimento.id_cliente IN (
                    SELECT cliente.id_cliente
                    FROM cliente
                    WHERE cliente.nome LIKE :nome
                    AND cliente.telefone1 LIKE :telefone
                    AND cliente.email1 LIKE :email
                )
            ) ";
        }
        $query .= " 
            ORDER BY (
                SELECT andamento.data_inclusao
                FROM andamento
                WHERE andamento.id_atendimento = atendimento.id_atendimento
                ORDER BY andamento.data_inclusao DESC
                LIMIT 1
            ) DESC
        ";
        $pgini = (($pg - 1) * $numpg);
        $query .= " LIMIT " . $pgini . ", " . $numpg;

        $db = DB::getDB()->prepare($query);
        if ($cod_situacao > 0) {
            $db->bindValue(":cod_situacao", $cod_situacao, PDO::PARAM_INT);
        }
        if ($id_usuario > 0) {
            $db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
        }
        if ($id_cliente > 0) {
            $db->bindValue(":id_cliente", $id_cliente, PDO::PARAM_INT);
        }
        if (!isNullOrEmpty($palavra_chave)) {
            $palavra = '%' . $palavra_chave . '%';
            $db->bindValue(":titulo", $palavra, PDO::PARAM_STR);
            $db->bindValue(":nome", $palavra, PDO::PARAM_STR);
            $db->bindValue(":telefone", $palavra, PDO::PARAM_STR);
            $db->bindValue(":email", $palavra, PDO::PARAM_STR);
        }
        $db->execute();
        $atendimentos = DB::getResult($db, "Emagine\\CRM\\Model\\AtendimentoInfo");
        $total = DB::getDB()->query('SELECT FOUND_ROWS();')->fetch(PDO::FETCH_COLUMN);
        return new AtendimentoRetornoInfo($atendimentos, $total);
    }

    /**
     * @throws Exception
     * @param int|null $id_cliente
     * @param int|null $id_usuario
     * @param int|null $cod_situacao
     * @return AtendimentoInfo[]
     */
    public function listar($id_cliente = null, $id_usuario = null, $cod_situacao = null) {
        $query = $this->query() . " 
            WHERE (1=1) 
        ";
        if (!is_null($id_cliente)) {
            $query .= " AND atendimento.id_cliente = :id_cliente ";
        }
        if (!is_null($id_usuario)) {
            $query .= " AND atendimento.id_usuario = :id_usuario ";
        }
        /*
        if (!is_null($cod_situacao)) {
            $query .= " AND atendimento.cod_situacao = :cod_situacao ";
        }
        */
        $db = DB::getDB()->prepare($query);
        if (!is_null($id_cliente)) {
            $db->bindValue(":id_cliente", $id_cliente, PDO::PARAM_INT);
        }
        if (!is_null($id_usuario)) {
            $db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
        }
        /*
        if (!is_null($cod_situacao)) {
            $db->bindValue(":cod_situacao", $cod_situacao, PDO::PARAM_INT);
        }
        */
        $db->execute();
        return DB::getResult($db, "Emagine\\CRM\\Model\\AtendimentoInfo");
    }

    /**
     * @throws Exception
     * @return array<int,int>
     */
    public function quantidadePorSituacao() {
        $query = "
            SELECT 
	            andamento.cod_situacao,
	            COUNT(*) AS 'quantidade'
            FROM andamento
            WHERE andamento.id_andamento = (
	            SELECT 
		            andamento2.id_andamento
	            FROM andamento AS andamento2
                WHERE andamento2.id_atendimento = andamento.id_atendimento
                ORDER BY andamento2.data_inclusao DESC
                LIMIT 1
            )
            GROUP BY 
	            andamento.cod_situacao
        ";
        $db = DB::getDB()->prepare($query);
        $db->execute();
        return DB::getDictionary($db, "cod_situacao", "quantidade");
    }

    /**
     * @throws Exception
     * @param int $id_atendimento
     * @return AtendimentoInfo
     */
    public function pegar($id_atendimento) {
        $query = $this->query() . " 
            WHERE atendimento.id_atendimento = :id_atendimento 
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_atendimento", $id_atendimento, PDO::PARAM_INT);
        $db->execute();
        return DB::getValueClass($db, "Emagine\\CRM\\Model\\AtendimentoInfo");
    }

    /**
     * @throws Exception
     * @param string $url
     * @return AtendimentoInfo
     */
    public function pegarPorUrl($url) {
        $query = $this->query() . " 
            WHERE atendimento.url = :url 
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":url", $url);
        $db->execute();
        return DB::getValueClass($db, "Emagine\\CRM\\Model\\AtendimentoInfo");
    }

    /**
     * @param PDOStatement $db
     * @param AtendimentoInfo $atendimento
     */
    private function preencherCampo($db, $atendimento) {
        $db->bindValue(":id_cliente", $atendimento->getIdCliente(), PDO::PARAM_INT);
        $db->bindValue(":id_usuario", $atendimento->getIdUsuario(), PDO::PARAM_INT);
        $db->bindValue(":titulo", $atendimento->getTitulo());
        $db->bindValue(":url", $atendimento->getUrl());
    }

    /**
     * @throws Exception
     * @param AtendimentoInfo $atendimento
     * @return int
     */
    public function inserir($atendimento) {
        $query = "
            INSERT INTO atendimento (
                id_cliente,
                id_usuario,
                titulo,
                url
            ) VALUES (
                :id_cliente,
                :id_usuario,
                :titulo,
                :url            
            )
        ";
        $db = DB::getDB()->prepare($query);
        $this->preencherCampo($db, $atendimento);
        $db->execute();
        return DB::lastInsertId();
    }

    /**
     * @throws Exception
     * @param AtendimentoInfo $atendimento
     */
    public function alterar($atendimento) {
        $query = "
            UPDATE atendimento SET
                id_cliente = :id_cliente,
                id_usuario = :id_usuario,
                titulo = :titulo,
                url = :url
            WHERE id_atendimento = :id_atendimento
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_atendimento", $atendimento->getId(), PDO::PARAM_INT);
        $this->preencherCampo($db, $atendimento);
        $db->execute();
    }

    /**
     * @throws Exception
     * @param int $id_atendimento
     */
    public function excluir($id_atendimento) {
        $query = "
            DELETE FROM atendimento
            WHERE id_atendimento = :id_atendimento
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_atendimento", $id_atendimento, PDO::PARAM_INT);
        $db->execute();
    }

    /**
     * @throws Exception
     * @param int $id_atendimento
     * @param int $id_tag
     * @return int
     */
    public function pegarQuantidadeTag($id_atendimento, $id_tag) {
        $query = "
            SELECT COUNT(*) AS 'quantidade'
            FROM atendimento_tag
            WHERE id_atendimento = :id_atendimento
            AND id_tag = :id_tag
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_atendimento", $id_atendimento, PDO::PARAM_INT);
        $db->bindValue(":id_tag", $id_tag, PDO::PARAM_INT);
        $db->execute();
        return DB::getValue($db, "quantidade");
    }

    /**
     * @throws Exception
     * @param int $id_atendimento
     * @param int $id_tag
     */
    public function inserirTag($id_atendimento, $id_tag) {
        $query = "
            INSERT INTO atendimento_tag (
                id_atendimento,
                id_tag
            ) VALUES (
                :id_atendimento,
                :id_tag
            )
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_atendimento", $id_atendimento, PDO::PARAM_INT);
        $db->bindValue(":id_tag", $id_tag, PDO::PARAM_INT);
        $db->execute();
    }

    /**
     * @throws Exception
     * @param int $id_atendimento
     */
    public function limparTags($id_atendimento) {
        $query = "
            DELETE FROM atendimento_tag
            WHERE id_atendimento = :id_atendimento
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_atendimento", $id_atendimento, PDO::PARAM_INT);
        $db->execute();
    }
}
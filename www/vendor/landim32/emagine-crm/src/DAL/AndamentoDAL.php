<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 30/06/2017
 * Time: 11:16
 */

namespace Emagine\CRM\DAL;

use PDO;
use PDOStatement;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\CRM\Model\AndamentoInfo;
use Emagine\CRM\Model\AtendimentoRetornoInfo;

class AndamentoDAL
{

    /**
     * @return string
     */
    private function query() {
        return "
            SELECT 
                andamento.id_andamento,
                andamento.id_atendimento,
                andamento.id_cliente,
                andamento.id_usuario,
                andamento.cod_situacao,
                andamento.data_inclusao,
                andamento.valor_proposta,
                andamento.mensagem
            FROM andamento
        ";
    }

    /**
     * @throws Exception
     * @param int $id_atendimento
     * @return AndamentoInfo[]
     */
    public function listar($id_atendimento) {
        $query = $this->query() . " 
            WHERE andamento.id_atendimento = :id_atendimento
            ORDER BY andamento.data_inclusao DESC
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_atendimento", $id_atendimento, PDO::PARAM_INT);
        $db->execute();
        return DB::getResult($db, "Emagine\\CRM\\Model\\AndamentoInfo");
    }

    /**
     * @param PDOStatement $db
     * @param AndamentoInfo $andamento
     */
    private function preencherCampo($db, $andamento) {
        $db->bindValue(":id_atendimento", $andamento->getIdAtendimento(), PDO::PARAM_INT);
        $db->bindValue(":id_cliente", $andamento->getIdCliente(), PDO::PARAM_INT);
        $db->bindValue(":id_usuario", $andamento->getIdUsuario(), PDO::PARAM_INT);
        $db->bindValue(":data_inclusao", $andamento->getDataInclusao());
        $db->bindValue(":cod_situacao", $andamento->getCodSituacao(), PDO::PARAM_INT);
        $db->bindValue(":valor_proposta", number_format( $andamento->getValorProposta(), 2, '.', ''));
        $db->bindValue(":mensagem", $andamento->getMensagem());
    }

    /**
     * @throws Exception
     * @param AndamentoInfo $andamento
     * @return int
     */
    public function inserir($andamento) {
        $query = "
            INSERT INTO andamento (
                id_atendimento,
                id_cliente,
                id_usuario,
                cod_situacao,
                data_inclusao,
                valor_proposta,
                mensagem
            ) VALUES (
                :id_atendimento,
                :id_cliente,
                :id_usuario,
                :cod_situacao,
                :data_inclusao,
                :valor_proposta,
                :mensagem
            )
        ";
        $db = DB::getDB()->prepare($query);
        $this->preencherCampo($db, $andamento);
        $db->execute();
        return DB::lastInsertId();
    }

    /**
     * @throws Exception
     * @param AndamentoInfo $andamento
     */
    public function alterar($andamento) {
        $query = "
            UPDATE andamento SET
                id_atendimento = :id_atendimento,
                id_cliente = :id_cliente,
                id_usuario = :id_usuario,
                cod_situacao = :cod_situacao,
                data_inclusao = :data_inclusao,
                valor_proposta = :valor_proposta,
                mensagem = :mensagem
            WHERE id_andamento = :id_andamento
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_andamento", $andamento->getId(), PDO::PARAM_INT);
        $this->preencherCampo($db, $andamento);
        $db->execute();
    }

    /**
     * @throws Exception
     * @param int $id_atendimento
     */
    public function limpar($id_atendimento) {
        $query = "
            DELETE FROM andamento
            WHERE id_atendimento = :id_atendimento 
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_atendimento", $id_atendimento, PDO::PARAM_INT);
        $db->execute();
    }

    /**
     * @throws Exception
     * @param int[] $ids
     */
    public function limparExcerto($ids) {
        $query = "
            DELETE FROM andamento
            WHERE id_andamento NOT IN " . implode(", ", $ids) . "
        ";
        $db = DB::getDB()->prepare($query);
        $db->execute();
    }

    /**
     * @throws Exception
     * @param int $id_andamento
     */
    public function excluir($id_andamento) {
        $query = "
            DELETE FROM andamento
            WHERE id_andamento = :id_andamento
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_andamento", $id_andamento, PDO::PARAM_INT);
        $db->execute();
    }
}
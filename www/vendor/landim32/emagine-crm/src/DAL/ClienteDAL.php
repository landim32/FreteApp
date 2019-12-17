<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 24/06/2017
 * Time: 10:58
 */

namespace Emagine\CRM\DAL;

use PDO;
use PDOStatement;
use Landim32\EasyDB\DB;
use Emagine\CRM\Model\ClienteInfo;
use Emagine\CRM\Model\ClienteRetornoInfo;
use Exception;

class ClienteDAL
{
    const ORDENAR_DATA = "data_inclusao";
    const ORDENAR_RAND = "random";

    /**
     * @param bool $paginado
     * @return string
     */
    public function query($paginado = false) {
        return "
            SELECT " . (($paginado) ? "SQL_CALC_FOUND_ROWS" : "") . "
                cliente.id_cliente,
                cliente.id_usuario,
                cliente.nome,
                cliente.telefone1,
                cliente.telefone2,
                cliente.email1 AS 'email',
                cliente.rg,
                cliente.cpf_cnpj,
                cliente.nacionalidade,
                cliente.estado_civil,
                cliente.profissao,
                cliente.empresa,
                cliente.site_url,
                cliente.cod_situacao,
                cliente.quantidade_enviado
            FROM cliente
        ";
    }

    /**
     * @throws Exception
     * @param int $id_usuario
     * @param int $codSituacao
     * @param string $orderby
     * @param int $limite
     * @return ClienteInfo[]
     */
    public function listar($id_usuario = 0, $codSituacao = 0, $orderby = "", $limite = 0) {
        $query = $this->query() . "
            WHERE (1=1)
        ";
        if ($id_usuario > 0) {
            $query .= " AND cliente.id_usuario = :id_usuario ";
        }
        if ($codSituacao > 0) {
            $query .= " AND cliente.cod_situacao = :cod_situacao ";
        }
        if (!isNullOrEmpty($orderby)) {
            switch ($orderby) {
                case ClienteDAL::ORDENAR_DATA:
                    $query .= " ORDER BY cliente.data_inclusao ";
                    break;
                case ClienteDAL::ORDENAR_RAND:
                    $query .= " ORDER BY cliente.quantidade_enviado, RAND() ";
                    break;
            }
        }
        if ($limite > 0) {
            $query .= " LIMIT " . $limite . " ";
        }
        $db = DB::getDB()->prepare($query);
        if ($id_usuario > 0) {
            $db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
        }
        if ($codSituacao > 0) {
            $db->bindValue(":cod_situacao", $codSituacao, PDO::PARAM_INT);
        }
        $db->execute();
        return DB::getResult($db, "Emagine\\CRM\\Model\\ClienteInfo");
    }

    /**
     * @throws Exception
     * @param int $cod_situacao
     * @param string $palavra_chave
     * @param string $tag_slug
     * @param int $pg Pagina atual
     * @param int $numpg Quantidade de itens visualizados
     * @return ClienteRetornoInfo
     */
    public function listarPaginado($cod_situacao = 0, $palavra_chave = '', $tag_slug = '', $pg = 1, $numpg = 10) {
        $query = $this->query(true);
        $query .= " WHERE (1=1) ";
        if (!isNullOrEmpty($palavra_chave)) {
            $query .= " AND (
                cliente.nome LIKE :nome OR
                cliente.telefone1 LIKE :telefone1 OR
                cliente.telefone2 LIKE :telefone2 OR
                cliente.email1 LIKE :email1
            ) ";
        }
        if (!isNullOrEmpty($tag_slug)) {
            $query .= " AND cliente.id_cliente IN (
                SELECT cliente_tag.id_cliente
                FROM cliente_tag
                INNER JOIN tag ON tag.id_tag = cliente_tag.id_tag
                WHERE tag.slug = :slug
            ) ";
        }
        if ($cod_situacao > 0) {
            $query .= " AND cliente.cod_situacao = :cod_situacao ";
        }
        $query .= " ORDER BY cliente.nome ";
        $pgini = (($pg - 1) * $numpg);
        $query .= " LIMIT " . $pgini . ", " . $numpg;

        $db = DB::getDB()->prepare($query);
        if (!isNullOrEmpty($palavra_chave)) {
            $palavra = '%' . $palavra_chave . '%';
            $db->bindValue(":nome", $palavra, PDO::PARAM_STR);
            $db->bindValue(":telefone1", $palavra, PDO::PARAM_STR);
            $db->bindValue(":telefone2", $palavra, PDO::PARAM_STR);
            $db->bindValue(":email1", $palavra, PDO::PARAM_STR);
        }
        if (!isNullOrEmpty($tag_slug)) {
            $db->bindValue(":slug", $tag_slug);
        }
        if ($cod_situacao > 0) {
            $db->bindValue(":cod_situacao", $cod_situacao, PDO::PARAM_INT);
        }
        $db->execute();
        $clientes = DB::getResult($db, "Emagine\\CRM\\Model\\ClienteInfo");
        $total = DB::getDB()->query('SELECT FOUND_ROWS();')->fetch(PDO::FETCH_COLUMN);
        return new ClienteRetornoInfo($clientes, $total);
    }

    /**
     * @throws Exception
     * @param int $id_tag
     * @param int $cod_situacao
     * @return ClienteInfo[]
     */
    public function listarPorTag($id_tag, $cod_situacao = 0) {
        $query = $this->query() . "
            INNER JOIN cliente_tag ON cliente_tag.id_cliente = cliente.id_cliente
            WHERE cliente_tag.id_tag = :id_tag
        ";
        if ($cod_situacao > 0) {
            $query .= " AND cliente.cod_situacao = :cod_situacao ";
        }
        $query .= " ORDER BY cliente.nome ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_tag", $id_tag, PDO::PARAM_INT);
        if ($cod_situacao > 0) {
            $db->bindValue(":cod_situacao", $cod_situacao, PDO::PARAM_INT);
        }
        $db->execute();
        return DB::getResult($db, "Emagine\\CRM\\Model\\ClienteInfo");
    }

    /**
     * @throws Exception
     * @param int $id_cliente
     * @return ClienteInfo
     */
    public function pegar($id_cliente) {
        $query = $this->query() . " 
            WHERE cliente.id_cliente = :id_cliente 
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_cliente", $id_cliente, PDO::PARAM_INT);
        $db->execute();
        return DB::getValueClass($db, "Emagine\\CRM\\Model\\ClienteInfo");
    }

    /**
     * @throws Exception
     * @param string $email
     * @return ClienteInfo
     */
    public function pegarPorEmail($email) {
        $query = $this->query() . " 
            WHERE cliente.email1 = :email 
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":email", $email);
        $db->execute();
        return DB::getValueClass($db, "Emagine\\CRM\\Model\\ClienteInfo");
    }

    /**
     * @param PDOStatement $db
     * @param ClienteInfo $cliente
     */
    private function preencherCampo($db, $cliente) {
        $db->bindValue(":id_usuario", $cliente->getIdUsuario(), PDO::PARAM_INT);
        $db->bindValue(":nome", $cliente->getNome());
        $db->bindValue(":telefone1", $cliente->getTelefone1());
        $db->bindValue(":telefone2", $cliente->getTelefone2());
        $db->bindValue(":email1", $cliente->getEmail());
        $db->bindValue(":rg", $cliente->getRg());
        $db->bindValue(":cpf_cnpj", $cliente->getCpfCnpj());
        $db->bindValue(":nacionalidade", $cliente->getNacionalidade());
        $db->bindValue(":estado_civil", $cliente->getEstadoCivil());
        $db->bindValue(":profissao", $cliente->getProfissao());
        $db->bindValue(":empresa", $cliente->getEmpresa());
        $db->bindValue(":site_url", $cliente->getSiteUrl());
        $db->bindValue(":cod_situacao", $cliente->getCodSituacao(), PDO::PARAM_INT);
    }

    /**
     * @throws Exception
     * @param ClienteInfo $cliente
     * @return int
     */
    public function inserir($cliente) {
        $query = "
            INSERT INTO cliente (
                id_usuario,
                data_inclusao,
                ultima_alteracao,
                nome,
                telefone1,
                telefone2,
                email1,
                rg,
                cpf_cnpj,
                nacionalidade,
                estado_civil,
                profissao,
                empresa,
                site_url,
                cod_situacao
            ) VALUES (
                :id_usuario,
                NOW(),
                NOW(),
                :nome,
                :telefone1,
                :telefone2,
                :email1,
                :rg,
                :cpf_cnpj,
                :nacionalidade,
                :estado_civil,
                :profissao,
                :empresa,
                :site_url,
                :cod_situacao
            )
        ";
        $db = DB::getDB()->prepare($query);
        $this->preencherCampo($db, $cliente);
        $db->execute();
        return DB::lastInsertId();
    }

    /**
     * @throws Exception
     * @param ClienteInfo $cliente
     * @return int
     */
    public function alterar($cliente) {
        $query = "
            UPDATE cliente SET
                id_usuario = :id_usuario,
                ultima_alteracao = NOW(),
                nome = :nome,
                telefone1 = :telefone1,
                telefone2 = :telefone2,
                email1 = :email1,
                rg = :rg,
                cpf_cnpj = :cpf_cnpj,
                nacionalidade = :nacionalidade,
                estado_civil = :estado_civil,
                profissao = :profissao,
                empresa = :empresa,
                site_url = :site_url,
                cod_situacao = :cod_situacao
            WHERE id_cliente = :id_cliente
        ";
        $db = DB::getDB()->prepare($query);
        $this->preencherCampo($db, $cliente);
        $db->bindValue(":id_cliente", $cliente->getId(), PDO::PARAM_INT);
        $db->execute();
        return DB::lastInsertId();
    }

    /**
     * @throws Exception
     * @param int $id_cliente
     */
    public function limparTags($id_cliente) {
        $query = "
            DELETE FROM cliente_tag
            WHERE id_cliente = :id_cliente
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_cliente", $id_cliente, PDO::PARAM_INT);
        $db->execute();
    }

    /**
     * @throws Exception
     * @param int $id_cliente
     * @param int $id_tag
     * @return int
     */
    public function pegarQuantidadeTag($id_cliente, $id_tag) {
        $query = "
            SELECT COUNT(*) AS 'quantidade'
            FROM cliente_tag
            WHERE id_cliente = :id_cliente
            AND id_tag = :id_tag
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_cliente", $id_cliente, PDO::PARAM_INT);
        $db->bindValue(":id_tag", $id_tag, PDO::PARAM_INT);
        $db->execute();
        return DB::getValue($db, "quantidade");
    }

    /**
     * @throws Exception
     * @param int $id_cliente
     * @param int $id_tag
     */
    public function inserirTag($id_cliente, $id_tag) {
        //var_dump($id_artigo, $id_tag);
        $query = "
            INSERT INTO cliente_tag (
                id_cliente,
                id_tag
            ) VALUES (
                :id_cliente,
                :id_tag
            )
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_cliente", $id_cliente, PDO::PARAM_INT);
        $db->bindValue(":id_tag", $id_tag, PDO::PARAM_INT);
        $db->execute();
    }

    /**
     * @throws Exception
     * @param int $id_cliente
     */
    public function excluir($id_cliente) {
        $query = "
            DELETE FROM cliente 
            WHERE id_cliente = :id_cliente
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_cliente", $id_cliente, PDO::PARAM_INT);
        $db->execute();
    }

    /**
     * @throws Exception
     * @return array<int,int>
     */
    public function quantidadePorSituacao() {
        $query = "
            SELECT
                cod_situacao,
                COUNT(id_cliente) AS 'quantidade'
            FROM cliente
            GROUP BY cod_situacao
        ";
        $db = DB::getDB()->prepare($query);
        $db->execute();
        return DB::getDictionary($db, "cod_situacao", "quantidade");
    }

    /**
     * @throws Exception
     * @param int $id_cliente
     */
    public function marcarEnviado($id_cliente) {
        $query = "
            UPDATE cliente SET
                quantidade_enviado = quantidade_enviado + 1 
            WHERE id_cliente = :id_cliente
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_cliente", $id_cliente, PDO::PARAM_INT);
        $db->execute();
    }

}
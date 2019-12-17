<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 25/06/2017
 * Time: 00:49
 */

namespace Emagine\CRM\DAL;

use PDO;
use Exception;
use Landim32\EasyDB\DB;

class ClienteOpcaoDAL
{
    /**
     * @return string
     */
    public function query() {
        return "
            SELECT
                chave,
                valor
            FROM cliente_opcao
        ";
    }

    /**
     * @throws Exception
     * @param int $id_cliente
     * @return array<string,string>
     */
    public function listar($id_cliente) {
        $query = $this->query() . "
            WHERE id_cliente = :id_cliente
            ORDER BY chave
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_cliente", $id_cliente, PDO::PARAM_INT);
        $db->execute();
        return DB::getDictionary($db, "chave", "valor");
    }

    /**
     * @throws Exception
     * @param int $id_cliente
     * @param string $chave
     * @return string
     */
    public function pegarValor($id_cliente, $chave) {
        $query = "
            SELECT valor
            FROM cliente_opcao
            WHERE id_cliente = :id_cliente
            AND chave = :chave
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_cliente", $id_cliente, PDO::PARAM_INT);
        $db->bindValue(":chave", $chave);
        $db->execute();
        return DB::getValue($db, "valor");
    }

    /**
     * @throws Exception
     * @param int $id_cliente
     */
    public function limpar($id_cliente) {
        $query = "
            DELETE FROM cliente_opcao
            WHERE id_cliente = :id_cliente
            ORDER BY chave
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_cliente", $id_cliente, PDO::PARAM_INT);
        $db->execute();
    }

    /**
     * @throws Exception
     * @param int $id_cliente
     * @param string $chave
     * @param string $valor
     */
    public function inserir($id_cliente, $chave, $valor) {
        $query = "
            INSERT INTO cliente_opcao (
                id_cliente,
                chave,
                valor
            ) VALUES (
                :id_cliente,
                :chave,
                :valor
            )
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_cliente", $id_cliente, PDO::PARAM_INT);
        $db->bindValue(":chave", $chave);
        $db->bindValue(":valor", $valor);
        $db->execute();
    }
}
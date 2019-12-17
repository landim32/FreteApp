<?php

namespace Emagine\CRM\DAL;

use Emagine\CRM\Model\ClienteEnderecoInfo;
use PDO;
use PDOStatement;
use Exception;
use Landim32\EasyDB\DB;

class EnderecoDAL
{

    /**
     * @return string
     */
    private function query() {
        return "
            SELECT 
                cliente_endereco.id_endereco,
                cliente_endereco.id_cliente,
                cliente_endereco.logradouro,
                cliente_endereco.complemento,
                cliente_endereco.numero,
                cliente_endereco.bairro,
                cliente_endereco.cidade,
                cliente_endereco.uf,
                cliente_endereco.latitude,
                cliente_endereco.longitude
            FROM cliente_endereco
        ";
    }

    /**
     * @throws Exception
     * @param int $id_cliente
     * @return ClienteEnderecoInfo[]
     */
    public function listar($id_cliente) {
        $query = $this->query() . " 
            WHERE cliente_endereco.id_cliente = :id_cliente
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_cliente", $id_cliente, PDO::PARAM_INT);
        $db->execute();
        return DB::getResult($db, "Emagine\\CRM\\Model\\ClienteEnderecoInfo");
    }

    /**
     * @param PDOStatement $db
     * @param ClienteEnderecoInfo $endereco
     */
    private function preencherCampo(PDOStatement $db, ClienteEnderecoInfo $endereco) {
        $db->bindValue(":logradouro", $endereco->getLogradouro());
        $db->bindValue(":complemento", $endereco->getComplemento());
        $db->bindValue(":numero", $endereco->getNumero());
        $db->bindValue(":bairro", $endereco->getBairro());
        $db->bindValue(":cidade", $endereco->getCidade());
        $db->bindValue(":uf", $endereco->getUf());
        $db->bindValue(":latitude", $endereco->getLatitude());
        $db->bindValue(":longitude", $endereco->getLongitude());
    }

    /**
     * @throws Exception
     * @param ClienteEnderecoInfo $endereco
     * @return int
     */
    public function inserir($endereco) {
        $query = "
            INSERT INTO cliente_endereco (
                id_cliente,
                logradouro,
                complemento,
                numero,
                bairro,
                cidade,
                uf,
                latitude,
                longitude
            ) VALUES (
                :id_cliente,
                :logradouro,
                :complemento,
                :numero,
                :bairro,
                :cidade,
                :uf,
                :latitude,
                :longitude
            )
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_cliente", $endereco->getIdCliente(), PDO::PARAM_INT);
        $this->preencherCampo($db, $endereco);
        $db->execute();
        return DB::lastInsertId();
    }

    /**
     * @throws Exception
     * @param ClienteEnderecoInfo $endereco
     */
    public function alterar($endereco) {
        $query = "
            UPDATE cliente_endereco SET
                logradouro = :logradouro,
                complemento = :complemento,
                numero = :numero,
                bairro = :bairro,
                cidade = :cidade,
                uf = :uf,
                latitude = :latitude,
                longitude = :longitude
            WHERE id_endereco = :id_endereco
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_endereco", $endereco->getId(), PDO::PARAM_INT);
        $this->preencherCampo($db, $endereco);
        $db->execute();
    }

    /**
     * @throws Exception
     * @param int $id_cliente
     */
    public function limpar($id_cliente) {
        $query = "
            DELETE FROM cliente_endereco
            WHERE id_cliente = :id_cliente 
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_cliente", $id_cliente, PDO::PARAM_INT);
        $db->execute();
    }

    /**
     * @throws Exception
     * @param int $id_endereco
     */
    public function excluir($id_endereco) {
        $query = "
            DELETE FROM cliente_endereco
            WHERE id_endereco = :id_endereco
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_endereco", $id_endereco, PDO::PARAM_INT);
        $db->execute();
    }
}
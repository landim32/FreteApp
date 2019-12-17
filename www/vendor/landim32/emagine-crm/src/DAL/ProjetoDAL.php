<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 01/07/2017
 * Time: 09:00
 */

namespace Emagine\CRM\DAL;

use Landim32\EasyDB\DB;
use Exception;

class ProjetoDAL
{
    /**
     * @throws Exception
     * @param string $comecaCom
     * @return string
     */
    public function pegarProximaUrl($comecaCom) {
        $query = "
            SELECT 
                url
            FROM projeto
            WHERE url LIKE :url
            ORDER BY data_inclusao
            LIMIT 1
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":url", $comecaCom . "%" );
        $db->execute();
        return DB::getValue($db, "url");
    }

    /**
     * @throws Exception
     * @param string $url
     * @return int
     */
    public function pegarPorUrl($url) {
        $query = "
            SELECT projeto.id_projeto
            FROM projeto
            WHERE projeto.url = :url
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":url", $url );
        $db->execute();
        return DB::getValue($db, "id_projeto");
    }

    /**
     * @throws Exception
     * @param string $url
     * @return int
     */
    public function inserirUrl($url) {
        $query = "
            INSERT INTO projeto (
                data_inclusao,
                url
            ) VALUES (
                NOW(),
                :url
            )
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":url", $url );
        $db->execute();
        return DB::lastInsertId();
    }

    /**
     * @throws Exception
     * @param string $url
     */
    public function excluirUrl($url) {
        $query = "
            DELETE FROM projeto
            WHERE projeto.url = :url
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":url", $url );
        $db->execute();
    }
}
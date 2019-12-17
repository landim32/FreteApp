<?php
namespace Emagine\Frete\MaisCargas\DAL;

use PDO;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Frete\MaisCargas\IDAL\IFreteFaturaDAL;
use Emagine\Frete\MaisCargas\Model\FreteFaturaInfo;

class FreteFaturaDAL implements IFreteFaturaDAL
{
    /**
     * @return string
     */
    private function query() {
        return "
			SELECT 
                driverbill.Id AS 'id_fatura',
                driverbill.DriverId AS 'id_usuario',
                driverbill.FreightId AS 'id_frete',
                driverbill.Value AS 'preco',
                driverbill.DateGenerator AS 'data_inclusao',
                driverbill.DateGenerator AS 'ultima_alteracao',
                driverbill.DateGenerator AS 'data_vencimento',
                driverbill.DatePayment AS 'data_pagamento',
                driverbill.DateConfirmPayment AS 'data_confirmacao',
                driverbill.FormPayment AS 'forma_pagamento',
                driverbill.Observation AS 'observacao',
                tickets.Bar AS 'codigo_barra',
                tickets.Url AS 'url',
                1 AS cod_situacao
			FROM driverbill
			INNER JOIN tickets ON tickets.DriverBillId = driverbill.Id
		";
    }

    /**
     * @throws Exception
     * @param int $id_usuario
     * @return FreteFaturaInfo[]
     */
    public function listar($id_usuario) {
        $query = $this->query() . "
			WHERE driverbill.Id = :id_usuario
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
        return DB::getResult($db,"Emagine\\Frete\\MaisCargas\\Model\\FreteFaturaInfo");
    }
}
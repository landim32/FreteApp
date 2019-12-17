<?php
namespace Emagine\Frete\MaisCargas\DAL;

use PDO;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Frete\IDAL\IFreteTipoVeiculoDAL;
use Emagine\Frete\Model\TipoVeiculoInfo;

class FreteTipoVeiculoDAL extends TipoVeiculoDAL implements IFreteTipoVeiculoDAL {

    /**
     * @throws Exception
     * @param int $id_frete
     * @return TipoVeiculoInfo[]
     */
    public function listarPorFrete($id_frete) {
        $query = $this->query() . "
            INNER JOIN freightvehicles ON freightvehicles.VehiclesId = vehicles.Id
            WHERE freightvehicles.FreightId = :id_frete
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_frete", $id_frete, PDO::PARAM_INT);
        return DB::getResult($db,"Emagine\\Frete\\Model\\TipoVeiculoInfo");
    }

    /**
     * @param int $id_frete
     * @param int $id_tipo
     * @throws Exception
     */
    public function inserir($id_frete, $id_tipo) {
        $query = "
			INSERT INTO freightvehicles (
				FreightId,
				VehiclesId
			) VALUES (
				:id_frete,
				:id_tipo
			)
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_frete", $id_frete, PDO::PARAM_INT);
        $db->bindValue(":id_tipo", $id_tipo, PDO::PARAM_INT);
        $db->execute();
    }

    /**
     * @throws Exception
     * @param int $id_frete
     */
    public function limpar($id_frete) {
        $query = "
			DELETE FROM freightvehicles
			WHERE FreightId = :id_frete
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_frete", $id_frete, PDO::PARAM_INT);
        $db->execute();
    }

}


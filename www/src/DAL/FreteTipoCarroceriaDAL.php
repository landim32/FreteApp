<?php
namespace Emagine\Frete\DAL;

use PDO;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Frete\IDAL\IFreteTipoCarroceriaDAL;
use Emagine\Frete\Model\TipoCarroceriaInfo;

class FreteTipoCarroceriaDAL extends TipoCarroceriaDAL implements IFreteTipoCarroceriaDAL {

    /**
     * @throws Exception
     * @param int $id_frete
     * @return TipoCarroceriaInfo[]
     */
    public function listarPorFrete($id_frete) {
        $query = $this->query() . "
            INNER JOIN frete_carroceria ON frete_carroceria.id_carroceria = veiculo_carroceria.id_carroceria
            WHERE frete_carroceria.id_frete = :id_frete
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_frete", $id_frete, PDO::PARAM_INT);
        return DB::getResult($db,"Emagine\\Frete\\Model\\TipoCarroceriaInfo");
    }

    /**
     * @param int $id_frete
     * @param int $id_carroceria
     * @throws Exception
     */
    public function inserir($id_frete, $id_carroceria) {
        $query = "
			INSERT INTO frete_carroceria (
				id_frete,
				id_carroceria
			) VALUES (
				:id_frete,
				:id_carroceria
			)
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_frete", $id_frete, PDO::PARAM_INT);
        $db->bindValue(":id_carroceria", $id_carroceria, PDO::PARAM_INT);
        $db->execute();
    }

    /**
     * @throws Exception
     * @param int $id_frete
     */
    public function limpar($id_frete) {
        $query = "
			DELETE FROM frete_carroceria
			WHERE id_frete = :id_frete
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_frete", $id_frete, PDO::PARAM_INT);
        $db->execute();
    }

}


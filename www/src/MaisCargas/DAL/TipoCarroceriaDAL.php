<?php
namespace Emagine\Frete\MaisCargas\DAL;

use PDO;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Frete\IDAL\ITipoCarroceriaDAL;
use Emagine\Frete\Model\TipoCarroceriaInfo;

class TipoCarroceriaDAL implements ITipoCarroceriaDAL {

	/**
	 * @return string
	 */
	protected function query() {
		return "
			SELECT 
                bodyworks.Id AS 'id_carroceria',
                bodyworks.Type AS 'nome',
                null AS 'foto'
			FROM bodyworks
		";
	}

    /**
     * @param int $id_veiculo
     * @return string
     */
    private function pegarFotoUrl($id_veiculo) {
        return sprintf("Carroceria_%s.png", str_pad($id_veiculo, 2, "0", STR_PAD_LEFT));
    }

	/**
     * @throws Exception
	 * @return TipoCarroceriaInfo[]
	 */
	public function listar() {
		$query = $this->query();
		$db = DB::getDB()->prepare($query);
		$carrocerias = DB::getResult($db,"Emagine\\Frete\\Model\\TipoCarroceriaInfo");
        /** @var TipoCarroceriaInfo $carroceria */
        foreach ($carrocerias as $carroceria) {
            $carroceria->setFoto($this->pegarFotoUrl($carroceria->getId()));
        }
        return $carrocerias;
	}

	/**
     * @throws Exception
	 * @param int $id_carroceria
	 * @return TipoCarroceriaInfo
	 */
	public function pegar($id_carroceria) {
		$query = $this->query() . "
			WHERE bodyworks.Id = :id_carroceria
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_carroceria", $id_carroceria, PDO::PARAM_INT);
		$carroceria = DB::getValueClass($db,"Emagine\\Frete\\Model\\TipoCarroceriaInfo");
		if (!is_null($carroceria)) {
            $carroceria->setFoto($this->pegarFotoUrl($carroceria->getId()));
        }
		return $carroceria;
	}

}


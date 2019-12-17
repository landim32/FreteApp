<?php
namespace Emagine\Frete\MaisCargas\DAL;

use PDO;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Frete\IDAL\ITipoVeiculoDAL;
use Emagine\Frete\Model\TipoVeiculoInfo;

class TipoVeiculoDAL implements ITipoVeiculoDAL {

	/**
	 * @return string
	 */
	protected function query() {
		return "
			SELECT 
				vehicles.Id AS 'id_tipo',
                vehicles.Type AS 'nome',
                0 AS 'cod_tipo',
                null AS 'foto',
                vehicles.Tons AS 'capacidade'
			FROM vehicles
		";
	}

    /**
     * @param int $id_veiculo
     * @return string
     */
    private function pegarFotoUrl($id_veiculo) {
        return sprintf("Veiculo_%s.png", str_pad($id_veiculo, 2, "0", STR_PAD_LEFT));
    }

	/**
     * @throws Exception
	 * @return TipoVeiculoInfo[]
	 */
	public function listar() {
		$query = $this->query();
		$db = DB::getDB()->prepare($query);
        $tipos = DB::getResult($db,"Emagine\\Frete\\Model\\TipoVeiculoInfo");
        /** @var TipoVeiculoInfo $tipo */
        foreach ($tipos as $tipo) {
            $tipo->setFoto($this->pegarFotoUrl($tipo->getId()));
        }
        return $tipos;
	}

	/**
     * @throws Exception
	 * @param int $id_tipo
	 * @return TipoVeiculoInfo
	 */
	public function pegar($id_tipo) {
		$query = $this->query() . "
			WHERE vehicles.Id = :id_tipo
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_tipo", $id_tipo, PDO::PARAM_INT);
        $tipo = DB::getValueClass($db,"Emagine\\Frete\\Model\\TipoVeiculoInfo");
        if (!is_null($tipo)) {
            $tipo->setFoto($this->pegarFotoUrl($tipo->getId()));
        }
        return $tipo;
	}

}


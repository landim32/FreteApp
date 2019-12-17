<?php
namespace Emagine\Frete\DAL;

use PDO;
use PDOStatement;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Frete\IDAL\IMotoristaDAL;
use Emagine\Frete\Model\MotoristaEnvioInfo;
use Emagine\Frete\Model\MotoristaInfo;

/**
 * Class PedidoItemDAL
 * @package EmaginePedido\DAL
 * @tablename pedido_item
 * @author EmagineCRUD
 */
class MotoristaDAL implements IMotoristaDAL {

	/**
	 * @return string
	 */
	public function query() {
		return "
			SELECT 
				usuario_motorista.id_usuario,
				usuario_motorista.id_tipo,
				usuario_motorista.id_carroceria,				
				usuario_motorista.foto_carteira,
				usuario_motorista.foto_veiculo,
				usuario_motorista.foto_endereco,
				usuario_motorista.foto_cpf,
				usuario_motorista.cnh,
				usuario_motorista.placa,
				usuario_motorista.antt,
				usuario_motorista.veiculo,
				usuario_motorista.latitude,
				usuario_motorista.longitude,
				usuario_motorista.direcao,
				usuario_motorista.valor_hora,
				usuario_motorista.cod_situacao,
				usuario_motorista.cod_disponibilidade
			FROM usuario_motorista
		";
	}

	/**
     * @throws Exception
	 * @return MotoristaInfo[]
	 */
	public function listar() {
		$query = $this->query();
		$db = DB::getDB()->prepare($query);
		return DB::getResult($db,"Emagine\\Frete\\Model\\MotoristaInfo");
	}

	/**
     * @throws \Exception
	 * @param int $id_usuario
	 * @return MotoristaInfo
	 */
	public function pegar($id_usuario) {
		$query = $this->query() . "
			WHERE usuario_motorista.id_usuario = :id_usuario
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
		return DB::getValueClass($db,"Emagine\\Frete\\Model\\MotoristaInfo");
	}

	/**
	 * @param PDOStatement $db
	 * @param MotoristaInfo $motorista
	 */
	public function preencherCampo(PDOStatement $db, MotoristaInfo $motorista) {
		$db->bindValue(":id_usuario", $motorista->getId(), PDO::PARAM_INT);
        $db->bindValue(":foto_carteira", $motorista->getFotoCarteira());
        $db->bindValue(":foto_veiculo", $motorista->getFotoVeiculo());
        $db->bindValue(":foto_endereco", $motorista->getFotoEndereco());
        $db->bindValue(":foto_cpf", $motorista->getFotoCpf());
        $db->bindValue(":cnh", $motorista->getCNH());
        $db->bindValue(":placa", $motorista->getPlaca());
        $db->bindValue(":antt", $motorista->getAntt());
        $db->bindValue(":id_tipo", $motorista->getIdTipo(), PDO::PARAM_INT);
        $db->bindValue(":id_carroceria", $motorista->getIdCarroceria(), PDO::PARAM_INT);
        $db->bindValue(":veiculo", $motorista->getVeiculo());
        $db->bindValue(":latitude", $motorista->getLatitude());
        $db->bindValue(":longitude", $motorista->getLongitude());
        $db->bindValue(":direcao", $motorista->getDirecao(), PDO::PARAM_INT);
        if ($motorista->getValorHora() > 0) {
            $db->bindValue(":valor_hora", $motorista->getValorHora());
        }
        else {
            $db->bindValue(":valor_hora", null);
        }
        $db->bindValue(":cod_situacao", $motorista->getCodSituacao(), PDO::PARAM_INT);
        $db->bindValue(":cod_disponibilidade", $motorista->getCodDisponibilidade(), PDO::PARAM_INT);
	}

	/**
     * @throws Exception
	 * @param MotoristaInfo $motorista
     * @return int
	 */
	public function inserir($motorista) {
		$query = "
			INSERT INTO usuario_motorista (
				id_usuario,
				data_inclusao,
				ultima_alteracao,
				foto_carteira,
				foto_veiculo,
				foto_endereco,
				foto_cpf,
				cnh,
				placa,
				antt,
				id_tipo,
				id_carroceria,
				veiculo,
				latitude,
				longitude,
				direcao,
				valor_hora,
				cod_situacao,
				cod_disponibilidade
			) VALUES (
				:id_usuario,
				NOW(),
				NOW(),
				:foto_carteira,
				:foto_veiculo,
				:foto_endereco,
				:foto_cpf,
				:cnh,
				:placa,
				:antt,
				:id_tipo,
				:id_carroceria,
				:veiculo,
				:latitude,
				:longitude,
				:direcao,
				:valor_hora,
				:cod_situacao,
				:cod_disponibilidade
			)
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $motorista);
		$db->execute();
		return $motorista->getId();
	}

	/**
     * @throws \Exception
	 * @param MotoristaInfo $motorista
	 */
	public function alterar($motorista) {
		$query = "
			UPDATE usuario_motorista SET 
			    ultima_alteracao = NOW(),
				foto_carteira = :foto_carteira,
				foto_veiculo = :foto_veiculo,
				foto_endereco = :foto_endereco,
				foto_cpf = :foto_cpf,
				cnh = :cnh,
				placa = :placa,
				antt = :antt,
				id_tipo = :id_tipo,
				id_carroceria = :id_carroceria,
				veiculo = :veiculo,
				latitude = :latitude,
				longitude = :longitude,
				direcao = :direcao,
				valor_hora = :valor_hora,
				cod_situacao = :cod_situacao,
				cod_disponibilidade = :cod_disponibilidade
			WHERE usuario_motorista.id_usuario = :id_usuario
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $motorista);
        $db->bindValue(":id_usuario", $motorista->getId());
		$db->execute();
	}

    /**
     * @param MotoristaEnvioInfo $motorista
     * @throws \Landim32\EasyDB\DBException
     */
    public function atualizar($motorista) {
        $query = "
			UPDATE usuario_motorista SET 
			    ultima_alteracao = NOW(),
				latitude = :latitude,
				longitude = :longitude,
				cod_disponibilidade = :cod_disponibilidade
			WHERE usuario_motorista.id_usuario = :id_usuario
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":latitude", $motorista->getLatitude());
        $db->bindValue(":longitude", $motorista->getLongitude());
        $db->bindValue(":cod_disponibilidade", $motorista->getCodDisponibilidade(), PDO::PARAM_INT);
        $db->bindValue(":id_usuario", $motorista->getIdMotorista());
        $db->execute();
    }

	/**
     * @throws \Exception
	 * @param int $id_usuario
	 */
	public function excluir($id_usuario) {
		$query = "
			DELETE FROM usuario_motorista
			WHERE id_usuario = :id_usuario
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
		$db->execute();
	}
}


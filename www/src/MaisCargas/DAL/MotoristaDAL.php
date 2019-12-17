<?php
namespace Emagine\Frete\MaisCargas\DAL;

use PDO;
use PDOStatement;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Frete\IDAL\IMotoristaDAL;
use Emagine\Login\Model\UsuarioInfo;
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
    private function queryUsuario() {
        return "
            SELECT 
				drivers.Id AS 'id_usuario',
				null AS 'foto',
				NOW() AS 'data_inclusao',
				NOW() AS 'ultima_alteracao',
				NOW() AS 'ultimo_login',
				drivers.Email AS 'email',
				null AS 'slug',
				drivers.Name AS 'nome',
				drivers.Password AS 'senha',
				drivers.Phone AS 'telefone',
				drivers.CPF AS 'cpf_cnpj',
				:cod_situacao AS 'cod_situacao'
		    FROM drivers
        ";
    }

	/**
	 * @return string
	 */
	public function query() {
		return "
			SELECT 
				drivers.Id AS 'id_usuario',
				drivers.Vehicle AS 'id_tipo',
				drivers.Bodywork AS 'id_carroceria',				
				null AS 'foto_carteira',
				null AS 'foto_veiculo',
				null AS 'foto_endereco',
				null AS 'foto_cpf',
				drivers.Board AS 'placa',
				drivers.ANTT AS 'antt',
				null AS 'veiculo',
				(
				    SELECT driverposition.Lat
				    FROM driverposition
				    WHERE driverposition.DriverId = drivers.Id
				    ORDER BY driverposition.Date DESC
				    LIMIT 1
				) AS 'latitude',
				(
				    SELECT driverposition.Lon
				    FROM driverposition
				    WHERE driverposition.DriverId = drivers.Id
				    ORDER BY driverposition.Date DESC
				    LIMIT 1
				) AS 'longitude',
				0 AS 'direcao',
				:cod_situacao AS 'cod_situacao',
				:cod_disponibilidade AS 'cod_disponibilidade' 
			FROM drivers
		";
	}

    /**
     * @param int $id_usuario
     * @return UsuarioInfo
     * @throws Exception
     */
	private function pegarUsuario($id_usuario) {
        $query = $this->queryUsuario() . "
			WHERE drivers.Id = :id_usuario
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
        $db->bindValue(":cod_situacao", MotoristaInfo::ATIVO, PDO::PARAM_INT);
        return DB::getValueClass($db,"Emagine\\Login\\Model\\UsuarioInfo");
    }

	/**
     * @throws Exception
	 * @return MotoristaInfo[]
	 */
	public function listar() {
		$query = $this->query();
		$db = DB::getDB()->prepare($query);
        $db->bindValue(":cod_situacao", MotoristaInfo::ATIVO, PDO::PARAM_INT);
        $db->bindValue(":cod_disponibilidade", MotoristaInfo::DISPONIVEL, PDO::PARAM_INT);
		$motoristas = DB::getResult($db,"Emagine\\Frete\\Model\\MotoristaInfo");
		/** @var MotoristaInfo $motorista */
        foreach ($motoristas as $motorista) {
		    $motorista->setUsuario($this->pegarUsuario($motorista->getId()));
        }
        return $motoristas;
	}

	/**
     * @throws Exception
	 * @param int $id_usuario
	 * @return MotoristaInfo
	 */
	public function pegar($id_usuario) {
		$query = $this->query() . "
			WHERE drivers.Id = :id_usuario
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
        $db->bindValue(":cod_situacao", MotoristaInfo::ATIVO, PDO::PARAM_INT);
        $db->bindValue(":cod_disponibilidade", MotoristaInfo::DISPONIVEL, PDO::PARAM_INT);
        /** @var MotoristaInfo $motorista */
		$motorista = DB::getValueClass($db,"Emagine\\Frete\\Model\\MotoristaInfo");
        $motorista->setUsuario($this->pegarUsuario($id_usuario));
		return $motorista;
	}

    /**
     * @param string $email
     * @param string $senha
     * @return MotoristaInfo
     * @throws Exception
     */
    public function pegarPorLogin($email, $senha) {
        $query = $this->query() . "
			WHERE (
			    drivers.Email IS NULL OR
			    drivers.Email = :email
			) 
			AND drivers.Password = PASSWORD(:senha)
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":cod_situacao", MotoristaInfo::ATIVO, PDO::PARAM_INT);
        $db->bindValue(":cod_disponibilidade", MotoristaInfo::DISPONIVEL, PDO::PARAM_INT);
        $db->bindValue(":email", $email);
        $db->bindValue(":senha", $senha);
        /** @var MotoristaInfo $motorista */
        $motorista = DB::getValueClass($db,"Emagine\\Frete\\Model\\MotoristaInfo");
        if (!is_null($motorista)) {
            $motorista->setUsuario($this->pegarUsuario($motorista->getId()));
        }
        return $motorista;
    }

	/**
     * @throws Exception
	 * @param PDOStatement $db
	 * @param MotoristaInfo $motorista
	 */
	private function preencherCampo(PDOStatement $db, MotoristaInfo $motorista) {
		$db->bindValue(":Name", $motorista->getUsuario()->getNome());
        $db->bindValue(":Phone", $motorista->getUsuario()->getTelefone());
        $db->bindValue(":Email", $motorista->getUsuario()->getEmail());
        $db->bindValue(":CNH", $motorista->getCNH());
        $db->bindValue(":CPF", $motorista->getUsuario()->getCpfCnpj());
        $db->bindValue(":Vehicle", $motorista->getIdTipo(), PDO::PARAM_INT);
        $db->bindValue(":Bodywork", $motorista->getIdCarroceria(), PDO::PARAM_INT);
        $db->bindValue(":Board", $motorista->getPlaca());
        $db->bindValue(":ANTT", $motorista->getAntt());
	}

	/**
     * @throws Exception
	 * @param MotoristaInfo $motorista
     * @return int
	 */
	public function inserir($motorista) {
		$query = "
			INSERT INTO drivers (
                Name,
                Phone,
                Email,  
                Password,
                CNH,
                CPF,
                Status,
                Vehicle,
                Bodywork,
                Board,
                ANTT
			) VALUES (
                :Name,
                :Phone,
                :Email,  
                PASSWORD(:Password),
                :CNH,
                :CPF,
                1,
                :Vehicle,
                :Bodywork,
                :Board,
                :ANTT
			)
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $motorista);
        $db->bindValue(":Password", $motorista->getUsuario()->getSenha());
		$db->execute();
		return DB::lastInsertId();
	}

	/**
     * @throws Exception
	 * @param MotoristaInfo $motorista
	 */
	public function alterar($motorista) {
		$query = "
			UPDATE drivers SET 
                Name = :Name,
                Phone = :Phone,
                Email = :Email,  
                CNH = :CNH,
                CPF = :CPF,
                Vehicle = :Vehicle,
                Bodywork = :Bodywork,
                Board = :Board,
                ANTT = :ANTT
			WHERE Id = :id_usuario
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $motorista);
		$db->execute();
	}

    /**
     * @param MotoristaEnvioInfo $motorista
     * @throws \Landim32\EasyDB\DBException
     */
    public function atualizar($motorista) {
        $query = "
			INSERT INTO driverposition (
			    DriverId,
			    FreightId,
			    Lat,
			    Lon,
			    Date
			)
			VALUES ( 
			    :DriverId,
			    :FreightId,
			    :Lat,
			    :Lon,
			    NOW()
			)
			WHERE usuario_motorista.id_usuario = :id_usuario
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":Lat", $motorista->getLatitude());
        $db->bindValue(":Lon", $motorista->getLongitude());
        $db->bindValue(":DriverId", $motorista->getIdMotorista());
        $db->execute();
    }

	/**
     * @throws Exception
	 * @param int $id_usuario
	 */
	public function excluir($id_usuario) {
		$query = "
			DELETE FROM drivers
			WHERE Id = :id_usuario
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
		$db->execute();
	}
}


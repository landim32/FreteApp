<?php

namespace Emagine\Frete\MaisCargas\DAL;

use PDO;
use PDOStatement;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Frete\IDAL\IFreteDAL;
use Emagine\Frete\Model\FreteInfo;

/**
 * Class FreteDAL
 * @package Emagine\Frete\DAL
 * @tablename entrega
 * @author EmagineCRUD
 */
class FreteDAL implements IFreteDAL {

	/**
	 * @return string
	 */
	public function query() {
		return "
			SELECT 
				freight.Id AS 'id_frete',
				freight.EnterprisesId AS 'id_usuario',
				freightdriver.DriverId AS 'id_motorista',
				null AS 'id_pagamento',
				NOW() AS 'data_inclusao',
				NOW() AS 'ultima_alteracao',
				freight.WithdrawalTime AS 'data_retirada',
				freight.DeliveryTime AS 'data_entrega',
				null AS 'foto',
				null AS 'preco',
				freight.Weight AS 'peso',
				freight.Width AS 'largura',
				freight.Height AS 'altura',
				freight.Length AS 'profundidade',
				null AS 'distancia',
				null AS 'distancia_str',
				null AS 'tempo',
				null AS 'tempo_str',
				0 AS 'rota_encontrada',
				freight.Freight AS 'observacao',
				(
				    SELECT 
				        CONCAT(IFNULL(freightwithdrawal.City, ''), ', ', IFNULL(freightwithdrawal.UF, ''))
				    FROM freightwithdrawal
				    WHERE freightwithdrawal.FreightId = freight.Id
				    ORDER BY freightwithdrawal.Id
				    LIMIT 1
				) AS 'endereco_origem',
				(
				    SELECT 
				        CONCAT(IFNULL(freightdelivery.City, ''), ', ', IFNULL(freightdelivery.UF, ''))
				    FROM freightdelivery
				    WHERE freightdelivery.FreightId = freight.Id
				    ORDER BY freightdelivery.Id DESC
				    LIMIT 1
				) AS 'endereco_destino',
				0 AS 'nota_frete',
				0 AS 'nota_motorista',
				1 AS 'cod_situacao',
				null AS 'polyline'
			FROM freight
			LEFT JOIN freightdriver ON (
			    freightdriver.FreightId = freight.Id AND
			    freightdriver.Status = 1 
		    )
		";
	}

    /**
     * @throws Exception
     * @param int $id_usuario
     * @param int $id_motorista
     * @param int $cod_situacao
     * @return FreteInfo[]
     */
    public function listar($id_usuario = 0, $id_motorista = 0, $cod_situacao = 0) {
        $query = $this->query();
        if ($id_usuario > 0 && $id_motorista > 0) {
            $query .= " 
                WHERE (
			        freight.EnterprisesId = :id_usuario OR
			        freightdriver.DriverId = :id_motorista
			    )
		    ";
        }
        elseif ($id_usuario > 0) {
            $query .= " WHERE freight.EnterprisesId = :id_usuario ";
        }
        elseif ($id_motorista > 0) {
            $query .= " WHERE freightdriver.DriverId = :id_motorista ";
        }
        else {
            $query .= " WHERE 1=1 ";
        }
        /*
        if ($cod_situacao > 0) {
            $query .= " AND frete.cod_situacao = :cod_situacao ";
        }
        */
        $db = DB::getDB()->prepare($query);
        if ($id_usuario > 0) {
            $db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
        }
        if ($id_motorista > 0) {
            $db->bindValue(":id_motorista", $id_motorista, PDO::PARAM_INT);
        }
        /*
        if ($cod_situacao > 0) {
            $db->bindValue(":cod_situacao", $cod_situacao, PDO::PARAM_INT);
        }
        */
        return DB::getResult($db,"Emagine\\Frete\\Model\\FreteInfo");
    }

    /**
     * @param int $id_usuario
     * @return FreteInfo[]
     * @throws Exception
     */
    public function listarDisponivel($id_usuario = 0) {
        $query = $this->query() . "
            WHERE freightdriver.DriverId IS NULL
            AND frete.cod_situacao = :cod_situacao
        ";
        if ($id_usuario > 0) {
            $query .= "
                AND frete.id_frete NOT IN (
                    SELECT frete_motorista.id_frete
                    FROM frete_motorista
                    WHERE frete_motorista.id_usuario = :id_usuario
                )
            ";
        }
        $db = DB::getDB()->prepare($query);
        if ($id_usuario > 0) {
            $db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
        }
        //$db->bindValue(":cod_situacao", FreteInfo::PROCURANDO_MOTORISTA, PDO::PARAM_INT);
        return DB::getResult($db,"Emagine\\Frete\\Model\\FreteInfo");
    }

	/**
     * @throws Exception
	 * @param int $id_frete
	 * @return FreteInfo
	 */
	public function pegar($id_frete) {
		$query = $this->query() . "
			WHERE freight.Id = :id_frete
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_frete", $id_frete, PDO::PARAM_INT);
		return DB::getValueClass($db,"Emagine\\Frete\\Model\\FreteInfo");
	}

    /**
     * @throws Exception
     * @param int $id_motorista
     * @return FreteInfo
     */
    public function pegarAbertoPorMotorista($id_motorista) {
        $situacoes = array(
            FreteInfo::PEGANDO_ENCOMENDA,
            FreteInfo::ENTREGANDO,
            FreteInfo::ENTREGUE
        );
        $query = $this->query() . "
			WHERE frete.id_motorista = :id_motorista
			AND frete.cod_situacao IN (" . implode(", ", $situacoes) . ")
			LIMIT 1
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_motorista", $id_motorista, PDO::PARAM_INT);
        return DB::getValueClass($db,"Emagine\\Frete\\Model\\FreteInfo");
    }

	/**
	 * @param PDOStatement $db
	 * @param FreteInfo $frete
	 */
	public function preencherCampo(PDOStatement $db, FreteInfo $frete) {
		$db->bindValue(":EnterprisesId", $frete->getIdUsuario(), PDO::PARAM_INT);
        $db->bindValue(":Freight", $frete->getObservacao());
        $db->bindValue(":Weight", $frete->getPeso());
        if ($frete->getLargura() > 0 || $frete->getAltura() > 0 || $frete->getProfundidade() > 0) {
            $db->bindValue(":Block", 1);
        }
        else {
            $db->bindValue(":Block", 0);
        }
        $db->bindValue(":Height", $frete->getAltura());
        $db->bindValue(":Length", $frete->getProfundidade());
        $db->bindValue(":Width", $frete->getLargura());

        $db->bindValue(":WithdrawalTime", $frete->getDataRetirada());
        $db->bindValue(":DeliveryTime", $frete->getDataEntrega());
	}

    /**
     * @param FreteInfo $frete
     * @return bool
     * @throws Exception
     */
	private function existeMotorista(FreteInfo $frete) {
        $query = "
	        SELECT freightdriver.Id
	        FROM freightdriver
	        WHERE freightdriver.FreightId = :FreightId
	        AND freightdriver.DriverId = :DriverId
	        AND freightdriver.Status = 1
	    ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":FreightId", $frete->getId(), PDO::PARAM_INT);
        $db->bindValue(":DriverId", $frete->getIdMotorista(), PDO::PARAM_INT);
        return intval(DB::getValue($db, "Id")) > 0;
    }

    /**
     * @param FreteInfo $frete
     * @throws Exception
     */
	private function inserirMotorista(FreteInfo $frete) {
        $query = "
			INSERT INTO freightdriver (
                FreightId,
                DriverId,
                Status
			) VALUES (
                :FreightId,
                :DriverId,
                1
			)
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":FreightId", $frete->getId(), PDO::PARAM_INT);
        $db->bindValue(":DriverId", $frete->getIdMotorista(), PDO::PARAM_INT);
        $db->execute();
    }

    /**
     * @param FreteInfo $frete
     * @throws Exception
     */
    private function limparMotorista(FreteInfo $frete) {
        $query = "
            UPDATE freightdriver SET
                Status = 0
            WHERE FreightId = :FreightId
            AND DriverId = :DriverId
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":FreightId", $frete->getId(), PDO::PARAM_INT);
        $db->bindValue(":DriverId", $frete->getIdMotorista(), PDO::PARAM_INT);
        $db->execute();
    }

	/**
     * @throws Exception
	 * @param FreteInfo $frete
	 * @return int
	 */
	public function inserir($frete) {
		$query = "
			INSERT INTO freight (
                EnterprisesId,
                Freight,
                Weight,
                Block,  
                Height,
                Length,
                Width,
                WithdrawalTime,
                DeliveryTime
			) VALUES (
                :EnterprisesId,
                :Freight,
                :Weight,
                :Block,  
                :Height,
                :Length,
                :Width,
                :WithdrawalTime,
                :DeliveryTime
			)
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $frete);
		$db->execute();
		$frete->setId(DB::lastInsertId());
		if ($frete->getIdMotorista() > 0) {
            $this->inserirMotorista($frete);
        }
        return $frete->getId();
	}

	/**
     * @throws Exception
	 * @param FreteInfo $frete
	 */
	public function alterar($frete) {
		$query = "
			UPDATE freight SET 
                EnterprisesId = :EnterprisesId,
                Freight = :Freight,
                Weight = :Weight,
                Block = :Block,  
                Height = :Height,
                Length = :Length,
                Width = :Width,
                WithdrawalTime = :WithdrawalTime,
                DeliveryTime = :DeliveryTime
			WHERE freight.Id = :Id
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $frete);
		$db->bindValue(":Id", $frete->getId(), PDO::PARAM_INT);
        if ($frete->getIdMotorista() > 0) {
            if ($this->existeMotorista($frete)) {
                $this->inserirMotorista($frete);
            }
        }
        else {
            $this->limparMotorista($frete);
        }
		$db->execute();
	}

	/**
     * @throws Exception
	 * @param int $id_frete
	 */
	public function excluir($id_frete) {
		$query = "
			DELETE FROM frete
			WHERE id_frete = :id_frete
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_frete", $id_frete, PDO::PARAM_INT);
		$db->execute();
	}
	/**
     * @throws Exception
	 * @param int $id_usuario
	 */
	public function limparPorUsuario($id_usuario) {
		$query = "
			DELETE FROM frete
			WHERE id_usuario = :id_usuario
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
		$db->execute();
	}

	/**
     * @throws Exception
	 * @param int $id_motorista
	 */
	public function limparPorMotorista($id_motorista) {
		$query = "
			DELETE FROM frete
			WHERE id_motorista = :id_motorista
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_motorista", $id_motorista, PDO::PARAM_INT);
		$db->execute();
	}

    /**
     * @throws Exception
     * @param int $id_frete
     * @param int $id_usuario
     * @return int
     */
	public function pegarQuantidadeAceite($id_frete, $id_usuario) {
        $query = "
			SELECT COUNT(*) AS 'quantidade'
			FROM frete_motorista
			WHERE id_frete = :id_frete
			AND id_usuario = :id_usuario
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_frete", $id_frete, PDO::PARAM_INT);
        $db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
        $db->execute();
        return DB::getValue($db, "quantidade");
    }

    /**
     * @param int $id_frete
     * @param int $id_usuario
     * @param bool $aceite
     * @throws Exception
     */
    public function alterarAceite($id_frete, $id_usuario, $aceite) {
        $query = "
			UPDATE frete_motorista SET
			    aceite = :aceite
			WHERE id_frete = :id_frete
			AND id_usuario = :id_usuario
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":aceite", $aceite, PDO::PARAM_BOOL);
        $db->bindValue(":id_frete", $id_frete, PDO::PARAM_INT);
        $db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
        $db->execute();
    }

    /**
     * @param int $id_frete
     * @param int $id_usuario
     * @param bool $aceite
     * @throws Exception
     */
	public function inserirAceite($id_frete, $id_usuario, $aceite) {
        $query = "
		    INSERT INTO frete_motorista (
		        id_frete,
		        id_usuario,
		        aceite
		    ) VALUES (
		        :id_frete,
		        :id_usuario,
		        :aceite
		    )
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":aceite", $aceite, PDO::PARAM_BOOL);
        $db->bindValue(":id_frete", $id_frete, PDO::PARAM_INT);
        $db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
        $db->execute();
    }

    /**
     * @param int $id_motorista
     * @return float
     * @throws Exception
     */
    public function pegarNotaMotorista($id_motorista) {
        $query = "
            SELECT AVG(nota_motorista) as 'nota'
            FROM frete
			WHERE id_motorista = :id_motorista
			AND nota_motorista > 0
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_motorista", $id_motorista, PDO::PARAM_INT);
        $db->execute();
        return floatval(DB::getValue($db,"nota"));
    }

    /**
     * @param int $id_motorista
     * @return float
     * @throws Exception
     */
    public function pegarNotaCliente($id_motorista) {
        $query = "
            SELECT AVG(nota_frete) as 'nota'
            FROM frete
			WHERE id_motorista = :id_motorista
			AND nota_frete > 0
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_motorista", $id_motorista, PDO::PARAM_INT);
        $db->execute();
        return floatval(DB::getValue($db,"nota"));
    }

}


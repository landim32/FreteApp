<?php

namespace Emagine\Frete\DAL;

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
				frete.id_frete,
				frete.id_usuario,
				frete.id_motorista,
				frete.id_pagamento,
				frete.data_inclusao,
				frete.ultima_alteracao,
				frete.data_retirada,
				frete.data_entrega,
				frete.data_retirada_executada,
				frete.data_entrega_executada,
				frete.foto,
				frete.preco,
				frete.peso,
				frete.largura,
				frete.altura,
				frete.profundidade,
				frete.distancia,
				frete.distancia_str,
				frete.tempo,
				frete.tempo_str,
				frete.rota_encontrada,
				frete.observacao,
				frete.endereco_saida as 'endereco_origem',
				frete.endereco_chegada as 'endereco_destino',
				frete.nota_frete,
				frete.nota_motorista,
				frete.cod_situacao,
				frete.polyline,
				TIME_TO_SEC(TIMEDIFF(
				  IFNULL(frete.data_entrega_executada, NOW()), 
				  IFNULL(frete.data_retirada_executada, NOW())
				)) AS 'duracao'
			FROM frete
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
			        frete.id_usuario = :id_usuario OR
			        frete.id_motorista = :id_motorista
			    )
		    ";
        }
        elseif ($id_usuario > 0) {
            $query .= " WHERE frete.id_usuario = :id_usuario ";
        }
        elseif ($id_motorista > 0) {
            $query .= " WHERE frete.id_motorista = :id_motorista ";
        }
        else {
            $query .= " WHERE 1=1 ";
        }
        if ($cod_situacao > 0) {
            $query .= " AND frete.cod_situacao = :cod_situacao ";
        }
        $db = DB::getDB()->prepare($query);
        if ($id_usuario > 0) {
            $db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
        }
        if ($id_motorista > 0) {
            $db->bindValue(":id_motorista", $id_motorista, PDO::PARAM_INT);
        }
        if ($cod_situacao > 0) {
            $db->bindValue(":cod_situacao", $cod_situacao, PDO::PARAM_INT);
        }
        return DB::getResult($db,"Emagine\\Frete\\Model\\FreteInfo");
    }

    /**
     * @param int $id_usuario
     * @return FreteInfo[]
     * @throws Exception
     */
    public function listarDisponivel($id_usuario = 0) {
        $query = $this->query() . "
            WHERE frete.id_motorista IS NULL
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
        $db->bindValue(":cod_situacao", FreteInfo::PROCURANDO_MOTORISTA, PDO::PARAM_INT);
        return DB::getResult($db,"Emagine\\Frete\\Model\\FreteInfo");
    }

	/**
     * @throws Exception
	 * @param int $id_frete
	 * @return FreteInfo
	 */
	public function pegar($id_frete) {
		$query = $this->query() . "
			WHERE frete.id_frete = :id_frete
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_frete", $id_frete, PDO::PARAM_INT);
		return DB::getValueClass($db,"Emagine\\Frete\\Model\\FreteInfo");
	}

    /**
     * @throws Exception
     * @param int $id_motorista
     * @param int $id_frete
     * @return FreteInfo
     */
    public function pegarAbertoPorMotorista($id_motorista, $id_frete = 0) {
        $situacoes = array(
            FreteInfo::PEGANDO_ENCOMENDA,
            FreteInfo::ENTREGANDO,
            FreteInfo::ENTREGUE
        );
        $query = $this->query() . "
			WHERE frete.id_motorista = :id_motorista
			AND frete.cod_situacao IN (" . implode(", ", $situacoes) . ")
		";
        if ($id_frete > 0) {
            $query .= " AND frete.id_frete = :id_frete ";
        }
        $query .= " LIMIT 1";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_motorista", $id_motorista, PDO::PARAM_INT);
        if ($id_frete > 0) {
            $db->bindValue(":id_frete", $id_frete, PDO::PARAM_INT);
        }
        return DB::getValueClass($db,"Emagine\\Frete\\Model\\FreteInfo");
    }

	/**
	 * @param PDOStatement $db
	 * @param FreteInfo $frete
	 */
	public function preencherCampo(PDOStatement $db, FreteInfo $frete) {
		$db->bindValue(":id_usuario", $frete->getIdUsuario(), PDO::PARAM_INT);
		$db->bindValue(":id_motorista", $frete->getIdMotorista(), PDO::PARAM_INT);
		if ($frete->getIdPagamento() > 0) {
            $db->bindValue(":id_pagamento", $frete->getIdPagamento(), PDO::PARAM_INT);
        }
        else {
            $db->bindValue(":id_pagamento", null, PDO::PARAM_INT);
        }
        $db->bindValue(":data_retirada", $frete->getDataRetirada());
        $db->bindValue(":data_entrega", $frete->getDataEntrega());
		$db->bindValue(":foto", $frete->getFoto());
		$db->bindValue(":preco", $frete->getPreco());
		$db->bindValue(":peso", $frete->getPeso());
		$db->bindValue(":largura", $frete->getLargura());
		$db->bindValue(":altura", $frete->getAltura());
		$db->bindValue(":profundidade", $frete->getProfundidade());
		$db->bindValue(":distancia", $frete->getDistancia());
        $db->bindValue(":distancia_str", $frete->getDistanciaStr());
        $db->bindValue(":tempo", $frete->getTempo());
        $db->bindValue(":tempo_str", $frete->getTempoStr());
        $db->bindValue(":rota_encontrada", $frete->getRotaEncontrada(), PDO::PARAM_BOOL);
        $db->bindValue(":endereco_saida", $frete->getEnderecoOrigem());
        $db->bindValue(":endereco_chegada", $frete->getEnderecoDestino());
		$db->bindValue(":observacao", $frete->getObservacao());
        $db->bindValue(":nota_motorista", $frete->getNotaMotorista(), PDO::PARAM_INT);
        $db->bindValue(":nota_frete", $frete->getNotaFrete(), PDO::PARAM_INT);
		$db->bindValue(":cod_situacao", $frete->getCodSituacao(), PDO::PARAM_INT);
	}

	/**
     * @throws Exception
	 * @param FreteInfo $frete
	 * @return int
	 */
	public function inserir($frete) {
		$query = "
			INSERT INTO frete (
				id_usuario,
				id_motorista,
				id_pagamento,
				data_inclusao,
				ultima_alteracao,
				data_retirada,
				data_entrega,
				foto,
				preco,
				peso,
				largura,
				altura,
				profundidade,
				distancia,
				distancia_str,
				tempo,
				tempo_str,
				rota_encontrada,
				endereco_saida,
				endereco_chegada,
				observacao,
				nota_motorista,
				nota_frete,
				cod_situacao,
				polyline
			) VALUES (
				:id_usuario,
				:id_motorista,
				:id_pagamento,
				NOW(),
				NOW(),
				:data_retirada,
				:data_entrega,
				:foto,
				:preco,
				:peso,
				:largura,
				:altura,
				:profundidade,
				:distancia,
				:distancia_str,
				:tempo,
				:tempo_str,
				:rota_encontrada,
				:endereco_saida,
				:endereco_chegada,
				:observacao,
				:nota_motorista,
				:nota_frete,
				:cod_situacao,
				:polyline
			)
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $frete);
        $db->bindValue(":polyline", $frete->getPolyline());
		$db->execute();
		return DB::lastInsertId();
	}

	/**
     * @throws Exception
	 * @param FreteInfo $frete
	 */
	public function alterar($frete) {
		$query = "
			UPDATE frete SET 
				id_usuario = :id_usuario,
				id_motorista = :id_motorista,
				id_pagamento = :id_pagamento,
				ultima_alteracao = NOW(),
				data_retirada = :data_retirada,
				data_entrega = :data_entrega,
				foto = :foto,
				preco = :preco,
				peso = :peso,
				largura = :largura,
				altura = :altura,
				profundidade = :profundidade,
				distancia = :distancia,
				distancia_str = :distancia_str,
				tempo = :tempo,
				tempo_str = :tempo_str,
				rota_encontrada = :rota_encontrada,
				endereco_saida = :endereco_saida,
				endereco_chegada = :endereco_chegada,
				observacao = :observacao,
				nota_motorista = :nota_motorista,
				nota_frete = :nota_frete,
				cod_situacao = :cod_situacao
			WHERE frete.id_frete = :id_frete
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $frete);
		$db->bindValue(":id_frete", $frete->getId(), PDO::PARAM_INT);
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
     * @param int $id_frete
     * @throws Exception
     */
    public function limparAceite($id_frete) {
        $query = "
			DELETE FROM frete_motorista
			WHERE id_frete = :id_frete
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_frete", $id_frete, PDO::PARAM_INT);
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

    /**
     * @param int $id_frete
     * @throws Exception
     */
    public function atualizarDataRetirada($id_frete) {
        $query = "
            UPDATE frete SET
                data_retirada_executada = NOW()
            WHERE id_frete = :id_frete
            AND data_retirada_executada IS NULL
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_frete", $id_frete, PDO::PARAM_INT);
        $db->execute();
    }

    /**
     * @param int $id_frete
     * @throws Exception
     */
    public function atualizarDataEntrega($id_frete) {
        $query = "
            UPDATE frete SET
                data_entrega_executada = NOW()
            WHERE id_frete = :id_frete
            AND data_entrega_executada IS NULL
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_frete", $id_frete, PDO::PARAM_INT);
        $db->execute();
    }

}


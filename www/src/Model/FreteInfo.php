<?php

namespace Emagine\Frete\Model;

use stdClass;
use Exception;
use JsonSerializable;
use Emagine\Frete\BLLFactory\FreteBLLFactory;
use Emagine\Frete\BLLFactory\MotoristaBLLFactory;
use Emagine\Frete\DALFactory\FreteTipoCarroceriaDALFactory;
use Emagine\Frete\DALFactory\FreteTipoVeiculoDALFactory;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Frete\BLL\FreteLocalBLL;
use Emagine\Pagamento\BLL\PagamentoCieloBLL;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Pagamento\Model\PagamentoInfo;

/**
 * Class FreteInfo
 * @package Emagine\Frete\Model
 * @tablename entrega
 * @author EmagineCRUD
 */
class FreteInfo implements JsonSerializable {

	const AGUARDANDO_PAGAMENTO = 1;
	const PROCURANDO_MOTORISTA = 2;
    const APROVANDO_MOTORISTA = 9;
    const AGUARDANDO = 3;
	const PEGANDO_ENCOMENDA = 4;
	const ENTREGANDO = 5;
	const ENTREGUE = 6;
	const ENTREGA_CONFIRMADA = 7;
	const CANCELADO = 8;

	private $id_frete;
	private $id_usuario;
	private $id_motorista;
	private $id_pagamento;
	private $data_inclusao;
	private $ultima_alteracao;
	private $data_retirada;
	private $data_entrega;
	private $data_retirada_executada;
	private $data_entrega_executada;
	private $foto;
	private $preco;
	private $peso;
    private $profundidade;
	private $largura;
	private $altura;
	private $observacao;
	private $rota_encontrada;
	private $endereco_origem;
	private $endereco_destino;
    private $distancia;
    private $distancia_str;
    private $tempo;
    private $tempo_str;
    private $nota_frete;
    private $nota_motorista;
    private $cod_situacao;
	private $polyline;
	private $duracao = 0;
	private $foto_base64 = null;
	private $usuario = null;
	private $motorista = null;
	private $pagamento = null;
	private $locais = null;
	private $tiposVeiculo = null;
    private $carrocerias = null;

	/**
	 * Auto Increment Field
	 * @return int
	 */
	public function getId() {
		return $this->id_frete;
	}

	/**
	 * @param int $value
	 */
	public function setId($value) {
		$this->id_frete = $value;
	}

	/**
	 * @return int
	 */
	public function getIdUsuario() {
		return $this->id_usuario;
	}

	/**
	 * @param int $value
	 */
	public function setIdUsuario($value) {
		$this->id_usuario = $value;
	}

	/**
     * @throws Exception
	 * @return UsuarioInfo
	 */
	public function getUsuario() {
		if (is_null($this->usuario) && $this->getIdUsuario() > 0) {
			$bll = new UsuarioBLL();
			$this->usuario = $bll->pegar($this->getIdUsuario());
		}
		return $this->usuario;
	}

	/**
	 * @return int
	 */
	public function getIdMotorista() {
		return $this->id_motorista;
	}

	/**
	 * @param int $value
	 */
	public function setIdMotorista($value) {
		$this->id_motorista = $value;
	}

	/**
     * @throws \Exception
	 * @return MotoristaInfo
	 */
	public function getMotorista() {
		if (is_null($this->motorista) && $this->getIdMotorista() > 0) {
			$bll = MotoristaBLLFactory::create();
			$this->motorista = $bll->pegar($this->getIdMotorista());
		}
		return $this->motorista;
	}

    /**
     * @return int
     */
	public function getIdPagamento() {
	    return $this->id_pagamento;
    }

    /**
     * @param int $value
     */
    public function setIdPagamento($value) {
	    $this->id_pagamento = $value;
    }

    /**
     * @throws Exception
     * @return PagamentoInfo
     */
    public function getPagamento() {
        if (is_null($this->pagamento) && $this->getIdPagamento() > 0) {
            $bll = new PagamentoCieloBLL();
            $this->pagamento = $bll->pegar($this->getIdPagamento());
        }
        return $this->pagamento;
    }

	/**
	 * @return string
	 */
	public function getDataInclusao() {
		return $this->data_inclusao;
	}

	/**
	 * @param string $value
	 */
	public function setDataInclusao($value) {
		$this->data_inclusao = $value;
	}

	/**
	 * @return string
	 */
	public function getUltimaAlteracao() {
		return $this->ultima_alteracao;
	}

	/**
	 * @param string $value
	 */
	public function setUltimaAlteracao($value) {
		$this->ultima_alteracao = $value;
	}

    /**
     * @return string
     */
	public function getDataRetirada() {
	    return $this->data_retirada;
    }

    /**
     * @param string $value
     */
    public function setDataRetirada($value) {
	    $this->data_retirada = $value;
    }

    /**
     * @return string
     */
    public function getDataEntrega() {
        return $this->data_entrega;
    }

    /**
     * @param string $value
     */
    public function setDataEntrega($value) {
        $this->data_entrega = $value;
    }

    /**
     * @return string
     */
    public function getDataRetiradaExecutada() {
        return $this->data_retirada_executada;
    }

    /**
     * @param string $value
     */
    public function setDataRetiradaExecutada($value) {
        $this->data_retirada_executada = $value;
    }

    /**
     * @return string
     */
    public function getDataEntregaExecutada() {
        return $this->data_entrega_executada;
    }

    /**
     * @param string $value
     */
    public function setDataEntregaExecutada($value) {
        $this->data_entrega_executada = $value;
    }

	/**
	 * @return string
	 */
	public function getFoto() {
		return $this->foto;
	}

	/**
	 * @param string $value
	 */
	public function setFoto($value) {
		$this->foto = $value;
	}

    /**
     * @return string
     */
	public function getFotoBase64() {
	    return $this->foto_base64;
    }

    /**
     * @param string $value
     */
    public function setFotoBase64($value) {
	    $this->foto_base64 = $value;
    }

	/**
	 * @return double
	 */
	public function getPreco() {
		return $this->preco;
	}

	/**
	 * @param double $value
	 */
	public function setPreco($value) {
		$this->preco = $value;
	}

	/**
	 * @return double
	 */
	public function getPeso() {
		return $this->peso;
	}

	/**
	 * @param double $value
	 */
	public function setPeso($value) {
		$this->peso = $value;
	}

	/**
	 * @return double
	 */
	public function getLargura() {
		return $this->largura;
	}

	/**
	 * @param double $value
	 */
	public function setLargura($value) {
		$this->largura = $value;
	}

	/**
	 * @return double
	 */
	public function getAltura() {
		return $this->altura;
	}

	/**
	 * @param double $value
	 */
	public function setAltura($value) {
		$this->altura = $value;
	}

	/**
	 * @return double
	 */
	public function getProfundidade() {
		return $this->profundidade;
	}

	/**
	 * @param double $value
	 */
	public function setProfundidade($value) {
		$this->profundidade = $value;
	}

    /**
     * @return bool
     */
	public function getRotaEncontrada() {
	    return $this->rota_encontrada;
    }

    /**
     * @param bool $value
     */
    public function setRotaEncontrada($value) {
	    $this->rota_encontrada = $value;
    }

    /**
     * @return string
     */
	public function getEnderecoOrigem() {
	    return $this->endereco_origem;
    }

    /**
     * @param string $value
     */
    public function setEnderecoOrigem($value) {
	    $this->endereco_origem = $value;
    }

    /**
     * @return string
     */
    public function getEnderecoDestino() {
        return $this->endereco_destino;
    }

    /**
     * @param string $value
     */
    public function setEnderecoDestino($value) {
        $this->endereco_destino = $value;
    }

	/**
	 * @return double
	 */
	public function getDistancia() {
		return $this->distancia;
	}

	/**
	 * @param double $value
	 */
	public function setDistancia($value) {
		$this->distancia = $value;
	}

    /**
     * @return string
     */
	public function getDistanciaStr() {
	    return $this->distancia_str;
    }

    /**
     * @param string $value
     */
    public function setDistanciaStr($value) {
	    $this->distancia_str = $value;
    }

    /**
     * @return int
     */
    public function getTempo() {
        return $this->tempo;
    }

    /**
     * @param int $value
     */
    public function setTempo($value) {
        $this->tempo = $value;
    }

    /**
     * @return string
     */
    public function getTempoStr() {
        return $this->tempo_str;
    }

    /**
     * @param string $value
     */
    public function setTempoStr($value) {
        $this->tempo_str = $value;
    }

	/**
	 * @return string
	 */
	public function getObservacao() {
		return $this->observacao;
	}

	/**
	 * @param string $value
	 */
	public function setObservacao($value) {
		$this->observacao = $value;
	}

	/**
	 * @return int
	 */
	public function getCodSituacao() {
		return $this->cod_situacao;
	}

	/**
	 * @param int $value
	 */
	public function setCodSituacao($value) {
		$this->cod_situacao = $value;
	}

    /**
     * @return int
     */
	public function getNotaMotorista() {
	    return $this->nota_motorista;
    }

    /**
     * @param int $value
     */
    public function setNotaMotorista($value) {
	    $this->nota_motorista = $value;
    }

    /**
     * @return int
     */
    public function getNotaFrete() {
        return $this->nota_frete;
    }

    /**
     * @param int $value
     */
    public function setNotaFrete($value) {
        $this->nota_frete = $value;
    }

    /**
     * @return string
     */
	public function getPolyline() {
	    return $this->polyline;
    }

    /**
     * @param string $value
     */
    public function setPolyline($value) {
	    $this->polyline = $value;
    }

    /**
     * @return string
     */
    public function getDimensao() {
        $str = "";
        if ($this->getLargura() > 0 && $this->getAltura() > 0 && $this->getProfundidade() > 0) {
            $str  = number_format($this->getLargura(), 1, ",", ".") . "x";
            $str .= number_format($this->getAltura(), 1, ",", ".") . "x";
            $str .= number_format($this->getProfundidade(), 1, ",", ".") . "cm";
        }
        elseif ($this->getLargura() > 0 && $this->getAltura() > 0) {
            $str  = number_format($this->getLargura(), 1, ",", ".") . "x";
            $str .= number_format($this->getAltura(), 1, ",", ".") . "cm";
        }
        return $str;
    }

    /**
     * @param int $width
     * @param int $height
     * @return string
     */
    public function getFotoUrl($width = 800, $height = 600) {
        if (!isNullOrEmpty($this->foto)) {
            return "frete/" . $width . "x" . $height . "/" . $this->foto;
        }
        return "img/" . $width . "x" . $height . "/anonimo.png";
    }

	/**
	 * @return string
	 */
	public function getSituacaoStr() {
		$bll = FreteBLLFactory::create();
		$lista = $bll->listarSituacao();
		return $lista[$this->getCodSituacao()];
	}

    /**
     * @throws Exception
     * @return string
     */
    public function getTipoVeiculoStr() {
        $tipos = array();
        foreach ($this->listarTipoVeiculo() as $tipoVeiculo) {
            $tipos[] = $tipoVeiculo->getNome();
        }
        return implode(", ", $tipos);
    }

    /**
     * @throws Exception
     * @return string
     */
    public function getCarroceriaStr() {
        $carrocerias = array();
        foreach ($this->listarCarroceria() as $carroceria) {
            $carrocerias[] = $carroceria->getNome();
        }
        return implode(", ", $carrocerias);
    }

    /**
     * @return string
     */
	public function getDataInclusaoStr() {
	    return date("d/m/Y H:i", strtotime($this->getDataInclusao()));
    }

    /**
     * @return string
     */
    public function getUltimaAlteracaoStr() {
        return date("d/m/Y H:i", strtotime($this->getUltimaAlteracao()));
    }

    /**
     * @return string
     */
    public function getDataRetiradaStr() {
        if (!isNullOrEmpty($this->getDataRetirada())) {
            return date("d/m/Y H:i", strtotime($this->getDataRetirada()));
        }
        return null;
    }

    /**
     * @return string
     */
    public function getDataEntregaStr() {
        if (!isNullOrEmpty($this->getDataEntrega())) {
            return date("d/m/Y H:i", strtotime($this->getDataEntrega()));
        }
        return null;
    }

    /**
     * @return string
     */
    public function getDataRetiradaExecutadaStr() {
        if (!isNullOrEmpty($this->getDataRetiradaExecutada())) {
            return date("d/m/Y H:i", strtotime($this->getDataRetiradaExecutada()));
        }
        return null;
    }

    /**
     * @return string
     */
    public function getDataEntregaExecutadaStr() {
        if (!isNullOrEmpty($this->getDataEntregaExecutada())) {
            return date("d/m/Y H:i", strtotime($this->getDataEntregaExecutada()));
        }
        return null;
    }

    /**
     * @return int
     */
    public function getDuracao() {
        return $this->duracao;
    }

    /**
     * @return int
     */
    public function getPrevisao() {
        if (!isNullOrEmpty($this->getDataEntrega()) && !isNullOrEmpty($this->getDataRetirada())) {
            $dataEntrega = strtotime($this->getDataEntrega());
            $dataRetirada = strtotime($this->getDataRetirada());
            if ($dataEntrega > 0 && $dataRetirada > 0 && $dataEntrega > $dataRetirada) {
                return $dataEntrega - $dataRetirada;
            }
        }
        return 0;
    }

    /**
     * @return string
     */
    public function getPrecoStr() {
	    return "R$ " . number_format($this->getPreco(), 2, ",", ".");
    }

	/**
     * @throws Exception
	 * @return FreteLocalInfo[]
	 */
	public function listarLocal() {
		if (is_null($this->locais) && $this->getId() > 0) {
			$bll = new FreteLocalBLL();
			$this->locais = $bll->listar($this->getId());
		}
		return $this->locais;
	}

	/**
	 * Limpa todos os Locais relacionados com Entregas.
	 */
	public function limparLocal() {
		$this->locais = array();
	}

	/**
     * @throws Exception
	 * @param FreteLocalInfo $local
	 */
	public function adicionarLocal($local) {
		$this->listarLocal();
		$this->locais[] = $local;
	}

	public function limparTipoVeiculo() {
	    $this->tiposVeiculo = array();
    }

    /**
     * @return TipoVeiculoInfo[]
     * @throws Exception
     */
    public function listarTipoVeiculo() {
        if (is_null($this->tiposVeiculo)) {
            if ($this->getId() > 0) {
                $dal = FreteTipoVeiculoDALFactory::create();
                $this->tiposVeiculo = $dal->listarPorFrete($this->getId());
            }
            else {
                $this->limparTipoVeiculo();
            }
        }
        return $this->tiposVeiculo;
    }

    /**
     * @param TipoVeiculoInfo $value
     * @throws Exception
     */
    public function adicionarTipoVeiculo($value) {
        $this->listarTipoVeiculo();
        $this->tiposVeiculo[] = $value;
    }

    public function limparCarroceria() {
        $this->carrocerias = array();
    }

    /**
     * @return TipoCarroceriaInfo[]
     * @throws Exception
     */
    public function listarCarroceria() {
        if (is_null($this->carrocerias)) {
            if ($this->getId() > 0) {
                $dal = FreteTipoCarroceriaDALFactory::create();
                $this->carrocerias = $dal->listarPorFrete($this->getId());
            }
            else {
                $this->limparCarroceria();
            }
        }
        return $this->carrocerias;
    }

    /**
     * @param TipoCarroceriaInfo $value
     * @throws Exception
     */
    public function adicionarCarroceria($value) {
        $this->listarCarroceria();
        $this->carrocerias[] = $value;
    }

    /**
     * @return FreteLocalInfo|null
     * @throws Exception
     */
    public function getOrigem() {
        $origem = null;
        foreach ($this->listarLocal() as $local) {
            if ($local->getCodTipo() == FreteLocalInfo::ORIGEM) {
                $origem = $local;
                break;
            }
        }
        return $origem;
    }

    /**
     * @return FreteLocalInfo|null
     * @throws Exception
     */
    public function getDestino() {
        $origem = null;
        foreach ($this->listarLocal() as $local) {
            if ($local->getCodTipo() == FreteLocalInfo::DESTINO) {
                $origem = $local;
                break;
            }
        }
        return $origem;
    }

    /**
     * @return string
     * @throws Exception
     */
	public function getOrigemStr() {
        $local = $this->getOrigem();
	    return !is_null($local) ? $local->getPosicaoStr() : "";
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getDestinoStr() {
        $local = $this->getDestino();
        return !is_null($local) ? $local->getPosicaoStr() : "";
    }

	/**
     * @throws Exception
	 * @return stdClass
	 */
	public function jsonSerialize() {
		$value = new stdClass();
		$value->id_frete = $this->getId();
		$value->id_usuario = $this->getIdUsuario();
		$value->usuario = $this->getUsuario();
		$value->id_motorista = $this->getIdMotorista();
		$value->motorista = $this->getMotorista();
		$value->id_pagamento = $this->getIdPagamento();
		$value->pagamento = $this->getPagamento();
		$value->data_inclusao = $this->getDataInclusao();
        $value->data_inclusao_str = $this->getDataInclusaoStr();
		$value->ultima_alteracao = $this->getUltimaAlteracao();
        $value->ultima_alteracao_str = $this->getUltimaAlteracaoStr();
        $value->data_retirada = $this->getDataRetirada();
        $value->data_retirada_str = $this->getDataRetiradaStr();
        $value->data_entrega = $this->getDataEntrega();
        $value->data_entrega_str = $this->getDataEntregaStr();
        $value->data_retirada_executada = $this->getDataRetiradaExecutada();
        $value->data_retirada_executada_str = $this->getDataRetiradaExecutadaStr();
        $value->data_entrega_executada = $this->getDataEntregaExecutada();
        $value->data_entrega_executada_str = $this->getDataEntregaExecutadaStr();
        $value->duracao = intval($this->getDuracao());
        $value->previsao = intvalx($this->getPrevisao());
		$value->foto = $this->getFoto();
		$value->foto_url = $this->getFotoUrl();
		$value->preco = floatval($this->getPreco());
		$value->peso = floatval($this->getPeso());
		$value->largura = floatval($this->getLargura());
		$value->altura = floatval($this->getAltura());
		$value->profundidade = floatval($this->getProfundidade());
		$value->observacao = $this->getObservacao();
        $value->carroceria = $this->getCarroceriaStr();
        $value->endereco_origem = $this->getEnderecoOrigem();
        $value->endereco_destino = $this->getEnderecoDestino();
        $value->distancia = intval($this->getDistancia());
        $value->distancia_str = $this->getDistanciaStr();
        $value->tempo = intval($this->getTempo());
        $value->tempo_str = $this->getTempoStr();
        $value->rota_encontrada = boolval($this->getRotaEncontrada());
        $value->nota_motorista = intval($this->getNotaMotorista());
        $value->nota_frete = intval($this->getNotaFrete());
        $value->dimensao = $this->getDimensao();
        $value->cod_situacao = intval($this->getCodSituacao());
        $value->situacao = $this->getSituacaoStr();
		$value->polyline = $this->getPolyline();
		$value->locais = array();
		foreach ($this->listarLocal() as $item) {
			$value->locais[] = $item->jsonSerialize();
		}
        $value->veiculos = array();
        foreach ($this->listarTipoVeiculo() as $tipo) {
            $value->veiculos[] = $tipo->jsonSerialize();
        }
        $value->veiculos_str = $this->getTipoVeiculoStr();
        $value->carrocerias = array();
        foreach ($this->listarCarroceria() as $carroceria) {
            $value->carrocerias[] = $carroceria->jsonSerialize();
        }
        $value->carrocerias_str = $this->getCarroceriaStr();
		return $value;
	}

	/**
     * @throws Exception
	 * @param stdClass $value
	 * @return FreteInfo
	 */
	public static function fromJson($value) {
		$frete = new FreteInfo();
		$frete->setId($value->id_frete);
		$frete->setIdUsuario($value->id_usuario);
		$frete->setIdMotorista($value->id_motorista);
        $frete->setIdPagamento($value->id_pagamento);
		$frete->setDataInclusao($value->data_inclusao);
		$frete->setUltimaAlteracao($value->ultima_alteracao);
		$frete->setDataRetirada($value->data_retirada);
        $frete->setDataEntrega($value->data_entrega);
        $frete->setDataRetiradaExecutada($value->data_retirada_executada);
        $frete->setDataEntregaExecutada($value->data_entrega_executada);
		$frete->setFoto($value->foto);
		$frete->setFotoBase64($value->foto_base64);
		$frete->setPreco($value->preco);
		$frete->setPeso($value->peso);
		$frete->setLargura($value->largura);
		$frete->setAltura($value->altura);
		$frete->setProfundidade($value->profundidade);
		$frete->setObservacao($value->observacao);
		$frete->setRotaEncontrada($value->rota_encontrada);
        $frete->setEnderecoOrigem($value->endereco_origem);
        $frete->setEnderecoDestino($value->endereco_destino);
        $frete->setDistancia($value->distancia);
        $frete->setDistanciaStr($value->distancia_str);
        $frete->setTempo($value->tempo);
        $frete->setTempoStr($value->tempo_str);
        $frete->setRotaEncontrada($value->rota_encontrada);
        $frete->setNotaFrete($value->nota_frete);
        $frete->setNotaMotorista($value->nota_motorista);
		$frete->setPolyline($value->polyline);
		$frete->setCodSituacao($value->cod_situacao);
		if (isset($value->locais)) {
			$frete->limparLocal();
			foreach ($value->locais as $item) {
				$frete->adicionarLocal(FreteLocalInfo::fromJson($item));
			}
		}
        if (isset($value->veiculos)) {
            $frete->limparTipoVeiculo();
            foreach ($value->veiculos as $tipoVeiculo) {
                $frete->adicionarTipoVeiculo(TipoVeiculoInfo::fromJson($tipoVeiculo));
            }
        }
        if (isset($value->carrocerias)) {
            $frete->limparCarroceria();
            foreach ($value->carrocerias as $carroceria) {
                $frete->adicionarCarroceria(TipoCarroceriaInfo::fromJson($carroceria));
            }
        }
		return $frete;
	}

}


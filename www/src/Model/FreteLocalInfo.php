<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 08/12/2017
 * Time: 01:10
 * Tablename: entrega_local
 */

namespace Emagine\Frete\Model;

use Emagine\Frete\BLLFactory\FreteBLLFactory;
use stdClass;
use JsonSerializable;
use Emagine\Frete\BLL\FreteBLL;
use Emagine\Frete\BLL\FreteLocalBLL;
use Emagine\Frete\Model\FreteInfo;

/**
 * Class FreteLocalInfo
 * @package Emagine\Frete\Model
 * @tablename entrega_local
 * @author EmagineCRUD
 */
class FreteLocalInfo implements JsonSerializable {


	const ORIGEM = 1;
	const PARADA = 2;
	const DESTINO = 3;

	private $id_local;
	private $id_frete;
	private $cod_tipo;
	private $ordem;
	private $cep;
	private $logradouro;
	private $complemento;
	private $numero;
	private $bairro;
	private $cidade;
	private $uf;
	private $latitude;
	private $longitude;
	private $frete = null;

	/**
	 * Auto Increment Field
	 * @return int
	 */
	public function getId() {
		return $this->id_local;
	}

	/**
	 * @param int $value
	 */
	public function setId($value) {
		$this->id_local = $value;
	}

	/**
	 * @return int
	 */
	public function getIdFrete() {
		return $this->id_frete;
	}

	/**
	 * @param int $value
	 */
	public function setIdFrete($value) {
		$this->id_frete = $value;
	}

	/**
     * @throws \Exception
	 * @return FreteInfo
	 */
	public function getFrete() {
		if (is_null($this->frete) && $this->getIdFrete() > 0) {
			$bll = FreteBLLFactory::create();
			$this->frete = $bll->pegar($this->getIdFrete());
		}
		return $this->frete;
	}

	/**
	 * @return int
	 */
	public function getCodTipo() {
		return $this->cod_tipo;
	}

	/**
	 * @param int-option $value
	 */
	public function setCodTipo($value) {
		$this->cod_tipo = $value;
	}

	/**
	 * @return string
	 */
	public function getCodTipoStr() {
		$bll = new FreteLocalBLL();
		$lista = $bll->listarCodTipo();
		return $lista[$this->getCodTipo()];
	}

	/**
	 * @return int
	 */
	public function getOrdem() {
		return $this->ordem;
	}

	/**
	 * @param int $value
	 */
	public function setOrdem($value) {
		$this->ordem = $value;
	}

    /**
     * @return string
     */
	public function getCep() {
	    return $this->cep;
    }

    /**
     * @param string $value
     */
    public function setCep($value) {
	    $this->cep = $value;
    }

    /**
     * @return string
     */
	public function getLogradouro() {
	    return $this->logradouro;
    }

    /**
     * @param string $value
     */
    public function setLogradouro($value) {
	    $this->logradouro = $value;
    }

    /**
     * @return string
     */
    public function getComplemento() {
        return $this->complemento;
    }

    /**
     * @param string $value
     */
    public function setComplemento($value) {
        $this->complemento = $value;
    }

    /**
     * @return string
     */
    public function getNumero() {
        return $this->numero;
    }

    /**
     * @param string $value
     */
    public function setNumero($value) {
        $this->numero = $value;
    }

    /**
     * @return string
     */
    public function getBairro() {
        return $this->bairro;
    }

    /**
     * @param string $value
     */
    public function setBairro($value) {
        $this->bairro = $value;
    }

    /**
     * @return string
     */
    public function getCidade() {
        return $this->cidade;
    }

    /**
     * @param string $value
     */
    public function setCidade($value) {
        $this->cidade = $value;
    }

    /**
     * @return string
     */
    public function getUf() {
        return $this->uf;
    }

    /**
     * @param string $value
     */
    public function setUf($value) {
        $this->uf = $value;
    }

	/**
	 * @return double
	 */
	public function getLatitude() {
		return $this->latitude;
	}

	/**
	 * @param double $value
	 */
	public function setLatitude($value) {
		$this->latitude = $value;
	}

	/**
	 * @return double
	 */
	public function getLongitude() {
		return $this->longitude;
	}

	/**
	 * @param double $value
	 */
	public function setLongitude($value) {
		$this->longitude = $value;
	}

    /**
     * @return string
     */
	public function getPosicaoStr() {
	    $posicao = "";
	    $posicao .= number_format($this->getLatitude(), 5, ".", "");
	    $posicao .= ",";
        $posicao .= number_format($this->getLongitude(), 5, ".", "");
        return $posicao;
    }

    /**
     * @return LocalInfo
     */
    public function getLocal() {
	    return new LocalInfo($this->getLatitude(), $this->getLongitude());
    }

	/**
	 * @return stdClass
	 */
	public function jsonSerialize() {
		$value = new stdClass();
		$value->id_local = $this->getId();
		$value->id_frete = $this->getIdFrete();
		$value->cod_tipo = $this->getCodTipo();
		$value->cep = $this->getCep();
        $value->logradouro = $this->getLogradouro();
        $value->complemento = $this->getComplemento();
        $value->numero = $this->getNumero();
        $value->bairro = $this->getBairro();
        $value->cidade = $this->getCidade();
        $value->uf = $this->getUf();
		$value->ordem = $this->getOrdem();
		$value->latitude = $this->getLatitude();
		$value->longitude = $this->getLongitude();
		return $value;
	}

	/**
	 * @param stdClass $value
	 * @return FreteLocalInfo
	 */
	public static function fromJson($value) {
		$local = new FreteLocalInfo();
		$local->setId($value->id_local);
		$local->setIdFrete($value->id_frete);
		$local->setCodTipo($value->cod_tipo);
		$local->setCep($value->cep);
        $local->setLogradouro($value->logradouro);
        $local->setComplemento($value->complemento);
        $local->setNumero($value->numero);
        $local->setBairro($value->bairro);
        $local->setCidade($value->cidade);
        $local->setUf($value->uf);
		$local->setOrdem($value->ordem);
		$local->setLatitude($value->latitude);
		$local->setLongitude($value->longitude);
		return $local;
	}

}


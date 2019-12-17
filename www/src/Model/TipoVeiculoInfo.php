<?php

namespace Emagine\Frete\Model;

use stdClass;
use Exception;
use JsonSerializable;

class TipoVeiculoInfo implements JsonSerializable {

    const MOTO = 1;
    const CARRO = 2;
    const CAMINHONETE = 3;
    const CAMINHAO = 4;

    private $id_tipo;
    private $nome;
    private $cod_tipo;
    private $foto;
    private $capacidade;

    /**
     * @return int
     */
    public function getId() {
        return $this->id_tipo;
    }

    /**
     * @param int $value
     */
    public function setId($value) {
        $this->id_tipo = $value;
    }

    /**
     * @return string
     */
    public function getNome() {
        return $this->nome;
    }

    /**
     * @param string $value
     */
    public function setNome($value) {
        $this->nome = $value;
    }

    /**
     * @return string
     */
    public function getCodTipo() {
        return $this->cod_tipo;
    }

    /**
     * @param string $value
     */
    public function setCodTipo($value) {
        $this->cod_tipo = $value;
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
     * @return double
     */
    public function getCapacidade() {
        return $this->capacidade;
    }

    /**
     * @param double $value
     */
    public function setCapacidade($value) {
        $this->capacidade = $value;
    }

    /**
     * @throws Exception
     * @param int $largura
     * @param int $altura
     * @return string
     */
    public function getFotoUrl($largura = 120, $altura = 120) {
            if (!defined("SITE_URL")) {
                throw new Exception("SITE_URL não foi definido.");
            }
            if (isNullOrEmpty($this->getFoto())) {
                return SITE_URL . sprintf("/img/%sx%s/anonimo.png", $largura, $altura);
            }
            return SITE_URL . sprintf("/veiculo/%sx%s/", $largura, $altura) . $this->getFoto();
    }

    /**
     * @param stdClass $value
     * @return TipoVeiculoInfo
     */
    public static function fromJson($value) {
        $tipo = new TipoVeiculoInfo();
        $tipo->setId($value->id_tipo);
        $tipo->setNome($value->nome);
        $tipo->setCodTipo($value->cod_tipo);
        $tipo->setFoto($value->foto);
        $tipo->setCapacidade($value->capacidade);
        return $tipo;
    }

    /**
     * @throws Exception
     * @return stdClass
     */
    public function jsonSerialize()
    {
        $tipo = new stdClass();
        $tipo->id_tipo = intval($this->getId());
        $tipo->nome = $this->getNome();
        $tipo->cod_tipo = intval($this->getCodTipo());
        $tipo->foto = $this->getFoto();
        $tipo->foto_url = $this->getFotoUrl();
        $tipo->capacidade = floatval($this->getCapacidade());
        return $tipo;
    }
}
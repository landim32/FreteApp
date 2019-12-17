<?php
/**
 * Created by PhpStorm.
 * User: foursys
 * Date: 31/12/17
 * Time: 22:11
 */

namespace Emagine\Frete\Model;

use stdClass;
use JsonSerializable;

class AceiteEnvioInfo implements JsonSerializable
{
    private $id_frete;
    private $id_motorista;
    private $aceite;
    private $valor;

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
     * @return bool
     */
    public function getAceite() {
        return $this->aceite;
    }

    /**
     * @param bool $value
     */
    public function setAceite($value) {
        $this->aceite = $value;
    }

    /**
     * @return double
     */
    public function getValor() {
        return $this->valor;
    }

    /**
     * @param double $value
     */
    public function setValor($value) {
        $this->valor = $value;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize() {
        $value = new stdClass();
        $value->id_frete = $this->getIdFrete();
        $value->id_motorista = $this->getIdMotorista();
        $value->aceite = $this->getAceite();
        $value->valor = $this->getValor();
        return $value;
    }

    /**
     * @param stdClass $value
     * @return AceiteEnvioInfo
     */
    public static function fromJson($value) {
        $envio = new AceiteEnvioInfo();
        $envio->setIdFrete($value->id_frete);
        $envio->setIdMotorista($value->id_motorista);
        $envio->setAceite($value->aceite);
        $envio->setValor($value->valor);
        return $envio;
    }
}
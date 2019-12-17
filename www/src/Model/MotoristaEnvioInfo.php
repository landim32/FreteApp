<?php
/**
 * Created by PhpStorm.
 * User: foursys
 * Date: 29/12/17
 * Time: 11:40
 */

namespace Emagine\Frete\Model;

use stdClass;
use JsonSerializable;

class MotoristaEnvioInfo implements JsonSerializable
{
    private $id_motorista;
    private $latitude;
    private $longitude;
    private $cod_disponibilidade;

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
     * @return float
     */
    public function getLatitude() {
        return $this->latitude;
    }

    /**
     * @param float $value
     */
    public function setLatitude($value) {
        $this->latitude = $value;
    }

    /**
     * @return float
     */
    public function getLongitude() {
        return $this->longitude;
    }

    /**
     * @param float $value
     */
    public function setLongitude($value) {
        $this->longitude = $value;
    }

    /**
     * @return int
     */
    public function getCodDisponibilidade() {
        return $this->cod_disponibilidade;
    }

    /**
     * @param int $value
     */
    public function setCodDisponibilidade($value) {
        $this->cod_disponibilidade = $value;
    }

    /**
     * @return string
     */
    public function getPosicaoStr() {
        $str = "";
        $str .= number_format($this->getLatitude(), 5, ".", "");
        $str .= ",";
        $str .= number_format($this->getLongitude(), 5, ".", "");
        return $str;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize()
    {
        $value = new stdClass();
        $value->id_motorista = $this->getIdMotorista();
        $value->latitude = $this->getLatitude();
        $value->longitude = $this->getLongitude();
        $value->cod_disponibilidade = $this->getCodDisponibilidade();
        return $value;
    }

    public static function fromJson($value) {
        $envio = new MotoristaEnvioInfo();
        $envio->setIdMotorista($value->id_motorista);
        $envio->setLatitude($value->latitude);
        $envio->setLongitude($value->longitude);
        $envio->setCodDisponibilidade($value->cod_disponibilidade);
        return $envio;
    }
}
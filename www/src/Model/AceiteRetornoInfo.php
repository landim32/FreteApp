<?php
/**
 * Created by PhpStorm.
 * User: foursys
 * Date: 01/01/18
 * Time: 01:21
 */

namespace Emagine\Frete\Model;

use stdClass;
use JsonSerializable;

class AceiteRetornoInfo implements JsonSerializable
{
    private $id_frete;
    private $id_motorista;
    private $aceite;
    private $mensagem;

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
     * @return string
     */
    public function getMensagem() {
        return $this->mensagem;
    }

    /**
     * @param string $value
     */
    public function setMensagem($value) {
        $this->mensagem = $value;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize() {
        $value = new stdClass();
        $value->id_frete = $this->getIdFrete();
        $value->id_motorista = $this->getIdMotorista();
        $value->aceite = $this->getAceite();
        $value->mensagem = $this->getMensagem();
        return $value;
    }

    /**
     * @param stdClass $value
     * @return AceiteRetornoInfo
     */
    public static function fromJson($value) {
        $retorno = new AceiteRetornoInfo();
        $retorno->setIdFrete($value->id_frete);
        $retorno->setIdMotorista($value->id_motorista);
        $retorno->setAceite($value->aceite);
        $retorno->setMensagem($value->mensagem);
        return $retorno;
    }
}
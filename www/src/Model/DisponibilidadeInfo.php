<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 16/04/2018
 * Time: 15:12
 */

namespace Emagine\Frete\Model;

use stdClass;
use JsonSerializable;

class DisponibilidadeInfo implements JsonSerializable
{
    private $id_disponibilidade;
    private $id_usuario;
    private $data;
    private $uf;
    private $cidade;

    /**
     * @return int
     */
    public function getId() {
        return $this->id_disponibilidade;
    }

    /**
     * @param int $value
     */
    public function setId($value) {
        $this->id_disponibilidade = $value;
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
     * @return string
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @param string $value
     */
    public function setData($value) {
        $this->data = $value;
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
     * @param stdClass $value
     * @return DisponibilidadeInfo
     */
    public static function fromJson($value) {
        $disponibilidade = new DisponibilidadeInfo();
        $disponibilidade->setId($value->id_disponibilidade);
        $disponibilidade->setIdUsuario($value->id_usuario);
        $disponibilidade->setData($value->data);
        $disponibilidade->setUf($value->uf);
        $disponibilidade->setCidade($value->cidade);
        return $disponibilidade;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize()
    {
        $disponibilidade = new stdClass();
        $disponibilidade->id_disponibilidade = intval($this->getId());
        $disponibilidade->id_usuario = intval($this->getIdUsuario());
        $disponibilidade->data = $this->getData();
        $disponibilidade->uf = $this->getUf();
        $disponibilidade->cidade = $this->getCidade();
        return $disponibilidade;
    }
}
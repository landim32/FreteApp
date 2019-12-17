<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 05/04/2018
 * Time: 07:38
 */

namespace Emagine\Frete\Model;

use stdClass;
use JsonSerializable;

class FreteHistoricoInfo implements JsonSerializable
{
    private $id_historico;
    private $id_frete;
    private $data_inclusao;
    private $latitude;
    private $longitude;
    private $endereco;

    /**
     * @return int
     */
    public function getId() {
        return $this->id_historico;
    }

    /**
     * @param int $value
     */
    public function setId($value) {
        $this->id_historico = $value;
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
     * @return float
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
    public function getEndereco() {
        return $this->endereco;
    }

    /**
     * @param string $value
     */
    public function setEndereco($value) {
        $this->endereco = $value;
    }

    /**
     * @return string
     */
    public function getDataInclusaoStr() {
        return date("d/m/Y H:i", strtotime($this->getDataInclusao()));
    }

    /**
     * @param stdClass $value
     * @return FreteHistoricoInfo
     */
    public static function fromJson($value) {
        $historico = new FreteHistoricoInfo();
        $historico->setId($value->id_historico);
        $historico->setIdFrete($value->id_frete);
        $historico->setDataInclusao($value->data_inclusao);
        $historico->setLatitude($value->latitude);
        $historico->setLongitude($value->longitude);
        $historico->setEndereco($value->endereco);
        return $historico;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize()
    {
        $historico = new stdClass();
        $historico->id_historico = $this->getId();
        $historico->id_frete = $this->getIdFrete();
        $historico->data_inclusao = $this->getDataInclusao();
        $historico->data_inclusao_str = $this->getDataInclusaoStr();
        $historico->latitude = $this->getLatitude();
        $historico->longitude = $this->getLongitude();
        $historico->endereco = $this->getEndereco();
        return $historico;
    }
}
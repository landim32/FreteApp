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

class FreteRetornoInfo implements JsonSerializable
{
    private $id_motorista;
    private $id_frete;
    private $cod_situacao;
    private $latitude;
    private $longitude;
    private $distancia;
    private $distancia_str;
    private $tempo;
    private $tempo_str;
    private $polyline;
    private $mensagens = array();

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
    public function getDistancia() {
        return $this->distancia;
    }

    /**
     * @param int $value
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
     * @return string[]
     */
    public function listarMensagem() {
        return $this->mensagens;
    }

    public function limparMensagem() {
        $this->mensagens = array();
    }

    /**
     * @param string $mensagem
     */
    public function adicionarMensagem($mensagem) {
        $this->mensagens[] = $mensagem;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize()
    {
        $id_motorista = intval($this->getIdMotorista());
        $id_frete = intval($this->getIdFrete());
        $cod_situacao = intval($this->getCodSituacao());

        $value = new stdClass();
        $value->id_motorista = ($id_motorista > 0) ? $id_motorista : null;
        $value->id_frete = ($id_frete > 0) ? $id_frete : null;
        $value->cod_situacao = ($cod_situacao > 0) ? $cod_situacao : null;
        $value->latitude = $this->getLatitude();
        $value->longitude = $this->getLongitude();
        $value->distancia = $this->getDistancia();
        $value->distancia_str = $this->getDistanciaStr();
        $value->tempo = $this->getTempo();
        $value->tempo_str = $this->getTempoStr();
        $value->polyline = $this->getPolyline();
        $value->mensagens = array();
        foreach ($this->listarMensagem() as $mensagem) {
            $value->mensagens[] = $mensagem;
        }
        return $value;
    }

    /**
     * @param stdClass $value
     * @return FreteRetornoInfo
     */
    public static function fromJson($value) {
        $envio = new FreteRetornoInfo();
        $envio->setIdMotorista($value->id_motorista);
        $envio->setIdFrete($value->id_frete);
        $envio->setCodSituacao($value->cod_situacao);
        $envio->setLatitude($value->latitude);
        $envio->setLongitude($value->longitude);
        $envio->setDistancia($value->distancia);
        $envio->setDistanciaStr($value->distancia_str);
        $envio->setTempo($value->tempo);
        $envio->setTempoStr($value->tempo_str);
        $envio->setPolyline($value->polyline);
        $envio->limparMensagem();
        foreach ($value->mensagens as $mensagem) {
            $envio->adicionarMensagem($mensagem);
        }
        return $envio;
    }
}
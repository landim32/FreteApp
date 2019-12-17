<?php
/**
 * Created by PhpStorm.
 * User: foursys
 * Date: 31/12/17
 * Time: 04:04
 */

namespace Emagine\Frete\Model;

use stdClass;
use JsonSerializable;

class MotoristaFreteInfo implements JsonSerializable
{
    private $id_frete;
    private $endereco_origem;
    private $endereco_destino;
    private $local_origem;
    private $local_destino;
    private $duracao;
    private $duracao_str;
    private $duracao_encomenda;
    private $duracao_encomenda_str;
    private $distancia;
    private $distancia_str;
    private $distancia_encomenda;
    private $distancia_encomenda_str;
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
     * @return LocalInfo
     */
    public function getLocalOrigem() {
        return $this->local_origem;
    }

    /**
     * @param LocalInfo $value
     */
    public function setLocalOrigem($value) {
        $this->local_origem = $value;
    }

    /**
     * @return LocalInfo
     */
    public function getLocalDestino() {
        return $this->local_destino;
    }

    /**
     * @param LocalInfo $value
     */
    public function setLocalDestino($value) {
        $this->local_destino = $value;
    }

    /**
     * @return int
     */
    public function getDuracao() {
        return $this->duracao;
    }

    /**
     * @param int $value
     */
    public function setDuracao($value) {
        $this->duracao = $value;
    }

    /**
     * @return string
     */
    public function getDuracaoStr() {
        return $this->duracao_str;
    }

    /**
     * @param string $value
     */
    public function setDuracaoStr($value) {
        $this->duracao_str = $value;
    }

    /**
     * @return int
     */
    public function getDuracaoEncomenda() {
        return $this->duracao_encomenda;
    }

    /**
     * @param int $value
     */
    public function setDuracaoEncomenda($value) {
        $this->duracao_encomenda = $value;
    }

    /**
     * @return string
     */
    public function getDuracaoEncomendaStr() {
        return $this->duracao_encomenda_str;
    }

    /**
     * @param string $value
     */
    public function setDuracaoEncomendaStr($value) {
        $this->duracao_encomenda_str = $value;
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
     * @return int
     */
    public function getDistanciaEncomenda() {
        return $this->distancia_encomenda;
    }

    /**
     * @return string
     */
    public function getDistanciaEncomendaStr() {
        return $this->distancia_encomenda_str;
    }

    /**
     * @param string $value
     */
    public function setDistanciaEncomendaStr($value) {
        $this->distancia_encomenda_str = $value;
    }

    /**
     * @param int $value
     */
    public function setDistanciaEncomenda($value) {
        $this->distancia_encomenda = $value;
    }

    /**
     * @param string $value
     */
    public function setDistanciaStr($value) {
        $this->distancia_str = $value;
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
    public function jsonSerialize()
    {
        $value = new stdClass();
        $value->id_frete = intval($this->getIdFrete());
        $value->endereco_origem = $this->getEnderecoOrigem();
        $value->endereco_destino = $this->getEnderecoDestino();
        $value->local_origem = $this->getLocalOrigem();
        $value->local_destino = $this->getLocalDestino();
        $value->duracao = $this->getDuracao();
        $value->duracao_str = $this->getDuracaoStr();
        $value->duracao_encomenda = $this->getDuracaoEncomenda();
        $value->duracao_encomenda_str = $this->getDuracaoEncomendaStr();
        $value->distancia = $this->getDistancia();
        $value->distancia_str = $this->getDistanciaStr();
        $value->distancia_encomenda = $this->getDistanciaEncomenda();
        $value->distancia_encomenda_str = $this->getDistanciaEncomendaStr();
        $value->valor = $this->getValor();
        return $value;
    }

    /**
     * @param stdClass $value
     * @return MotoristaFreteInfo
     */
    public static function fromJson($value) {
        $envio = new MotoristaFreteInfo();
        $envio->setIdFrete($value->id_frete);
        $envio->setEnderecoOrigem($value->endereco_origem);
        $envio->setEnderecoDestino($value->endereco_destino);
        $envio->setLocalOrigem($value->local_origem);
        $envio->setLocalDestino($value->local_destino);
        $envio->setDuracao($value->duracao);
        $envio->setDuracaoStr($value->duracao_str);
        $envio->setDuracaoEncomenda($value->duracao_encomenda);
        $envio->setDuracaoEncomendaStr($value->duracao_encomenda_str);
        $envio->setDistancia($value->distancia);
        $envio->setDistanciaStr($value->distancia_str);
        $envio->setDistanciaEncomenda($value->distancia_encomenda);
        $envio->setDistanciaEncomendaStr($value->distancia_encomenda_str);
        $envio->setValor($value->valor);
        return $envio;
    }
}
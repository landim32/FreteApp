<?php
namespace Emagine\Frete\MaisCargas\Model;

use stdClass;
use JsonSerializable;

class FreteFaturaInfo implements JsonSerializable
{
    const ATIVO = 1;

    private $id_fatura;
    private $id_usuario;
    private $id_frete;
    private $preco;
    private $data_inclusao;
    private $ultima_alteracao;
    private $data_vencimento;
    private $data_pagamento;
    private $data_confirmacao;
    private $forma_pagamento;
    private $observacao;
    private $codigo_barra;
    private $url;
    private $cod_situacao;

    /**
     * @return int
     */
    public function getId() {
        return $this->id_fatura;
    }

    /**
     * @param int $value
     */
    public function setId($value) {
        $this->id_fatura = $value;
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
    public function getDataVencimento() {
        return $this->data_vencimento;
    }

    /**
     * @param string $value
     */
    public function setDataVencimento($value) {
        $this->data_vencimento = $value;
    }

    /**
     * @return string
     */
    public function getDataPagamento() {
        return $this->data_pagamento;
    }

    /**
     * @param string $value
     */
    public function setDataPagamento($value) {
        $this->data_pagamento = $value;
    }

    /**
     * @return string
     */
    public function getDataConfirmacao() {
        return $this->data_confirmacao;
    }

    /**
     * @param string $value
     */
    public function setDataConfirmacao($value) {
        $this->data_confirmacao = $value;
    }

    /**
     * @return string
     */
    public function getFormaPagamento() {
        return $this->forma_pagamento;
    }

    /**
     * @param string $value
     */
    public function setFormaPagamento($value) {
        $this->forma_pagamento = $value;
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
     * @return string
     */
    public function getCodigoBarra() {
        return $this->codigo_barra;
    }

    /**
     * @param string $value
     */
    public function setCodigoBarra($value) {
        $this->codigo_barra = $value;
    }

    /**
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @param string $value
     */
    public function setUrl($value) {
        $this->url = $value;
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
    public function getDataVencimentoStr() {
        return date("d/m/Y", strtotime($this->getDataVencimento()));
    }

    /**
     * @return string
     */
    public function getDataPagamentoStr() {
        return date("d/m/Y", strtotime($this->getDataPagamento()));
    }

    /**
     * @return string
     */
    public function getDataConfirmacaoStr() {
        return date("d/m/Y", strtotime($this->getDataConfirmacao()));
    }

    /**
     * @param stdClass $value
     * @return FreteFaturaInfo
     */
    public static function fromJson($value) {
        $fatura = new FreteFaturaInfo();
        $fatura->setId($value->id_fatura);
        $fatura->setIdUsuario($value->id_usuario);
        $fatura->setIdFrete($value->id_frete);
        $fatura->setPreco($value->preco);
        $fatura->setDataInclusao($value->data_inclusao);
        $fatura->setUltimaAlteracao($value->ultima_alteracao);
        $fatura->setDataVencimento($value->data_vencimento);
        $fatura->setDataPagamento($value->data_pagamento);
        $fatura->setDataConfirmacao($value->data_confirmacao);
        $fatura->setFormaPagamento($value->forma_pagamento);
        $fatura->setObservacao($value->observacao);
        $fatura->setCodigoBarra($value->codigo_barra);
        $fatura->setUrl($value->url);
        $fatura->setCodSituacao($value->cod_situacao);
        return $fatura;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize()
    {
        $fatura = new stdClass();
        $fatura->id_fatura = $this->getId();
        $fatura->id_usuario = $this->getIdUsuario();
        $fatura->id_frete = $this->getIdFrete();
        $fatura->preco = $this->getPreco();
        $fatura->data_inclusao = $this->getDataInclusao();
        $fatura->data_inclusao_str = $this->getDataInclusaoStr();
        $fatura->ultima_alteracao = $this->getUltimaAlteracao();
        $fatura->ultima_alteracao_str = $this->getUltimaAlteracaoStr();
        $fatura->data_vencimento = $this->getDataVencimento();
        $fatura->data_vencimento_str = $this->getDataVencimentoStr();
        $fatura->data_pagamento = $this->getDataPagamento();
        $fatura->data_pagamento_str = $this->getDataPagamentoStr();
        $fatura->data_confirmacao = $this->getDataConfirmacao();
        $fatura->data_confirmacao_str = $this->getDataConfirmacaoStr();
        $fatura->forma_pagamento = $this->getFormaPagamento();
        $fatura->observacao = $this->getObservacao();
        $fatura->codigo_barra = $this->getCodigoBarra();
        $fatura->url = $this->getUrl();
        $fatura->cod_situacao = $this->getCodSituacao();
        return $fatura;
    }
}
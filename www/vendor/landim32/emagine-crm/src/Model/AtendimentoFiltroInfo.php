<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 16/12/2018
 * Time: 15:55
 */

namespace Emagine\CRM\Model;

use stdClass;
use Exception;
use JsonSerializable;

class AtendimentoFiltroInfo implements JsonSerializable
{
    private $id_usuario = 0;
    private $id_cliente = 0;
    private $cod_situacao = 0;
    private $palavra_chave = '';
    private $pagina = 0;
    private $tamanho_pagina = 0;

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
    public function getIdCliente() {
        return $this->id_cliente;
    }

    /**
     * @param int $value
     */
    public function setIdCliente($value) {
        $this->id_cliente = $value;
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
     * @return string|null
     */
    public function getPalavraChave() {
        return $this->palavra_chave;
    }

    /**
     * @param string|null $value
     */
    public function setPalavraChave($value) {
        $this->palavra_chave = $value;
    }

    /**
     * @param int $value
     */
    public function setPagina($value) {
        $this->pagina = $value;
    }

    /**
     * @return int
     */
    public function getPagina() {
        return $this->pagina;
    }

    /**
     * @param int $value
     */
    public function setTamanhoPagina($value) {
        $this->tamanho_pagina = $value;
    }

    /**
     * @return int
     */
    public function getTamanhoPagina() {
        return $this->tamanho_pagina;
    }

    /**
     * @throws Exception
     * @param stdClass $value
     * @return AtendimentoFiltroInfo
     */
    public static function fromJson($value) {
        $filtro = new AtendimentoFiltroInfo();
        $filtro->setIdUsuario($value->id_usuario);
        $filtro->setIdCliente($value->id_cliente);
        $filtro->setCodSituacao($filtro->cod_situacao);
        $filtro->setPalavraChave($filtro->palavra_chave);
        $filtro->setPagina($filtro->pagina);
        $filtro->setTamanhoPagina($filtro->tamanho_pagina);
        return $filtro;
    }

    /**
     * @throws Exception
     * @return stdClass
     */
    public function JsonSerialize()
    {
        $filtro = new stdClass();
        $filtro->id_usuario = $this->getIdUsuario();
        $filtro->id_cliente = $this->getIdCliente();
        $filtro->cod_situacao = $this->getCodSituacao();
        $filtro->palavra_chave = $this->getPalavraChave();
        $filtro->pagina = $this->getPagina();
        $filtro->tamanho_pagina = $this->getTamanhoPagina();
        return $filtro;
    }
}
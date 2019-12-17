<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 27/06/2017
 * Time: 09:23
 */

namespace Emagine\CRM\Model;


class MigracaoZendeskInfo
{
    private $quantidade_migrado = 0;
    private $quantidade_lista = 0;
    private $quantidade_total = 0;
    private $log = array();

    /**
     * @return int
     */
    public function getQuantidadeMigrado() {
        return $this->quantidade_migrado;
    }

    /**
     * @param int $value
     */
    public function setQuantidadeMigrado($value) {
        $this->quantidade_migrado = $value;
    }

    /**
     * @return int
     */
    public function getQuantidadeLista() {
        return $this->quantidade_lista;
    }

    /**
     * @param int $value
     */
    public function setQuantidadeLista($value) {
        $this->quantidade_lista = $value;
    }

    /**
     * @return int
     */
    public function getQuantidadeTotal() {
        return $this->quantidade_total;
    }

    /**
     * @param int $value
     */
    public function setQuantidadeTotal($value) {
        $this->quantidade_total = $value;
    }

    /**
     * @return string[]
     */
    public function listarLog() {
        return $this->log;
    }

    /**
     * @param string $mensagem
     */
    public function adicionarLog($mensagem) {
        $log[] = $mensagem;
    }
}
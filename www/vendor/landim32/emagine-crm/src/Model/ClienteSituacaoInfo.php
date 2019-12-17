<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 26/06/2017
 * Time: 13:50
 */

namespace Emagine\CRM\Model;


class ClienteSituacaoInfo
{
    private $cod_situacao;
    private $nome;
    private $quantidade;

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
    public function getNome() {
        return $this->nome;
    }

    /**
     * @param string $value
     */
    public function setNome($value) {
        $this->nome = $value;
    }

    /**
     * @return int
     */
    public function getQuantidade() {
        return $this->quantidade;
    }

    /**
     * @param int $value
     */
    public function setQuantidade($value) {
        $this->quantidade = $value;
    }
}
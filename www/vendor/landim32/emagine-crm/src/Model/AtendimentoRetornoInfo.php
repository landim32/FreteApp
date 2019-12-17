<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 02/07/2017
 * Time: 07:55
 */

namespace Emagine\CRM\Model;


class AtendimentoRetornoInfo
{
    private $atendimentos = null;
    private $total = 0;

    /**
     * AtendimentoRetornoInfo constructor.
     * @param AtendimentoInfo[] $atendimentos
     * @param int $total
     */
    public function __construct($atendimentos, $total)
    {
        $this->atendimentos = $atendimentos;
        $this->total = $total;
    }

    /**
     * @return AtendimentoInfo[]
     */
    public function getAtendimentos() {
        return $this->atendimentos;
    }

    /**
     * @return int
     */
    public function getTotal() {
        return $this->total;
    }
}
<?php
namespace Emagine\CRM\Model;

class ClienteRetornoInfo
{
    private $clientes;
    private $total;

    /**
     * ClienteRetornoInfo constructor.
     * @param ClienteInfo[] $clientes
     * @param int $total
     */
    public function __construct($clientes, $total)
    {
        $this->clientes = $clientes;
        $this->total = $total;
    }

    /**
     * @return ClienteInfo[]
     */
    public function getClientes() {
        return $this->clientes;
    }

    /**
     * @return int
     */
    public function getTotal() {
        return $this->total;
    }
}
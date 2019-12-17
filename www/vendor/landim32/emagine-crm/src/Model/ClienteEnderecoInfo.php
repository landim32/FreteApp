<?php
namespace Emagine\CRM\Model;

use stdClass;
use Exception;
use Emagine\Endereco\Model\EnderecoInfo;

class ClienteEnderecoInfo extends EnderecoInfo
{
    private $id_endereco;
    private $id_cliente;

    /**
     * @return int
     */
    public function getId() {
        return $this->id_endereco;
    }

    /**
     * @param int $value
     */
    public function setId($value) {
        $this->id_endereco = $value;
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

    public function jsonSerialize() {
        $cliente = $this->jsonSerialize();
        $cliente->id_cliente = $this->getIdCliente();
        return $cliente;
    }

    /**
     * @return ClienteEnderecoInfo
     */
    public static function create()
    {
        return new ClienteEnderecoInfo();
    }

    /**
     * @throws Exception
     * @param stdClass $value
     * @return ClienteEnderecoInfo
     */
    public static function fromJson($value) {
        $endereco = parent::fromJson($value);
        if ($endereco instanceof ClienteEnderecoInfo) {
            $endereco->setIdCliente($value->id_cliente);
        }
        return $endereco;
    }
}
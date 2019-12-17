<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 22/03/2018
 * Time: 16:32
 */

namespace Emagine\Frete\Model;

use stdClass;
use Exception;
use JsonSerializable;

class TipoCarroceriaInfo implements JsonSerializable
{
    private $id_carroceria;
    private $nome;
    private $foto;

    /**
     * @return int
     */
    public function getId() {
        return $this->id_carroceria;
    }

    /**
     * @param int $value
     */
    public function setId($value) {
        $this->id_carroceria = $value;
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
     * @return string
     */
    public function getFoto() {
        return $this->foto;
    }

    /**
     * @param string $value
     */
    public function setFoto($value) {
        $this->foto = $value;
    }

    /**
     * @throws Exception
     * @return string
     */
    public function getFotoUrl() {
        if (!isNullOrEmpty($this->foto)) {
            if (!defined("SITE_URL")) {
                throw new Exception("SITE_URL não foi definido.");
            }
            return SITE_URL . "/images/carroceria/" . $this->foto;
        }
        return null;
    }

    /**
     * @param stdClass $value
     * @return TipoCarroceriaInfo
     */
    public static function fromJson($value) {
        $carroceria = new TipoCarroceriaInfo();
        $carroceria->setId($value->id_carroceria);
        $carroceria->setNome($value->nome);
        $carroceria->setFoto($value->foto);
        return $carroceria;
    }

    /**
     * @throws Exception
     * @return stdClass
     */
    public function jsonSerialize()
    {
        $carroceria = new stdClass();
        $carroceria->id_carroceria = $this->getId();
        $carroceria->nome = $this->getNome();
        $carroceria->foto = $this->getFoto();
        $carroceria->foto_url = $this->getFotoUrl();
        return $carroceria;
    }
}
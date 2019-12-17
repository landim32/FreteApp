<?php
namespace Emagine\CRM\Model;

use stdClass;
use JsonSerializable;

class TagInfo implements JsonSerializable
{
    private $id_tag;
    private $slug;
    private $nome;

    /**
     * @return int
     */
    public function getId() {
        return $this->id_tag;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setId($value) {
        $this->id_tag = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getSlug() {
        return $this->slug;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setSlug($value) {
        $this->slug = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getNome() {
        return $this->nome;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setNome($value) {
        $this->nome = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl() {
        return get_tema_path() . "/tag/" . $this->getSlug();
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize() {
        $tag = new stdClass();
        $tag->id_tag = $this->getId();
        $tag->slug = $this->getSlug();
        $tag->nome = $this->getNome();
        return $tag;
    }

    /**
     * @param stdClass $value
     * @return TagInfo
     */
    public static function fromJson($value) {
        return (new TagInfo())
            ->setId($value->id_tag)
            ->setSlug($value->slug)
            ->setNome($value->nome);
    }

}
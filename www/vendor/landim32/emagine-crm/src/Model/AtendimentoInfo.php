<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 30/06/2017
 * Time: 09:32
 */

namespace Emagine\CRM\Model;

use Emagine\CRM\DAL\TagDAL;
use stdClass;
use Exception;
use JsonSerializable;
use Emagine\CRM\BLL\ClienteBLL;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\CRM\DAL\AndamentoDAL;
use Emagine\Login\Model\UsuarioInfo;

class AtendimentoInfo implements JsonSerializable
{
    private $id_atendimento;
    private $id_cliente;
    private $id_usuario;
    private $titulo;
    private $url;
    private $usuario = null;
    private $cliente = null;
    private $andamentos = null;
    private $tags = null;

    /**
     * @return int
     */
    public function getId() {
        return $this->id_atendimento;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setId($value) {
        $this->id_atendimento = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdCliente() {
        return $this->id_cliente;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setIdCliente($value) {
        $this->id_cliente = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdUsuario() {
        return $this->id_usuario;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setIdUsuario($value) {
        $this->id_usuario = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitulo() {
        return $this->titulo;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setTitulo($value) {
        $this->titulo = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setUrl($value) {
        $this->url = $value;
        return $this;
    }

    /**
     * @throws Exception
     * @return UsuarioInfo|null
     */
    public function getUsuario() {
        if (is_null($this->usuario) && $this->getIdUsuario() > 0) {
            $regraUsuario = new UsuarioBLL();
            $this->usuario = $regraUsuario->pegar($this->getIdUsuario());
        }
        return $this->usuario;
    }

    /**
     * @param UsuarioInfo $value
     * @return $this
     */
    public function setUsuario($value) {
        $this->usuario = $value;
        return $this;
    }

    /**
     * @throws Exception
     * @return ClienteInfo|null
     */
    public function getCliente() {
        if (is_null($this->cliente) && $this->getIdCliente() > 0) {
            $regraCliente = new ClienteBLL();
            $this->cliente = $regraCliente->pegar($this->getIdCliente());
        }
        return $this->cliente;
    }

    /**
     * @param ClienteInfo $value
     * @return $this
     */
    public function setCliente($value) {
        $this->cliente = $value;
        return $this;
    }

    /**
     * @throws Exception
     * @return AndamentoInfo[]
     */
    public function getAndamentos() {
        if (is_null($this->andamentos) && $this->getId() > 0) {
            $dal = new AndamentoDAL();
            $this->andamentos = $dal->listar($this->getId());
        }
        return $this->andamentos;
    }

    /**
     * @param AndamentoInfo[] $value
     * @return $this
     */
    public function setAndamentos($value) {
        $this->andamentos = $value;
        return $this;
    }

    public function limparAndamentos() {
        $this->andamentos = array();
    }

    /**
     * @throws Exception
     * @param AndamentoInfo $andamento
     * @return $this
     */
    public function adicionarAndamento($andamento) {
        if (is_null($this->andamentos) && $this->getId() > 0) {
            $dal = new AndamentoDAL();
            $this->andamentos = $dal->listar($this->getId());
        }
        else {
            $this->limparAndamentos();
        }
        $this->andamentos[] = $andamento;
        return $this;
    }

    /**
     * @throws Exception
     * @return int
     */
    public function getCodSituacao() {
        $andamentos = $this->getAndamentos();
        if (count($andamentos) > 0) {
            return $andamentos[0]->getCodSituacao();
        }
        return 0;
    }

    /**
     * @throws Exception
     * @return string
     */
    public function getSituacao() {
        $andamentos = $this->getAndamentos();
        if (count($andamentos) > 0) {
            return $andamentos[0]->getSituacao();
        }
        return "";
    }

    /**
     * @throws Exception
     * @return double
     */
    public function getUltimaProposta() {
        $andamentos = $this->getAndamentos();
        if (count($andamentos) > 0) {
            return $andamentos[0]->getValorProposta();
        }
        return 0;
    }

    /**
     * @throws Exception
     * @return string
     */
    public function getUltimaPropostaStr() {
        $andamentos = $this->getAndamentos();
        if (count($andamentos) > 0) {
            return $andamentos[0]->getValorPropostaStr();
        }
        return "";
    }

    /**
     * @throws Exception
     * @return string
     */
    public function getUltimaAlteracao() {
        $andamentos = $this->getAndamentos();
        if (count($andamentos) > 0) {
            return $andamentos[0]->getDataInclusao();
        }
        return null;
    }

    /**
     * @throws Exception
     * @return string
     */
    public function getUltimaAlteracaoStr() {
        $andamentos = $this->getAndamentos();
        if (count($andamentos) > 0) {
            return $andamentos[0]->getDataInclusaoStr();
        }
        return null;
    }

    /**
     * @throws Exception
     * @return string
     */
    public function getSituacaoHtml() {
        $andamentos = $this->getAndamentos();
        if (count($andamentos) > 0) {
            return $andamentos[0]->getSituacaoHtml();
        }
        return "";
    }


    /**
     * @return TagInfo[]
     */
    public function getTags() {
        return $this->tags;
    }

    /**
     * @param TagInfo[] $value
     * @return $this
     */
    public function setTags($value) {
        $this->tags = $value;
        return $this;
    }

    /**
     * @throws Exception
     */
    private function atualizarTags() {
        if (is_null($this->tags)) {
            if ($this->getId() > 0) {
                $dalTag = new TagDAL();
                $this->tags = $dalTag->listarPorAtendimento($this->getId());
            }
            else {
                $this->tags = array();
            }
        }
    }

    /**
     * @throws Exception
     * @return TagInfo[]
     */
    public function listarTag() {
        $this->atualizarTags();
        return $this->tags;
    }

    /**
     * @throws Exception
     * @return string
     */
    public function getTagsHtml() {
        $tags = array();
        foreach ($this->listarTag() as $tag) {
            $tags[] = "<span class='badge badge-default'>" . $tag->getNome() . "</span>";
        }
        return implode(' ', $tags);
    }

    /**
     * @throws Exception
     * @param TagInfo $value
     * @return $this
     */
    public function adicionarTag($value) {
        $this->atualizarTags();
        $this->tags[] = $value;
        return $this;
    }

    public function limparTags() {
        $this->tags = array();
    }

    /**
     * @throws Exception
     * @param stdClass $value
     * @return AtendimentoInfo
     */
    public static function fromJson($value) {
        $atendimento = new AtendimentoInfo();
        $atendimento->setId($value->id_atendimento);
        $atendimento->setIdCliente($value->id_cliente);
        $atendimento->setIdUsuario($value->id_usuario);
        $atendimento->setTitulo($value->titulo);
        $atendimento->setUrl($value->url);
        if (isset($value->usuario)) {
            $atendimento->setUsuario(UsuarioInfo::fromJson($value->usuario));
        }
        if (isset($value->cliente)) {
            $atendimento->setCliente(ClienteInfo::fromJson($value->cliente));
        }
        if (isset($value->andamentos)) {
            $atendimento->limparAndamentos();
            foreach ($value->andamentos as $andamento) {
                $atendimento->adicionarAndamento(AndamentoInfo::fromJson($andamento));
            }
        }
        if (isset($value->tags)) {
            $atendimento->limparTags();
            foreach ($value->tags as $tag) {
                $atendimento->adicionarTag(TagInfo::fromJson($tag));
            }
        }
        return $atendimento;
    }

    /**
     * @throws Exception
     * @return stdClass
     */
    public function JsonSerialize() {
        $atendimento = new stdClass();
        $atendimento->id_atendimento = $this->getId();
        $atendimento->id_cliente = $this->getIdCliente();
        $atendimento->id_usuario = $this->getIdUsuario();
        $atendimento->titulo = $this->getTitulo();
        $atendimento->ultima_alteracao = $this->getUltimaAlteracao();
        $atendimento->url = $this->getUrl();
        if (!is_null($this->getUsuario())) {
            $atendimento->usuario = $this->getUsuario()->JsonSerialize();
        }
        if (!is_null($this->getCliente())) {
            $atendimento->cliente = $this->getCliente()->JsonSerialize();
        }
        $atendimento->andamentos = array();
        foreach ($this->getAndamentos() as $andamento) {
            $atendimento->andamentos[] = $andamento->JsonSerialize();
        }
        foreach ($this->listarTag() as $tag) {
            $atendimento->tags[] = $tag->jsonSerialize();
        }
        return $atendimento;
    }

}
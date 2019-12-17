<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 30/06/2017
 * Time: 10:04
 */

namespace Emagine\CRM\Model;

use Emagine\Base\Utils\DateTimeUtils;
use stdClass;
use Exception;
use JsonSerializable;
use Emagine\CRM\BLL\AtendimentoBLL;
use Emagine\CRM\BLL\ClienteBLL;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\Model\UsuarioInfo;

class AndamentoInfo implements JsonSerializable
{
    const ATIVO = 1;
    const PROPOSTA = 2;
    const INATIVO = 3;

    private $id_andamento;
    private $id_atendimento;
    private $id_cliente;
    private $id_usuario;
    private $data_inclusao;
    private $cod_situacao;
    private $valor_proposta;
    private $mensagem;
    private $usuario = null;
    private $cliente = null;

    /**
     * @return int
     */
    public function getId() {
        return $this->id_andamento;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setId($value) {
        $this->id_andamento = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdAtendimento() {
        return $this->id_atendimento;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setIdAtendimento($value) {
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
     * @return int
     */
    public function getCodSituacao() {
        return $this->cod_situacao;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setCodSituacao($value) {
        $this->cod_situacao = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataInclusao() {
        return $this->data_inclusao;
    }

    /**
     * @return string
     */
    public function getDataInclusaoStr() {
        return DateTimeUtils::humanizeDateDiff(time(), strtotime($this->getDataInclusao()));
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setDataInclusao($value) {
        $this->data_inclusao = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getValorProposta() {
        return $this->valor_proposta;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setValorProposta($value) {
        $this->valor_proposta = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getMensagem() {
        return $this->mensagem;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setMensagem($value) {
        $this->mensagem = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getSituacao() {
        $regraAtendimento = new AtendimentoBLL();
        $situacoes = $regraAtendimento->listarSituacao();
        return $situacoes[$this->getCodSituacao()];
    }

    /**
     * @return string
     */
    public function getValorPropostaStr() {
        if ($this->valor_proposta > 0) {
            return "R$ " . number_format($this->valor_proposta, 2, ",", ".");
        }
        else {
            return "";
        }
    }

    /**
     * @return string
     */
    public function getSituacaoHtml() {
        $str = "";
        if ($this->getCodSituacao() == AndamentoInfo::ATIVO) {
            $str .= "<span class=\"label label-info\">";
        }
        elseif ($this->getCodSituacao() == AndamentoInfo::INATIVO) {
            $str .= "<span class=\"label label-danger\">";
        }
        elseif ($this->getCodSituacao() == AndamentoInfo::PROPOSTA) {
            $str .= "<span class=\"label label-success\">";
        }
        else {
            $str .= "<span class=\"label label-default\">";
        }
        $str .= $this->getSituacao();
        $str .= "</label>";
        return $str;
    }

    /**
     * @param stdClass $value
     * @return AndamentoInfo
     */
    public static function fromJson($value) {
        $andamento = new AndamentoInfo();
        $andamento->setId($value->id_andamento);
        $andamento->setIdAtendimento($value->id_atendimento);
        $andamento->setIdCliente($value->id_cliente);
        $andamento->setIdUsuario($value->id_usuario);
        $andamento->setDataInclusao($value->data_inclusao);
        $andamento->setCodSituacao($value->cod_situacao);
        $andamento->setValorProposta($value->valor_proposta);
        $andamento->setMensagem($value->mensagem);
        return $andamento;
    }

    /**
     * @return stdClass
     */
    public function JsonSerialize() {
        $andamento = new stdClass();
        $andamento->id_andamento = $this->getId();
        $andamento->id_atendimento = $this->getIdAtendimento();
        $andamento->id_cliente = $this->getIdCliente();
        $andamento->id_usuario = $this->getIdUsuario();
        $andamento->data_inclusao = $this->getDataInclusao();
        $andamento->cod_situacao = $this->getCodSituacao();
        $andamento->valor_proposta = $this->getValorProposta();
        $andamento->mensagem = $this->getMensagem();
        return $andamento;
    }
}
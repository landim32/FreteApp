<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 24/06/2017
 * Time: 10:37
 */

namespace Emagine\CRM\Model;

use Emagine\Base\BLL\ValidaCpfCnpj;
use Emagine\Base\BLL\ValidaTelefone;
use Emagine\Base\Utils\StringUtils;
use Emagine\Endereco\Model\EnderecoInfo;
use stdClass;
use Exception;
use JsonSerializable;
use Emagine\CRM\DAL\EnderecoDAL;
use Emagine\CRM\BLL\ClienteBLL;
use Emagine\CRM\DAL\ClienteOpcaoDAL;
use Emagine\CRM\DAL\TagDAL;

class ClienteInfo implements JsonSerializable
{
    const ATIVO = 1;
    const INATIVO = 2;

    private $id_cliente;
    private $id_usuario;
    private $data_inclusao;
    private $ultima_alteracao;
    private $nome;
    private $empresa;
    private $telefone1;
    private $telefone2;
    private $email;
    private $rg;
    private $cpf_cnpj;
    private $nacionalidade;
    private $estado_civil;
    private $profissao;
    private $site_url;
    private $cod_situacao;
    private $quantidade_enviado;
    private $tags = null;
    private $opcoes = null;
    private $enderecos = null;

    /**
     * @return int
     */
    public function getId() {
        return $this->id_cliente;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setId($value) {
        $this->id_cliente = $value;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getIdUsuario() {
        return $this->id_usuario;
    }

    /**
     * @param int|null $value
     * @return $this
     */
    public function setIdUsuario($value) {
        $this->id_usuario = $value;
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
        return date("d/m/Y H:i", strtotime($this->getDataInclusao()));
    }

    /**
     * @return string
     */
    public function getUltimaAlteracao() {
        return $this->ultima_alteracao;
    }

    /**
     * @return string
     */
    public function getUltimaAlteracaoStr() {
        return date("d/m/Y H:i", strtotime($this->getUltimaAlteracao()));
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
    public function getEmpresa() {
        return $this->empresa;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setEmpresa($value) {
        $this->empresa = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getTelefone1() {
        return $this->telefone1;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setTelefone1($value) {
        $this->telefone1 = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getTelefone1Str() {
        if (!StringUtils::isNullOrEmpty($this->getTelefone1())) {
            $validaTelefone = new ValidaTelefone($this->getTelefone1());
            return $validaTelefone->formatar();
        }
        return "";
    }

    /**
     * @return string
     */
    public function getTelefone2() {
        return $this->telefone2;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setTelefone2($value) {
        $this->telefone2 = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getTelefone2Str() {
        if (!StringUtils::isNullOrEmpty($this->getTelefone2())) {
            $validaTelefone = new ValidaTelefone($this->getTelefone2());
            return $validaTelefone->formatar();
        }
        return "";
    }

    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @deprecated Use getEmail
     * @return string
     */
    public function getEmail1() {
        return $this->getEmail();
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setEmail($value) {
        $this->email = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getRg() {
        return $this->rg;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setRg($value) {
        $this->rg = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getCpfCnpj() {
        return $this->cpf_cnpj;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setCpfCnpj($value) {
        $this->cpf_cnpj = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getCpfCnpjStr() {
        if (!StringUtils::isNullOrEmpty($this->getCpfCnpj())) {
            $validaCpfCnpj = new ValidaCpfCnpj($this->getCpfCnpj());
            return $validaCpfCnpj->formatar();
        }
        return "";
    }

    /**
     * @return bool
     */
    public function getPJ() {
        if (!StringUtils::isNullOrEmpty($this->getCpfCnpj())) {
            $validaCpfCnpj = new ValidaCpfCnpj($this->getCpfCnpj());
            return $validaCpfCnpj->validarCnpj();
        }
        return false;
    }

    /**
     * @return string
     */
    public function getNacionalidade() {
        return $this->nacionalidade;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setNacionalidade($value) {
        $this->nacionalidade = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getEstadoCivil() {
        return $this->estado_civil;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setEstadoCivil($value) {
        $this->estado_civil = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getProfissao() {
        return $this->profissao;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setProfissao($value) {
        $this->profissao = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getSiteUrl() {
        return $this->site_url;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setSiteUrl($value) {
        $this->site_url = $value;
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
     * @return int
     */
    public function getQuantidadeEnviado() {
        return $this->quantidade_enviado;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setQuantidadeEnviado($value) {
        $this->quantidade_enviado = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getSituacao() {
        $regraCliente = new ClienteBLL();
        $situacoes = $regraCliente->listarSituacao();
        return $situacoes[ $this->getCodSituacao() ];
    }

    /**
     * @return string
     */
    public function getSituacaoHtml() {
        $situacao = '';
        switch ($this->getCodSituacao()) {
            case ClienteInfo::ATIVO:
                $situacao = "<span class='label label-success'>" . $this->getSituacao() . "</span>";
                break;
            case ClienteInfo::INATIVO:
                $situacao = "<span class='label label-danger'>" . $this->getSituacao() . "</span>";
                break;
        }
        return $situacao;
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
                $this->tags = $dalTag->listarPorCliente($this->getId());
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
     */
    private function atualizarOpcoes() {
        if (is_null($this->opcoes)) {
            if ($this->getId() > 0) {
                $dalOpcao = new ClienteOpcaoDAL();
                $this->opcoes = $dalOpcao->listar($this->getId());
            }
            else {
                $this->opcoes = array();
            }
        }
    }

    /**
     * @throws Exception
     * @return array<string, string>
     */
    public function listarOpcao() {
        $this->atualizarOpcoes();
        return $this->opcoes;
    }

    public function limparOpcao() {
        $this->opcoes = array();
    }

    /**
     * @throws Exception
     * @param string $chave
     * @param string $valor
     * @return $this
     */
    public function adicionarOpcao($chave, $valor) {
        $this->atualizarOpcoes();
        $this->opcoes[$chave] = $valor;
        return $this;
    }

    /**
     * Limpa todos os Grupos de Usuários relacionados com Usuários.
     */
    public function limparEndereco() {
        $this->enderecos = array();
    }

    /**
     * @throws Exception
     * @return ClienteEnderecoInfo[]
     */
    public function listarEndereco() {
        if (is_null($this->enderecos)) {
            if ($this->getId() > 0) {
                $dal = new EnderecoDAL();
                $this->enderecos = $dal->listar($this->getId());
            }
            else {
                $this->limparEndereco();
            }
        }
        return $this->enderecos;
    }

    /**
     * @throws Exception
     * @param EnderecoInfo $endereco
     * @return $this
     */
    public function adicionarEndereco($endereco) {
        $this->listarEndereco();
        $this->enderecos[] = $endereco;
        return $this;
    }

    /**
     * @throws Exception
     * @return stdClass
     */
    public function jsonSerialize() {
        $cliente = new stdClass();
        $cliente->id_cliente = $this->getId();
        $cliente->id_usuario = $this->getIdUsuario();
        $cliente->nome = $this->getNome();
        $cliente->empresa = $this->getEmpresa();
        $cliente->telefone1 = $this->getTelefone1();
        $cliente->telefone2 = $this->getTelefone2();
        $cliente->email = $this->getEmail();
        $cliente->rg = $this->getRg();
        $cliente->cpf_cnpj = $this->getCpfCnpj();
        $cliente->nacionalidade = $this->getNacionalidade();
        $cliente->estado_civil = $this->getEstadoCivil();
        $cliente->profissao = $this->getProfissao();
        $cliente->site_url = $this->getSiteUrl();
        $cliente->cod_situacao = $this->getCodSituacao();
        foreach ($this->listarEndereco() as $endereco) {
            $cliente->enderecos[] = $endereco->jsonSerialize();
        }
        foreach ($this->listarTag() as $tag) {
            $cliente->tags[] = $tag->jsonSerialize();
        }
        foreach ($this->listarOpcao() as $chave => $valor) {
            $cliente->opcoes[$chave] = $valor;
        }
        return $cliente;
    }

    /**
     * @throws Exception
     * @param stdClass $value
     * @return ClienteInfo
     */
    public static function fromJson($value) {
        $cliente = new ClienteInfo();
        $cliente->setId($value->id_cliente);
        $cliente->setIdUsuario($value->id_usuario);
        $cliente->setNome($value->nome);
        $cliente->setEmpresa($value->empresa);
        $cliente->setTelefone1($value->telefone1);
        $cliente->setTelefone2($value->telefone2);
        $cliente->setEmail($value->email);
        $cliente->setRg($value->rg);
        $cliente->setCpfCnpj($value->cpf_cnpj);
        $cliente->setNacionalidade($value->nacionalidade);
        $cliente->setEstadoCivil($value->estado_civil);
        $cliente->setProfissao($value->profissao);
        $cliente->setSiteUrl($value->site_url);
        $cliente->setCodSituacao($value->cod_situacao);
        if (isset($value->enderecos)) {
            $cliente->limparEndereco();
            foreach ($value->enderecos as $endereco) {
                $cliente->adicionarEndereco(EnderecoInfo::fromJson($endereco));
            }
        }
        if (isset($value->tags)) {
            $cliente->limparTags();
            foreach ($value->tags as $tag) {
                $cliente->adicionarTag(TagInfo::fromJson($tag));
            }
        }
        if (isset($value->opcoes)) {
            $cliente->limparOpcao();
            foreach ($value->opcoes as $chave => $valor) {
                $cliente->adicionarOpcao($chave, $valor);
            }
        }
        return $cliente;
    }
}
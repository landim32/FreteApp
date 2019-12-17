<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 20/06/2017
 * Time: 08:51
 */

namespace Emagine\CRM\BLL;

use Emagine\CRM\DAL\EnderecoDAL;
use stdClass;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\CRM\DAL\ClienteDAL;
use Emagine\CRM\DAL\ClienteOpcaoDAL;
use Emagine\CRM\Model\TagInfo;
use Emagine\CRM\Model\ClienteInfo;
use Emagine\CRM\Model\ClienteRetornoInfo;
use Emagine\CRM\Model\ClienteSituacaoInfo;
use Emagine\CRM\Model\MigracaoZendeskInfo;

class ClienteBLL
{
    /**
     * @return array<int, string>
     */
    public function listarSituacao() {
        return array(
            ClienteInfo::ATIVO => "Ativo",
            ClienteInfo::INATIVO => "Inativo"
        );
    }

    /**
     * @throws Exception
     * @param int $id_usuario
     * @param int $codSituacao
     * @return ClienteInfo[]
     */
    public function listarPorUsuario($id_usuario, $codSituacao = 0) {
        $dal = new ClienteDAL();
        return $dal->listar($id_usuario, $codSituacao, ClienteDAL::ORDENAR_DATA);
    }

    /**
     * @throws Exception
     * @param int $codSituacao
     * @return ClienteInfo[]
     */
    public function listar($codSituacao = 0) {
        $dal = new ClienteDAL();
        return $dal->listar(0, $codSituacao, ClienteDAL::ORDENAR_DATA);
    }

    /**
     * @throws Exception
     * @param int $limite
     * @return ClienteInfo[]
     */
    public function listarAleatorio($limite = 0) {
        $dal = new ClienteDAL();
        return $dal->listar(0, ClienteInfo::ATIVO, ClienteDAL::ORDENAR_RAND, $limite);
    }

    /**
     * @throws Exception
     * @param int $cod_situacao
     * @param string $tag_slug
     * @param int $pg Pagina atual
     * @param int $numpg Quantidade de itens visualizados
     * @return ClienteRetornoInfo
     */
    public function listarPaginado($cod_situacao = 0, $tag_slug = "", $pg = 1, $numpg = 10) {
        $dal = new ClienteDAL();
        return $dal->listarPaginado($cod_situacao, '', $tag_slug, $pg, $numpg);
    }

    /**
     * @throws Exception
     * @param string $palavra_chave
     * @param int $cod_situacao
     * @param int $pg
     * @param int $numpg
     * @return ClienteRetornoInfo
     */
    public function buscaPaginado($palavra_chave, $cod_situacao = 0, $pg = 1, $numpg = 10) {
        $dal = new ClienteDAL();
        return $dal->listarPaginado($cod_situacao, $palavra_chave, '', $pg, $numpg);
    }

    /**
     * @throws Exception
     * @param int $id_tag
     * @param int $codSituacao
     * @return ClienteInfo[]
     */
    public function listarPorTag($id_tag, $codSituacao = 0) {
        $dal = new ClienteDAL();
        return $dal->listarPorTag($id_tag, $codSituacao);
    }

    /**
     * @throws Exception
     * @param int $id_cliente
     * @return ClienteInfo
     */
    public function pegar($id_cliente) {
        $dal = new ClienteDAL();
        return $dal->pegar($id_cliente);
    }

    /**
     * @throws Exception
     * @param string $email
     * @return ClienteInfo
     */
    public function pegarPorEmail($email) {
        $dal = new ClienteDAL();
        return $dal->pegarPorEmail($email);
    }

    /**
     * @param ClienteInfo $cliente
     * @throws Exception
     */
    private function validar(&$cliente) {
        if (is_null($cliente)) {
            throw new Exception("Cliente não informada.");
        }
        if (isNullOrEmpty($cliente->getNome())) {
            throw new Exception("Preencha o nome.");
        }
        $nome = trim( strip_tags( trim( $cliente->getNome() ) ) );
        $nome = substr($nome, 0, 60);
        $cliente->setNome( $nome );

        if (!($cliente->getCodSituacao() > 0)) {
            $cliente->setCodSituacao(ClienteInfo::ATIVO);
        }
    }

    /**
     * @throws Exception
     * @param int $id_cliente
     * @param int $id_tag
     */
    private function inserirTag($id_cliente, $id_tag) {
        $dal = new ClienteDAL();
        if (!($dal->pegarQuantidadeTag($id_cliente, $id_tag) > 0)) {
            $dal->inserirTag($id_cliente, $id_tag);
        }
    }

    /**
     * @throws Exception
     * @param ClienteInfo $cliente
     */
    private function atualizarTag($cliente) {
        $dal = new ClienteDAL();
        $regraTag = new TagBLL();
        $dal->limparTags($cliente->getId());
        foreach ($cliente->listarTag() as $tag) {
            if ($tag->getId() > 0) {
                $id_tag = $tag->getId();
            }
            else {
                $id_tag = $regraTag->inserirOuAlterar($tag);
            }
            $this->inserirTag($cliente->getId(), $id_tag);
        }
    }

    /**
     * @throws Exception
     * @param ClienteInfo $cliente
     */
    private function atualizarOpcao($cliente) {
        $id_cliente = $cliente->getId();
        if ($id_cliente > 0) {
            $dalOpcao = new ClienteOpcaoDAL();
            $dalOpcao->limpar($cliente->getId());
            foreach ($cliente->listarOpcao() as $chave => $valor) {
                $valorAtual = $dalOpcao->pegarValor($cliente->getId(), $chave);
                if (is_null($valorAtual)) {
                    $dalOpcao->inserir($id_cliente, $chave, $valor);
                }
            }
        }
    }

    /**
     * @throws Exception
     * @param ClienteInfo $cliente
     * @return int
     */
    public function inserirOuAlterar($cliente) {
        //var_dump($cliente->listarOpcao());
        if ($cliente->getId() > 0) {
            $this->alterar($cliente);
            $id_cliente = $cliente->getId();
        }
        else {
            $id_cliente = $this->inserir($cliente);
        }
        return $id_cliente;
    }

    /**
     * @param ClienteInfo $cliente
     * @throws Exception
     * @return int
     */
    public function inserir($cliente) {
        $this->validar($cliente);
        $id_cliente = null;
        $dal = new ClienteDAL();
        $dalEndereco = new EnderecoDAL();
        try {
            DB::beginTransaction();
            $id_cliente = $dal->inserir($cliente);
            $cliente->setId($id_cliente);
            $this->atualizarTag($cliente);
            $this->atualizarOpcao($cliente);
            foreach ($cliente->listarEndereco() as $endereco) {
                $endereco->setIdCliente($id_cliente);
                $dalEndereco->inserir($endereco);
            }
            DB::commit();
        }
        catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return $id_cliente;
    }

    /**
     * @param ClienteInfo $cliente
     * @throws Exception
     */
    public function alterar($cliente) {
        $this->validar($cliente);
        $dal = new ClienteDAL();
        $dalEndereco = new EnderecoDAL();
        try {
            DB::beginTransaction();
            $dal->alterar($cliente);
            $this->atualizarTag($cliente);
            $this->atualizarOpcao($cliente);
            $cliente->listarEndereco();
            $dalEndereco->limpar($cliente->getId());
            foreach ($cliente->listarEndereco() as $endereco) {
                $endereco->setIdCliente($cliente->getId());
                $dalEndereco->inserir($endereco);
            }
            DB::commit();
        }
        catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @throws Exception
     * @param int $id_cliente
     */
    public function excluir($id_cliente) {
        $dal = new ClienteDAL();
        $dalEndereco = new EnderecoDAL();
        $dalOpcao = new ClienteOpcaoDAL();
        try {
            DB::beginTransaction();
            $dal->limparTags($id_cliente);
            $dalOpcao->limpar($id_cliente);
            $dalEndereco->limpar($id_cliente);
            $dal->excluir($id_cliente);
            DB::commit();
        }
        catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @throws Exception
     * @param stdClass $user
     * @return bool
     */
    public function migrarUsuarioZendesk($user) {
        $retorno = false;
        if (isset($user->email)) {
            $cliente = $this->pegarPorEmail($user->email);
            if (is_null($cliente)) {
                $cliente = new ClienteInfo();
                $cliente->setNome($user->name);
                $cliente->setEmail1($user->email);
                $cliente->setTelefone1($user->phone);
            }
            $tag = new TagInfo();
            $tag->setNome("Zendesk");
            $cliente->adicionarTag($tag);

            if (isset($user->tags) && is_array($user->tags)) {
                foreach ($user->tags as $tagNome) {
                    $tag = new TagInfo();
                    $tag->setNome($tagNome);
                    $cliente->adicionarTag($tag);
                }
            }
            if (isset($user->id)) {
                $cliente->adicionarOpcao("zendesk_id", $user->id);
            }
            if (isset($user->created_at)) {
                $cliente->adicionarOpcao("zendesk_created_at", $user->created_at);
            }
            if (isset($user->updated_at)) {
                $cliente->adicionarOpcao("zendesk_updated_at", $user->updated_at);
            }
            if (isset($user->time_zone)) {
                $cliente->adicionarOpcao("zendesk_time_zone", $user->time_zone);
            }
            //var_dump($cliente->listarOpcao());
            $this->inserirOuAlterar($cliente);
            $retorno = true;
        }
        return $retorno;
    }

    /**
     * @throws Exception
     * @param int $pg
     * @return MigracaoZendeskInfo
     */
    public function migrarZendesk($pg = 1) {
        $regraZendesk = new ZendeskBLL();
        $data = $regraZendesk->listarUsuario($pg);
        $retorno = new MigracaoZendeskInfo();
        if (isset($data->count)) {
            $retorno->setQuantidadeTotal($data->count);
        }
        if (isset($data->users)) {
            $retorno->setQuantidadeLista(count($data->users));
            $c = 0;
            foreach ($data->users as $usuario) {
                try {
                    if ($this->migrarUsuarioZendesk($usuario)) {
                        $c++;
                    }
                } catch (Exception $e) {
                    $retorno->adicionarLog( $e->getMessage() );
                }
            }
            $retorno->setQuantidadeMigrado($c);
        }
        return $retorno;
    }

    /**
     * @throws Exception
     * @return ClienteSituacaoInfo[]
     */
    public function quantidadePorSituacao() {
        $dal = new ClienteDAL();
        $situacoes = $this->listarSituacao();
        $quantidades = $dal->quantidadePorSituacao();
        $retorno = array();
        foreach ($situacoes as $cod_situacao => $nome) {
            $quantidade = $quantidades[$cod_situacao];
            if (!($quantidade > 0)) {
                $quantidade = 0;
            }
            $situacao = new ClienteSituacaoInfo();
            $situacao->setCodSituacao($cod_situacao);
            $situacao->setNome($nome);
            $situacao->setQuantidade($quantidade);
            $retorno[$cod_situacao] = $situacao;
        }
        return $retorno;
    }

    /**
     * @throws Exception
     * @param int $id_cliente
     */
    public function marcarEnviado($id_cliente) {
        $dal = new ClienteDAL();
        $dal->marcarEnviado($id_cliente);
    }
}
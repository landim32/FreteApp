<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 30/06/2017
 * Time: 10:29
 */

namespace Emagine\CRM\BLL;

use Emagine\Base\Utils\StringUtils;
use Exception;
use Emagine\CRM\DAL\AndamentoDAL;
use Emagine\CRM\DAL\AtendimentoDAL;
use Emagine\CRM\Model\AtendimentoInfo;
use Emagine\CRM\Model\AndamentoInfo;
use Emagine\CRM\Model\AtendimentoRetornoInfo;
use Landim32\EasyDB\DB;

class AtendimentoBLL
{
    /**
     * @return array<int,string>
     */
    public function listarSituacao() {
        return array(
            AndamentoInfo::ATIVO => "Ativo",
            AndamentoInfo::PROPOSTA => "Proposta",
            AndamentoInfo::INATIVO => "Inativo"
        );
    }

    /**
     * @throws Exception
     * @param int $cod_situacao
     * @param int $pg
     * @param int $numpg
     * @return AtendimentoRetornoInfo
     */
    public function listarPaginado($cod_situacao = 0, $pg = 1, $numpg = 10) {
        $dal = new AtendimentoDAL();
        return $dal->listarPaginado($cod_situacao, null, null, "", $pg, $numpg);
    }

    /**
     * @throws Exception
     * @param string $palavra_chave
     * @param int $cod_situacao
     * @param int $pg
     * @param int $numpg
     * @return AtendimentoRetornoInfo
     */
    public function buscarPaginado($palavra_chave, $cod_situacao = 0, $pg = 1, $numpg = 10) {
        $dal = new AtendimentoDAL();
        return $dal->listarPaginado($cod_situacao, null, null, $palavra_chave, $pg, $numpg);
    }

    /**
     * @throws Exception
     * @param int|null $id_usuario
     * @param int $cod_situacao
     * @param int $pg
     * @param int $numpg
     * @return AtendimentoRetornoInfo
     */
    public function listarPaginadoPorUsuario($id_usuario = null, $cod_situacao = 0, $pg = 1, $numpg = 10) {
        $dal = new AtendimentoDAL();
        return $dal->listarPaginado($cod_situacao, $id_usuario, null, "", $pg, $numpg);
    }

    /**
     * @throws Exception
     * @param int|null $id_cliente
     * @param int $cod_situacao
     * @param int $pg
     * @param int $numpg
     * @return AtendimentoRetornoInfo
     */
    public function listarPaginadoPorCliente($id_cliente = null, $cod_situacao = 0, $pg = 1, $numpg = 10) {
        $dal = new AtendimentoDAL();
        return $dal->listarPaginado($cod_situacao, null, $id_cliente, "", $pg, $numpg);
    }

    /**
     * @throws Exception
     * @param int $cod_situacao
     * @return AtendimentoInfo[]
     */
    public function listar($cod_situacao = null) {
        $dal = new AtendimentoDAL();
        return $dal->listar(null, null, $cod_situacao);
    }

    /**
     * @throws Exception
     * @param int|null $cod_situacao
     * @return AtendimentoInfo[]
     */
    public function listarPorSituacao($cod_situacao = null) {
        $dal = new AtendimentoDAL();
        return $dal->listar(null, null, $cod_situacao);
    }

    /**
     * @throws Exception
     * @param int $id_cliente
     * @param int|null $cod_situacao
     * @return AtendimentoInfo[]
     */
    public function listarPorCliente($id_cliente, $cod_situacao = null) {
        $dal = new AtendimentoDAL();
        return $dal->listar($id_cliente, null, $cod_situacao);
    }

    /**
     * @throws Exception
     * @param int $id_usuario
     * @param int|null $cod_situacao
     * @return AtendimentoInfo[]
     */
    public function listarPorUsuario($id_usuario, $cod_situacao = null) {
        $dal = new AtendimentoDAL();
        return $dal->listar(null, $id_usuario, $cod_situacao);
    }

    /**
     * @throws Exception
     * @return array<int,int>
     */
    public function quantidadePorSituacao() {
        $dal = new AtendimentoDAL();
        return $dal->quantidadePorSituacao();
    }

    /**
     * @throws Exception
     * @param int $id_atendimento
     * @return AtendimentoInfo
     */
    public function pegar($id_atendimento) {
        $dal = new AtendimentoDAL();
        return $dal->pegar($id_atendimento);
    }

    /**
     * @throws Exception
     * @param string $url
     * @return AtendimentoInfo
     */
    public function pegarPorUrl($url) {
        $dal = new AtendimentoDAL();
        return $dal->pegarPorUrl($url);
    }

    /**
     * @param AndamentoInfo $andamento
     * @throws Exception
     */
    private function validarAndamento(&$andamento) {
        if ($andamento->getIdUsuario() > 0 && $andamento->getIdCliente() > 0) {
            throw new Exception("Andamento deve ter usuário ou cliente, não os dois.");
        }
        if (!($andamento->getIdUsuario() > 0) && !($andamento->getIdCliente() > 0)) {
            throw new Exception("Nem usuário, nem cliente foi informado para o andamento.");
        }
        if (isNullOrEmpty($andamento->getMensagem())) {
            throw new Exception("Preencha a mensagem.");
        }
        if (isNullOrEmpty($andamento->getDataInclusao())) {
            $andamento->setDataInclusao(date("Y-m-d H:i:s"));
        }
        if (!($andamento->getCodSituacao() > 0)) {
            $andamento->setCodSituacao(AndamentoInfo::ATIVO);
        }
    }

    /**
     * @param AtendimentoInfo $atendimento
     * @throws Exception
     */
    private function validar(&$atendimento) {
        /*
        if (!($atendimento->getIdUsuario() > 0)) {
            throw new Exception("Selecione o usuário.");
        }
        */
        if (!($atendimento->getIdCliente() > 0)) {
            throw new Exception("Selecione o cliente.");
        }
        if (StringUtils::isNullOrEmpty($atendimento->getTitulo())) {
            throw new Exception("Preencha o título.");
        }
        if (count($atendimento->getAndamentos()) == 0) {
            throw new Exception("O atendimento precisa ter pelomenos um andamento.");
        }
        foreach ($atendimento->getAndamentos() as $andamento) {
            $this->validarAndamento($andamento);
        }
        if (strlen($atendimento->getTitulo()) > 140) {
            $atendimento->setTitulo(substr($atendimento->getTitulo(), 0, 140));
        }
    }

    /**
     * @throws Exception
     * @param int $id_cliente
     * @param int $id_tag
     */
    private function inserirTag($id_cliente, $id_tag) {
        $dal = new AtendimentoDAL();
        if (!($dal->pegarQuantidadeTag($id_cliente, $id_tag) > 0)) {
            $dal->inserirTag($id_cliente, $id_tag);
        }
    }

    /**
     * @throws Exception
     * @param AtendimentoInfo $atendimento
     */
    private function atualizarTag($atendimento) {
        $dal = new AtendimentoDAL();
        $regraTag = new TagBLL();
        $dal->limparTags($atendimento->getId());
        foreach ($atendimento->listarTag() as $tag) {
            if ($tag->getId() > 0) {
                $id_tag = $tag->getId();
            }
            else {
                $id_tag = $regraTag->inserirOuAlterar($tag);
            }
            $this->inserirTag($atendimento->getId(), $id_tag);
        }
    }

    /**
     * @param AtendimentoInfo $atendimento
     * @throws Exception
     * @return int
     */
    public function inserir($atendimento) {
        $this->validar($atendimento);

        $dalAtendimento = new AtendimentoDAL();
        $dalAndamento = new AndamentoDAL();
        try {
            DB::beginTransaction();
            $id_atendimento = $dalAtendimento->inserir($atendimento);
            $atendimento->setId($id_atendimento);
            $this->atualizarTag($atendimento);
            foreach ($atendimento->getAndamentos() as $andamento) {
                $andamento->setIdAtendimento($id_atendimento);
                $dalAndamento->inserir($andamento);
            }
            DB::commit();
        }
        catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return $id_atendimento;
    }

    /**
     * @param AtendimentoInfo $atendimento
     * @throws Exception
     */
    public function alterar($atendimento) {
        $this->validar($atendimento);

        $dalAtendimento = new AtendimentoDAL();
        $dalAndamento = new AndamentoDAL();
        try {
            DB::beginTransaction();
            $dalAtendimento->alterar($atendimento);
            $this->atualizarTag($atendimento);
            $idValido = array();
            foreach ($atendimento->getAndamentos() as $andamento) {
                if ($andamento->getId() > 0) {
                    $dalAndamento->alterar($andamento);
                    $idValido[] = $andamento->getId();
                }
                else {
                    $idValido[] = $dalAndamento->inserir($andamento);
                }
            }
            $dalAndamento->limparExcerto($idValido);
            DB::commit();
        }
        catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param int $id_atendimento
     * @throws Exception
     */
    public function excluir($id_atendimento) {
        $dalAtendimento = new AtendimentoDAL();
        $dalAndamento = new AndamentoDAL();
        try {
            DB::beginTransaction();
            $dalAndamento->limpar($id_atendimento);
            $dalAtendimento->excluir($id_atendimento);
            DB::commit();
        }
        catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
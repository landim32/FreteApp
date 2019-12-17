<?php
namespace Emagine\Frete\IBLL;

use Emagine\Frete\Model\AceiteEnvioInfo;
use Emagine\Frete\Model\AceiteRetornoInfo;
use Emagine\Frete\Model\FreteHistoricoInfo;
use Emagine\Frete\Model\FreteRetornoInfo;
use Emagine\Frete\Model\FreteInfo;

interface IFreteBLL
{
    /**
     * @return string[]
     */
    public function listarSituacao();

    /**
     * @param int $id_usuario
     * @param int $id_motorista
     * @param int $cod_situacao
     * @return FreteInfo[]
     */
    public function listar($id_usuario = 0, $id_motorista = 0, $cod_situacao = 0);

    /**
     * @param int $id_usuario
     * @return FreteInfo[]
     */
    public function listarDisponivel($id_usuario);

    /**
     * @param int $id_usuario
     * @return FreteInfo[]
     */
    public function listarPorUsuario($id_usuario);

    /**
     * @param int $id_usuario
     * @return FreteInfo[]
     */
    public function listarPorCliente($id_usuario);

    /**
     * @param int $id_usuario
     * @return FreteInfo[]
     */
    public function listarPorMotorista($id_usuario);

    /**
     * @param int $id_frete
     * @return FreteInfo
     */
    public function pegar($id_frete);

    /**
     * @param int $id_motorista
     * @param bool $atualizado
     * @return FreteInfo
     */
    public function pegarAbertoPorMotorista($id_motorista, $atualizado = true);

    /**
     * @param FreteInfo $frete
     * @return int
     */
    public function inserir($frete);

    /**
     * @param FreteInfo $frete
     */
    public function alterar($frete);

    /**
     * @param int $id_frete
     * @param bool $transaction
     */
    public function excluir($id_frete, $transaction = true);

    /**
     * @param int $id_usuario
     */
    public function limparPorIdUsuario($id_usuario);

    /**
     * @param int $id_motorista
     */
    public function limparPorIdMotorista($id_motorista);

    /**
     * @param FreteInfo $frete
     * @return float
     */
    public function calcularValor($frete);

    /**
     * @param int $id_frete
     * @param int $id_usuario
     * @param bool $aceite
     */
    public function adicionarAceite($id_frete, $id_usuario, $aceite);

    /**
     * @param AceiteEnvioInfo $aceite
     * @return AceiteRetornoInfo
     */
    public function aceitar($aceite);

    /**
     * @param int $id_frete
     * @return FreteRetornoInfo
     */
    public function atualizar($id_frete);

    /**
     * @param FreteInfo $frete
     * @param int $largura
     * @param int $altura
     * @return string
     */
    public function gerarMapaURL(FreteInfo $frete, $largura = 640, $altura = 360);
}
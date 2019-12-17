<?php

namespace Emagine\Frete\IDAL;

use Exception;
use Emagine\Frete\Model\FreteInfo;

interface IFreteDAL {

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
     * @throws Exception
     */
    public function listarDisponivel($id_usuario = 0);

	/**
	 * @param int $id_frete
	 * @return FreteInfo
	 */
	public function pegar($id_frete);

    /**
     * @param int $id_motorista
     * @return FreteInfo
     */
    public function pegarAbertoPorMotorista($id_motorista);

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
	 */
	public function excluir($id_frete);

	/**
	 * @param int $id_usuario
	 */
	public function limparPorUsuario($id_usuario);

	/**
	 * @param int $id_motorista
	 */
	public function limparPorMotorista($id_motorista);

    /**
     * @param int $id_frete
     * @param int $id_usuario
     * @return int
     */
	public function pegarQuantidadeAceite($id_frete, $id_usuario);

    /**
     * @param int $id_frete
     * @param int $id_usuario
     * @param bool $aceite
     */
    public function alterarAceite($id_frete, $id_usuario, $aceite);

    /**
     * @param int $id_frete
     * @param int $id_usuario
     * @param bool $aceite
     */
	public function inserirAceite($id_frete, $id_usuario, $aceite);

    /**
     * @param $id_frete
     */
    public function limparAceite($id_frete);

    /**
     * @param int $id_motorista
     * @return float
     */
    public function pegarNotaMotorista($id_motorista);

    /**
     * @param int $id_motorista
     * @return float
     */
    public function pegarNotaCliente($id_motorista);

    /**
     * @param int $id_frete
     */
    public function atualizarDataRetirada($id_frete);

    /**
     * @param int $id_frete
     */
    public function atualizarDataEntrega($id_frete);
}


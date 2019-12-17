<?php
namespace Emagine\Frete\IDAL;

use Emagine\Frete\Model\MotoristaEnvioInfo;
use Emagine\Frete\Model\MotoristaInfo;

interface IMotoristaDAL {

	/**
	 * @return MotoristaInfo[]
	 */
	public function listar();

	/**
	 * @param int $id_usuario
	 * @return MotoristaInfo
	 */
	public function pegar($id_usuario);

	/**
	 * @param MotoristaInfo $motorista
     * @return int
	 */
	public function inserir($motorista);

	/**
	 * @param MotoristaInfo $motorista
	 */
	public function alterar($motorista);

    /**
     * @param MotoristaEnvioInfo $motorista
     */
    public function atualizar($motorista);

	/**
	 * @param int $id_usuario
	 */
	public function excluir($id_usuario);
}


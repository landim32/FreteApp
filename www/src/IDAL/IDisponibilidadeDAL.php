<?php

namespace Emagine\Frete\IDAL;

use Emagine\Frete\Model\DisponibilidadeInfo;

interface IDisponibilidadeDAL {

	/**
	 * @param int $id_usuario
	 * @return DisponibilidadeInfo[]
	 */
	public function listar($id_usuario);

    /**
     * @param int $id_disponibilidade
     * @return DisponibilidadeInfo
     */
    public function pegar($id_disponibilidade);

	/**
	 * @param DisponibilidadeInfo $disponibilidade
	 * @return int
	 */
	public function inserir($disponibilidade);

    /**
     * @param DisponibilidadeInfo $disponibilidade
     */
    public function alterar($disponibilidade);

    /**
     * @param int $id_disponibilidade
     */
    public function excluir($id_disponibilidade);

	/**
	 * @param int $id_usuario
	 */
	public function limpar($id_usuario);

}


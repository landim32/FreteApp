<?php
namespace Emagine\Frete\IBLL;

use Emagine\Login\DALFactory\UsuarioDALFactory;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Frete\DALFactory\FreteDALFactory;
use Emagine\Frete\DALFactory\FreteHistoricoDALFactory;
use Emagine\Frete\DALFactory\MotoristaDALFactory;
use Emagine\Frete\Model\LocalInfo;
use Emagine\Frete\Model\MotoristaFreteInfo;
use Emagine\Frete\Model\MotoristaEnvioInfo;
use Emagine\Frete\Model\MotoristaInfo;
use Emagine\Frete\Model\MotoristaRetornoInfo;
use Emagine\Frete\Model\FreteHistoricoInfo;
use Emagine\Frete\Model\FreteInfo;

interface IMotoristaBLL {

    /**
     * @return array<int,string>
     */
    public function listarSituacao();

    /**
     * @return array<int,string>
     */
    public function listarDisponibilidade();

	/**
     * @throws Exception
	 * @return MotoristaInfo[]
	 */
	public function listar();

	/**
     * @throws Exception
	 * @param int $id_motorista
	 * @return MotoristaInfo
	 */
	public function pegar($id_motorista);

	/**
	 * @throws Exception
	 * @param MotoristaInfo $motorista
     * @return int
	 */
	public function inserir(MotoristaInfo $motorista);

	/**
	 * @throws Exception
	 * @param MotoristaInfo $motorista
	 */
	public function alterar(MotoristaInfo $motorista);

	/**
	 * @throws Exception
	 * @param int $id_motorista
	 */
	public function excluir($id_motorista);

    /**
     * @param MotoristaEnvioInfo $envio
     * @return MotoristaRetornoInfo
     * @throws Exception
     */
    public function atualizar(MotoristaEnvioInfo $envio);

    /**
     * @param int $id_motorista
     * @return float
     * @throws Exception
     */
    public function pegarNotaCliente($id_motorista);

    /**
     * @param int $id_motorista
     * @return float
     * @throws Exception
     */
    public function pegarNotaMotorista($id_motorista);
}


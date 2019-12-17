<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 08/12/2017
 * Time: 01:10
 * Tablename: entrega_local
 */

namespace Emagine\Frete\BLL;

use Emagine\Frete\DALFactory\FreteLocalDALFactory;
use Exception;
use Emagine\Frete\DAL\FreteLocalDAL;
use Emagine\Frete\Model\FreteLocalInfo;

/**
 * Class FreteLocalBLL
 * @package Emagine\Frete\BLL
 * @tablename entrega_local
 * @author EmagineCRUD
 */
class FreteLocalBLL {


	/**
	 * @return array<string,string>
	 */
	public function listarCodTipo() {
		return array(
			FreteLocalInfo::ORIGEM => 'Saída',
			FreteLocalInfo::PARADA => 'Parada',
			FreteLocalInfo::DESTINO => 'Destino'
		);
	}

	/**
     * @throws Exception
	 * @param int $id_frete
	 * @return FreteLocalInfo[]
	 */
	public function listar($id_frete) {
		$dal = FreteLocalDALFactory::create();
		return $dal->listar($id_frete);
	}


	/**
     * @throws Exception
	 * @param int $id_local
	 * @return FreteLocalInfo
	 */
	public function pegar($id_local) {
		$dal = FreteLocalDALFactory::create();
		return $dal->pegar($id_local);
	}

	/**
	 * @throws Exception
	 * @param FreteLocalInfo $local
	 */
	protected function validar(&$local) {
		if ($local->getIdFrete() == 0) {
			throw new Exception('O local não está vinculado a entrega..');
		}
        if (!($local->getCodTipo() > 0)) {
			throw new Exception('Selecione o tipo.');
		}
		if (!($local->getOrdem() > 0)) {
			throw new Exception('Informe a ordem.');
		}
        $local->setCep(cortarTexto($local->getCep(), 10));
        $local->setLogradouro(cortarTexto($local->getLogradouro(), 60));
        $local->setComplemento(cortarTexto($local->getComplemento(), 40));
        $local->setNumero(cortarTexto($local->getNumero(), 20));
        $local->setBairro(cortarTexto($local->getBairro(), 50));
        $local->setCidade(cortarTexto($local->getCidade(), 50));
        $local->setUf(strtoupper(cortarTexto($local->getUf(), 2)));
	}

	/**
	 * @throws Exception
	 * @param FreteLocalInfo $local
	 * @return int
	 */
	public function inserir($local) {
		$this->validar($local);
		$dal = FreteLocalDALFactory::create();
		return $dal->inserir($local);
	}

	/**
	 * @throws Exception
	 * @param FreteLocalInfo $local
	 */
	public function alterar($local) {
		$this->validar($local);
		$dal = FreteLocalDALFactory::create();
		$dal->alterar($local);
	}

	/**
	 * @throws Exception
	 * @param int $id_local
	 */
	public function excluir($id_local) {
		$dal = FreteLocalDALFactory::create();
		$dal->excluir($id_local);
	}
	/**
     * @throws Exception
	 * @param int $id_frete
	 */
	public function limpar($id_frete) {
		$dal = FreteLocalDALFactory::create();
		$dal->limpar($id_frete);
	}


}


<?php
namespace Emagine\Frete\MaisCargas\BLL;

use Emagine\Frete\MaisCargas\DAL\MotoristaDAL;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\DALFactory\UsuarioDALFactory;
use Emagine\Login\Model\UsuarioInfo;
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

/**
 * Class PedidoItemBLL
 * @package EmaginePedido\BLL
 * @tablename pedido_item
 * @author EmagineCRUD
 */
class MotoristaBLL extends \Emagine\Frete\BLL\MotoristaBLL {

	/**
	 * @throws Exception
	 * @param MotoristaInfo $motorista
     * @return int
	 */
	public function inserir($motorista) {
	    $id_motorista = null;
		$this->validar($motorista);
		$dal = MotoristaDALFactory::create();
		try{
		    DB::beginTransaction();
            $this->gravarFotos($motorista);
            $id_motorista = $dal->inserir($motorista);
			DB::commit();
		}
		catch (Exception $e){
		    DB::rollBack();
			throw $e;
		}
        return $id_motorista;
	}

	/**
	 * @throws Exception
	 * @param MotoristaInfo $motorista
	 */
	public function alterar($motorista) {
		$this->validar($motorista);
		$dal = MotoristaDALFactory::create();
		try{
		    DB::beginTransaction();
            $this->gravarFotos($motorista);
			$dal->alterar($motorista);
			DB::commit();
		}
		catch (Exception $e){
		    DB::rollBack();
			throw $e;
		}
	}

    /**
     * @param string $email
     * @param string $senha
     * @return int
     * @throws Exception
     */
    public function logar($email, $senha) {
        /** @var MotoristaDAL $dal */
        $dal = MotoristaDALFactory::create();
        $motorista = $dal->pegarPorLogin($email, $senha);
        if (is_null($motorista)) {
            throw new Exception("Email ou senha inválida.");
        }
        if ($motorista->getCodSituacao() == MotoristaInfo::AGUARDANDO_APROVACAO) {
            throw new Exception("Aguardando aprovação.");
        }
        if ($motorista->getCodSituacao() == MotoristaInfo::REPROVADO) {
            throw new Exception("Seu cadastro como motorista foi reprovado.");
        }
        $regraUsuario = new UsuarioBLL();
        $regraUsuario->gravarCookie( $motorista->getId(), true );
        return $motorista->getId();
    }

}


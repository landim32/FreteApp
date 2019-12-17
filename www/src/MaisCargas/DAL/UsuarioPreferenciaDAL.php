<?php
namespace Emagine\Frete\MaisCargas\DAL;

use Emagine\Login\IDAL\IUsuarioPreferenciaDAL;
use PDO;
use PDOStatement;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Login\Model\UsuarioPreferenciaInfo;

/**
 * Class UsuarioPreferenciaDAL
 * @package EmagineAuth\DAL
 * @tablename usuario_preferencia
 * @author EmagineCRUD
 */
class UsuarioPreferenciaDAL implements IUsuarioPreferenciaDAL {

	/**
     * @throws Exception
	 * @return UsuarioPreferenciaInfo[]
	 */
	public function listar() {
	    return array();
	    /*
		$query = $this->query();
		$db = DB::getDB()->prepare($query);
		return DB::getResult($db,"Emagine\\Login\\Model\\UsuarioPreferenciaInfo");
	    */
	}

	/**
     * @throws Exception
	 * @param int $id_usuario
	 * @return UsuarioPreferenciaInfo[]
	 */
	public function listarPorIdUsuario($id_usuario) {
        return array();
	    /*
		$query = $this->query() . "
			WHERE usuario_preferencia.id_usuario = :id_usuario
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
		return DB::getResult($db,"Emagine\\Login\\Model\\UsuarioPreferenciaInfo");
	    */
	}

	/**
     * @throws Exception
	 * @param int $id_usuario
	 * @param string $chave
	 * @return UsuarioPreferenciaInfo
	 */
	public function pegar($id_usuario, $chave) {
	    return null;
	    /*
		$query = $this->query() . "
			WHERE usuario_preferencia.id_usuario = :id_usuario 
			AND usuario_preferencia.chave = :chave
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_usuario", $id_usuario);
		$db->bindValue(":chave", $chave);
		return DB::getValueClass($db,"Emagine\\Login\\Model\\UsuarioPreferenciaInfo");
	    */
	}

    /**
     * @throws Exception
     * @param string $chave
     * @param string $valor
     * @return UsuarioPreferenciaInfo
     */
    public function pegarPorValor($chave, $valor) {
        return null;
        /*
        $query = $this->query() . "
			WHERE usuario_preferencia.chave = :chave
			AND usuario_preferencia.valor = :valor
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":chave", $chave);
        $db->bindValue(":valor", $valor);
        return DB::getValueClass($db,"Emagine\\Login\\Model\\UsuarioPreferenciaInfo");
        */
    }

	/**
     * @throws Exception
	 * @param UsuarioPreferenciaInfo $preferencia
	 */
	public function inserir($preferencia) {
	    // Não faz nada
	}

	/**
     * @throws Exception
	 * @param UsuarioPreferenciaInfo $preferencia
	 */
	public function alterar($preferencia) {
        // Não faz nada
	}

	/**
     * @throws Exception
	 * @param int $id_usuario
	 * @param string $chave
	 */
	public function excluir($id_usuario, $chave) {
        // Não faz nada
	}
	/**
     * @throws Exception
	 * @param int $id_usuario
	 */
	public function limpar($id_usuario) {
        // Não faz nada
	}

}


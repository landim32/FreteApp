<?php
namespace Emagine\Frete\MaisCargas\DAL;

use Exception;
use Emagine\Login\IDAL\IGrupoDAL;
use Emagine\Login\Model\GrupoInfo;

/**
 * Class GrupoDAL
 * @package EmagineAuth\DAL
 * @tablename grupo
 * @author EmagineCRUD
 */
class GrupoDAL implements IGrupoDAL {

	/**
     * @throws Exception
	 * @return GrupoInfo[]
	 */
	public function listar() {
	    return array();
	}

    /**
     * @throws Exception
     * @param int $id_usuario
     * @return GrupoInfo[]
     */
    public function listarPorIdUsuario($id_usuario) {
        return array();
    }

	/**
     * @throws Exception
	 * @param int $id_grupo
	 * @return GrupoInfo
	 */
	public function pegar($id_grupo) {
	    return null;
	}

	/**
     * @throws Exception
	 * @param GrupoInfo $grupo
	 * @return int
	 */
	public function inserir($grupo) {
	    return 0;
	}

	/**
     * @throws Exception
	 * @param GrupoInfo $grupo
	 */
	public function alterar($grupo) {
	    // Não faz nada
	}

	/**
     * @throws Exception
	 * @param int $id_grupo
	 */
	public function excluir($id_grupo) {
        // Não faz nada
	}

    /**
     * @throws Exception
     * @param int $id_grupo
     */
    public function limparPermissaoPorIdGrupo($id_grupo) {
        // Não faz nada
    }

    /**
     * @throws Exception
     * @param int $id_grupo
     * @param string $slug
     */
    public function adicionarPermissao($id_grupo, $slug) {
        // Não faz nada
    }
}


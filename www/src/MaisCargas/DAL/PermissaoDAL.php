<?php
namespace Emagine\Frete\MaisCargas\DAL;

use PDO;
use PDOStatement;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Login\IDAL\IPermissaoDAL;
use Emagine\Login\Model\PermissaoInfo;

/**
 * Class PermissaoDAL
 * @package EmagineAuth\DAL
 * @tablename permissao
 * @author EmagineCRUD
 */
class PermissaoDAL implements IPermissaoDAL {

	/**
	 * @return string
	 */
	public function query() {
		return "
			SELECT 
				permissao.slug,
				permissao.nome
			FROM permissao
		";
	}

	/**
     * @throws Exception
	 * @return PermissaoInfo[]
	 */
	public function listar() {
		$query = $this->query();
		$db = DB::getDB()->prepare($query);
		return DB::getResult($db,"Emagine\\Login\\Model\\PermissaoInfo");
	}

    /**
     * @throws Exception
     * @param int $id_grupo
     * @return PermissaoInfo[]
     */
    public function listarPorIdGrupo($id_grupo) {
        $query = $this->query() . "
            INNER JOIN grupo_permissao ON grupo_permissao.slug = permissao.slug
			WHERE grupo_permissao.id_grupo = :id_grupo
		";;
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_grupo", $id_grupo, PDO::PARAM_INT);
        return DB::getResult($db,"Emagine\\Login\\Model\\PermissaoInfo");
    }

	/**
     * @throws Exception
	 * @param string $slug
	 * @return PermissaoInfo
	 */
	public function pegar($slug) {
		$query = $this->query() . "
			WHERE permissao.slug = :slug
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":slug", $slug);
		return DB::getValueClass($db,"Emagine\\Login\\Model\\PermissaoInfo");
	}

	/**
	 * @param PDOStatement $db
	 * @param PermissaoInfo $permissao
	 */
	public function preencherCampo(PDOStatement $db, PermissaoInfo $permissao) {
		$db->bindValue(":slug", $permissao->getSlug());
		$db->bindValue(":nome", $permissao->getNome());
	}

	/**
     * @throws Exception
	 * @param PermissaoInfo $permissao
	 */
	public function inserir($permissao) {
		$query = "
			INSERT INTO permissao (
				slug,
				nome
			) VALUES (
				:slug,
				:nome
			)
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $permissao);
		$db->execute();
	}

	/**
     * @throws Exception
	 * @param PermissaoInfo $permissao
	 */
	public function alterar($permissao) {
		$query = "
			UPDATE permissao SET 
				nome = :nome
			WHERE permissao.slug = :slug
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $permissao);
		$db->bindValue(":slug", $permissao->getSlug());
		$db->execute();
	}

	/**
     * @throws Exception
	 * @param string $slug
	 */
	public function excluir($slug) {
		$query = "
			DELETE FROM permissao
			WHERE slug = :slug
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":slug", $slug);
		$db->execute();
	}
}


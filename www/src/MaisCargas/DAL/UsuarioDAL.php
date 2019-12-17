<?php
namespace Emagine\Frete\MaisCargas\DAL;

use PDO;
use Exception;
use PDOStatement;
use Landim32\EasyDB\DB;
use Emagine\Login\IDAL\IUsuarioDAL;
use Emagine\Login\Model\UsuarioInfo;

/**
 * Class UsuarioDAL
 * @package EmagineAuth\DAL
 * @tablename usuario
 * @author EmagineCRUD
 */
class UsuarioDAL implements IUsuarioDAL {

	/**
	 * @return string
	 */
	public function query() {
		return "
			SELECT 
				enterprises.Id AS 'id_usuario',
				null AS 'foto',
				NOW() AS 'data_inclusao',
				NOW() AS 'ultima_alteracao',
				NOW() AS 'ultimo_login',
				enterprisecontact.Email AS 'email',
				null AS 'slug',
				enterprises.Name AS 'nome',
				enterprises.Password AS 'senha',
				enterprisecontact.Phone AS 'telefone',
				enterprises.CNPJ AS 'cpf_cnpj',
				enterprises.Status AS 'cod_situacao'
			FROM enterprises
			LEFT JOIN enterprisecontact ON enterprisecontact.EnterprisesId = enterprises.Id   
		";
	}

	/**
     * @throws Exception
     * @param int $codSituacao
	 * @return UsuarioInfo[]
	 */
	public function listar($codSituacao = 0) {
		$query = $this->query();
		if ($codSituacao > 0) {
		    $query .= " WHERE enterprises.Status = :cod_situacao ";
        }
        $query .= " ORDER BY enterprises.Name ";
		$db = DB::getDB()->prepare($query);
        if ($codSituacao > 0) {
            //$db->bindValue(":cod_situacao", $codSituacao, PDO::PARAM_INT);
            $db->bindValue(":cod_situacao", 1, PDO::PARAM_INT);
        }
		return DB::getResult($db,"Emagine\\Login\\Model\\UsuarioInfo");
	}

    /**
     * @throws Exception
     * @param string $palavraChave
     * @return UsuarioInfo[]
     */
    public function buscaPorPalavra($palavraChave) {
        $query = $this->query() . "
            WHERE (
                usuario.nome LIKE :palavra_nome OR
                usuario.email LIKE :palavra_email
            )
            AND usuario.cod_situacao = :cod_situacao
            ORDER BY usuario.nome
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":palavra_nome", '%' . $palavraChave . '%');
        $db->bindValue(":palavra_email", '%' . $palavraChave . '%');
        $db->bindValue(":cod_situacao", UsuarioInfo::ATIVO, PDO::PARAM_INT);
        return DB::getResult($db,"Emagine\\Login\\Model\\UsuarioInfo");
    }

	/**
     * @throws Exception
	 * @param int $id_usuario
	 * @return UsuarioInfo
	 */
	public function pegar($id_usuario) {
		$query = $this->query() . "
			WHERE enterprises.Id = :id_usuario
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
		return DB::getValueClass($db,"Emagine\\Login\\Model\\UsuarioInfo");
	}

    /**
     * @throws Exception
     * @param string $slug
     * @return UsuarioInfo
     */
    public function pegarPorSlug($slug) {
        return null;
    }

    /**
     * @throws Exception
     * @param string $email
     * @return UsuarioInfo
     */
    public function pegarPorEmail($email) {
        $query = $this->query() . "
			WHERE (
                enterprisecontact.Email IS NULL OR
                enterprisecontact.Email = :email OR
                enterprises.CNPJ = :cnpj
			)
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":email", $email);
        $db->bindValue(":cnpj", $email);
        return DB::getValueClass($db,"Emagine\\Login\\Model\\UsuarioInfo");
    }

    /**
     * @param string $email
     * @param string $senha
     * @return UsuarioInfo|null
     * @throws Exception
     */
    public function pegarPorLogin($email, $senha) {
        $query = $this->query() . "
			WHERE (
			    enterprisecontact.Email IS NULL OR
			    enterprisecontact.Email = :email OR
			    enterprises.CNPJ = :cnpj
			) 
			AND enterprises.Password = PASSWORD(:senha)
			AND enterprises.Status = 1
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":email", $email);
        $db->bindValue(":cnpj", $email);
        $db->bindValue(":senha", $senha);
        return DB::getValueClass($db,"Emagine\\Login\\Model\\UsuarioInfo");
    }

    /**
     * @param PDOStatement $db
     * @param UsuarioInfo $usuario
     */
    private function preencherCampoEmpresa(PDOStatement $db, UsuarioInfo $usuario) {
        $db->bindValue(":Name", $usuario->getNome());
        $db->bindValue(":CNPJ", $usuario->getCpfCnpj());
        $db->bindValue(":Status", $usuario->getCodSituacao() > 0 ? 1 : 0, PDO::PARAM_BOOL);
    }

    /**
     * @param PDOStatement $db
     * @param UsuarioInfo $usuario
     */
    private function preencherCampoContato(PDOStatement $db, UsuarioInfo $usuario) {
        $db->bindValue(":Name", $usuario->getNome());
        $db->bindValue(":Phone", $usuario->getTelefone());
        $db->bindValue(":Email", $usuario->getEmail());
    }

    /**
     * @param UsuarioInfo $usuario
     * @return int
     * @throws Exception
     */
    private function inserirEmpresa(UsuarioInfo $usuario)
    {
        $query = "
			INSERT INTO enterprises (
				Name,
				CNPJ,
				Status,
				Password
			) VALUES (
				:Name,
				:CNPJ,
				:Status,
				PASSWORD(:Password)
			)
		";
        $db = DB::getDB()->prepare($query);
        $this->preencherCampoEmpresa($db, $usuario);
        $db->bindValue(":Password", $usuario->getSenha());
        $db->execute();
        return DB::lastInsertId();
    }

    /**
     * @param UsuarioInfo $usuario
     * @return int
     * @throws Exception
     */
    private function inserirContato(UsuarioInfo $usuario)
    {
        $query = "
			INSERT INTO enterprisecontact (
				EnterprisesId,
				Name,
				Phone,
				Email
			) VALUES (
				:EnterprisesId,
				:Name,
				:Phone,
				:Email
			)
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":EnterprisesId", $usuario->getId(), PDO::PARAM_INT);
        $this->preencherCampoContato($db, $usuario);
        $db->execute();
        return DB::lastInsertId();
    }

	/**
     * @throws Exception
	 * @param UsuarioInfo $usuario
	 * @return int
	 */
	public function inserir($usuario) {
        $id_usuario = $this->inserirEmpresa($usuario);
        $usuario->setId($id_usuario);
        $this->inserirContato($usuario);
        return $id_usuario;
	}

    /**
     * @param UsuarioInfo $usuario
     * @throws Exception
     */
	private function alterarEmpresa(UsuarioInfo $usuario) {
        $query = "
			UPDATE enterprises SET 
				Name = :Name,
				CNPJ = :CNPJ,
				Status = :Status
			WHERE Id = :Id
		";
        $db = DB::getDB()->prepare($query);
        $this->preencherCampoEmpresa($db, $usuario);
        $db->bindValue(":Id", $usuario->getId(), PDO::PARAM_INT);
        $db->execute();
    }

    /**
     * @param UsuarioInfo $usuario
     * @throws Exception
     */
    private function alterarContato(UsuarioInfo $usuario) {
        $query = "
			UPDATE enterprisecontact SET 
				Name = :Name,
				Phone = :Phone,
				Email = :Email
			WHERE EnterprisesId = :EnterprisesId
		";
        $db = DB::getDB()->prepare($query);
        $this->preencherCampoContato($db, $usuario);
        $db->bindValue(":EnterprisesId", $usuario->getId(), PDO::PARAM_INT);
        $db->execute();
    }

	/**
     * @throws Exception
	 * @param UsuarioInfo $usuario
	 */
	public function alterar($usuario) {
        $this->alterarEmpresa($usuario);
        $this->alterarContato($usuario);
	}

    /**
     * @throws Exception
     * @param int $id_usuario
     * @param string $senha
     */
	public function alterarSenha($id_usuario, $senha) {
        $query = "
			UPDATE usuario SET 
				ultima_alteracao = NOW(),
				senha = :senha
			WHERE usuario.id_usuario = :id_usuario
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
        $db->bindValue(":senha", $senha);
        $db->execute();
    }

	/**
     * @throws Exception
	 * @param int $id_usuario
	 */
	public function excluir($id_usuario) {
		$query = "
			DELETE FROM usuario
			WHERE id_usuario = :id_usuario
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
		$db->execute();
	}

    /**
     * @throws Exception
     * @param int $id_usuario
     */
    public function limparGrupoPorIdUsuario($id_usuario) {
        // Não faz nada
    }

    /**
     * @throws Exception
     * @param int $id_usuario
     * @param int $id_grupo
     */
    public function adicionarGrupo($id_usuario, $id_grupo) {
        // Não faz nada
    }
}


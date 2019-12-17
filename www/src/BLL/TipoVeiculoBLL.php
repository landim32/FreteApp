<?php
namespace Emagine\Frete\BLL;

use Exception;
use Emagine\Frete\DALFactory\TipoVeiculoDALFactory;
use Emagine\Frete\Model\TipoVeiculoInfo;
use Slim\Http\UploadedFile;

class TipoVeiculoBLL
{

    /**
     * @return array<int,string>
     */
    public function listarTipo() {
        return array(
            TipoVeiculoInfo::MOTO => "Moto",
            TipoVeiculoInfo::CARRO => "Carro",
            TipoVeiculoInfo::CAMINHONETE => "Caminhonete/Utilitário",
            TipoVeiculoInfo::CAMINHAO => "Caminhão"
        );
    }

    /**
     * @return TipoVeiculoInfo[]
     * @throws Exception
     */
    public function listar() {
        $dal = TipoVeiculoDALFactory::create();
        return $dal->listar();
    }

    /**
     * @param int $id_tipo
     * @return TipoVeiculoInfo
     * @throws Exception
     */
    public function pegar($id_tipo) {
        $dal = TipoVeiculoDALFactory::create();
        return $dal->pegar($id_tipo);
    }

    /**
     * @param array<string,string> $postData
     * @param TipoVeiculoInfo $veiculo
     */
    public function pegarDoPost($postData, &$veiculo = null) {
        if (is_null($veiculo)) {
            $veiculo = new TipoVeiculoInfo();
        }
        if (array_key_exists("id_tipo", $postData)) {
            $veiculo->setId(intval($postData["id_tipo"]));
        }
        if (array_key_exists("nome", $postData)) {
            $veiculo->setNome($postData["nome"]);
        }
        if (array_key_exists("cod_tipo", $postData)) {
            $veiculo->setCodTipo(intval($postData["cod_tipo"]));
        }
        if (array_key_exists("capacidade", $postData)) {
            $veiculo->setCapacidade(intval($postData["capacidade"]));
        }
    }

    /**
     * @throws Exception
     * @param TipoVeiculoInfo $veiculo
     * @return int
     */
    public function inserir(TipoVeiculoInfo $veiculo) {
        //$this->validar($motorista);
        $dal = TipoVeiculoDALFactory::create();
        return $dal->inserir($veiculo);
    }

    /**
     * @throws Exception
     * @param TipoVeiculoInfo $veiculo
     */
    public function alterar(TipoVeiculoInfo $veiculo) {
        $dal = TipoVeiculoDALFactory::create();
        $dal->alterar($veiculo);
    }

    /**
     * @throws Exception
     * @param int $id_veiculo
     */
    public function excluir($id_veiculo) {
        $dal = TipoVeiculoDALFactory::create();
        $dal->excluir($id_veiculo);
    }

    /**
     * @param UploadedFile $uploadedFile
     * @return string
     * @throws Exception
     */
    public function moveUploadedFile(UploadedFile $uploadedFile)
    {
        $directory = UPLOAD_PATH . '/veiculo';
        if (!file_exists($directory)) {
            @mkdir($directory, 755);
        }
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }
}
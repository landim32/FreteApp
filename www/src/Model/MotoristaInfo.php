<?php
namespace Emagine\Frete\Model;

use stdClass;
use Exception;
use JsonSerializable;
use Emagine\Frete\BLL\TipoCarroceriaBLL;
use Emagine\Frete\BLL\TipoVeiculoBLL;
use Emagine\Frete\BLLFactory\MotoristaBLLFactory;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\Model\UsuarioInfo;

/**
 * Class MotoristaInfo
 * @package Emagine\Frete\Model
 */
class MotoristaInfo implements JsonSerializable {

    const ATIVO = 1;
    const AGUARDANDO_APROVACAO = 2;
    const REPROVADO = 3;

    const DISPONIVEL = 1;
    const INDISPONIVEL = 2;
    const PEGANDO_ENCOMENDA = 3;
    const ENTREGANDO = 4;

    private $id_usuario;
    private $id_tipo;
    private $id_carroceria;
    private $foto_carteira;
    private $foto_veiculo;
    private $foto_endereco;
    private $foto_cpf;
    private $cnh;
    private $placa;
    private $antt;
    private $veiculo;
    private $latitude;
    private $longitude;
    private $direcao;
    private $valor_hora;
    private $cod_situacao;
    private $cod_disponibilidade;
    private $foto_carteira_base64 = null;
    private $foto_veiculo_base64 = null;
    private $foto_endereco_base64 = null;
    private $foto_cpf_base64 = null;
    private $usuario = null;
    private $tipo_veiculo = null;
    private $tipo_carroceria = null;

    /**
     * @return int
     */
    public function getId() {
        return $this->id_usuario;
    }

    /**
     * @param int $value
     */
    public function setId($value) {
        $this->id_usuario = $value;
    }

    /**
     * @return int
     */
    public function getIdTipo() {
        return $this->id_tipo;
    }

    /**
     * @param int $value
     */
    public function setIdTipo($value) {
        $this->id_tipo = $value;
    }

    /**
     * @return int
     */
    public function getIdCarroceria() {
        return $this->id_carroceria;
    }

    /**
     * @param int $value
     */
    public function setIdCarroceria($value) {
        $this->id_carroceria = $value;
    }

    /**
     * @return string
     */
    public function getFotoCarteira() {
        return $this->foto_carteira;
    }

    /**
     * @param string $value
     */
    public function setFotoCarteira($value) {
        $this->foto_carteira = $value;
    }

    /**
     * @return string
     */
    public function getFotoVeiculo() {
        return $this->foto_veiculo;
    }

    /**
     * @param string $value
     */
    public function setFotoVeiculo($value) {
        $this->foto_veiculo = $value;
    }

    /**
     * @return string
     */
    public function getFotoEndereco() {
        return $this->foto_endereco;
    }

    /**
     * @param string $value
     */
    public function setFotoEndereco($value) {
        $this->foto_endereco = $value;
    }

    /**
     * @return string
     */
    public function getFotoCpf() {
        return $this->foto_cpf;
    }

    /**
     * @param string $value
     */
    public function setFotoCpf($value) {
        $this->foto_cpf = $value;
    }

    /**
     * @return string
     */
    public function getCNH() {
        return $this->cnh;
    }

    /**
     * @param string $value
     */
    public function setCNH($value) {
        $this->cnh = $value;
    }

    /**
     * @return string
     */
    public function getPlaca() {
        return $this->placa;
    }

    /**
     * @param string $value
     */
    public function setPlaca($value) {
        $this->placa = $value;
    }

    /**
     * @return string
     */
    public function getAntt() {
        return $this->antt;
    }

    /**
     * @param string $value
     */
    public function setAntt($value) {
        $this->antt = $value;
    }

    /**
     * @return string
     */
    public function getVeiculo() {
        return $this->veiculo;
    }

    /**
     * @param string $value
     */
    public function setVeiculo($value) {
        $this->veiculo = $value;
    }

    /**
     * @return float
     */
    public function getLatitude() {
        return $this->latitude;
    }

    /**
     * @param float $value
     */
    public function setLatitude($value) {
        $this->latitude = $value;
    }

    /**
     * @return float
     */
    public function getLongitude() {
        return $this->longitude;
    }

    /**
     * @param float $value
     */
    public function setLongitude($value) {
        $this->longitude = $value;
    }

    /**
     * @return int
     */
    public function getDirecao() {
        return $this->direcao;
    }

    /**
     * @param int $value
     */
    public function setDirecao($value) {
        $this->direcao = $value;
    }

    /**
     * @return double
     */
    public function getValorHora() {
        return $this->valor_hora;
    }

    /**
     * @param double $value
     */
    public function setValorHora($value) {
        $this->valor_hora = $value;
    }

    /**
     * @return int
     */
    public function getCodSituacao() {
        return $this->cod_situacao;
    }

    /**
     * @param int $value
     */
    public function setCodSituacao($value) {
        $this->cod_situacao = $value;
    }

    /**
     * @return int
     */
    public function getCodDisponibilidade() {
        return $this->cod_disponibilidade;
    }

    /**
     * @param int $value
     */
    public function setCodDisponibilidade($value) {
        $this->cod_disponibilidade = $value;
    }

    /**
     * @return string
     */
    public function getFotoCarteiraBase64() {
        return $this->foto_carteira_base64;
    }

    /**
     * @param string $value
     */
    public function setFotoCarteiraBase64($value) {
        $this->foto_carteira_base64 = $value;
    }

    /**
     * @return string
     */
    public function getFotoVeiculoBase64() {
        return $this->foto_veiculo_base64;
    }

    /**
     * @param string $value
     */
    public function setFotoVeiculoBase64($value) {
        $this->foto_veiculo_base64 = $value;
    }

    /**
     * @return string
     */
    public function getFotoEnderecoBase64() {
        return $this->foto_endereco_base64;
    }

    /**
     * @param string $value
     */
    public function setFotoEnderecoBase64($value) {
        $this->foto_endereco_base64 = $value;
    }

    /**
     * @return string
     */
    public function getFotoCpfBase64() {
        return $this->foto_cpf_base64;
    }

    /**
     * @param string $value
     */
    public function setFotoCpfBase64($value) {
        $this->foto_cpf_base64 = $value;
    }

    /**
     * @param int $width
     * @param int $height
     * @return string
     */
    public function getFotoCarteiraUrl($width = 120, $height = 120) {
        if (!isNullOrEmpty($this->foto_carteira)) {
            return "motorista/" . $width . "x" . $height . "/" . $this->foto_carteira;
        }
        return "img/" . $width . "x" . $height . "/sem-foto.jpg";
    }

    /**
     * @param int $width
     * @param int $height
     * @return string
     */
    public function getFotoVeiculoUrl($width = 120, $height = 120) {
        if (!isNullOrEmpty($this->foto_veiculo)) {
            return "motorista/" . $width . "x" . $height . "/" . $this->foto_veiculo;
        }
        return "img/" . $width . "x" . $height . "/sem-foto.jpg";
    }

    /**
     * @param int $width
     * @param int $height
     * @return string
     */
    public function getFotoEnderecoUrl($width = 120, $height = 120) {
        if (!isNullOrEmpty($this->foto_endereco)) {
            return "motorista/" . $width . "x" . $height . "/" . $this->foto_endereco;
        }
        return "img/" . $width . "x" . $height . "/sem-foto.jpg";
    }

    /**
     * @param int $width
     * @param int $height
     * @return string
     */
    public function getFotoCpfUrl($width = 120, $height = 120) {
        if (!isNullOrEmpty($this->foto_cpf)) {
            return "motorista/" . $width . "x" . $height . "/" . $this->foto_cpf;
        }
        return "img/" . $width . "x" . $height . "/sem-foto.jpg";
    }

    /**
     * @throws Exception
     * @return UsuarioInfo
     */
    public function getUsuario() {
        if (is_null($this->usuario) && $this->getId() > 0) {
            $regraUsuario = new UsuarioBLL();
            $this->usuario = $regraUsuario->pegar($this->getId());
        }
        return $this->usuario;
    }

    /**
     * @param UsuarioInfo $usuario
     */
    public function setUsuario(UsuarioInfo $usuario) {
        $this->usuario = $usuario;
        if (!is_null($usuario) && $usuario->getId() > 0) {
            $this->id_usuario = $usuario->getId();
        }
    }

    /**
     * @return string
     */
    public function getPosicaoStr() {
        $str = "";
        $str .= number_format($this->getLatitude(), 5, ".", "");
        $str .= ",";
        $str .= number_format($this->getLongitude(), 5, ".", "");
        return $str;
    }

    /**
     * @throws Exception
     * @return TipoVeiculoInfo
     */
    public function getTipo() {
        if (is_null($this->tipo_veiculo)) {
            $regraTipoVeiculo = new TipoVeiculoBLL();
            $this->tipo_veiculo = $regraTipoVeiculo->pegar($this->getIdTipo());
        }
        return $this->tipo_veiculo;
    }

    /**
     * @throws Exception
     * @return string
     */
    public function getTipoStr() {
        $tipo = $this->getTipo();
        if (!is_null($tipo)) {
            return $tipo->getNome();
        }
        return null;
    }

    /**
     * @throws Exception
     * @return TipoCarroceriaInfo
     */
    public function getCarroceria() {
        if (is_null($this->tipo_carroceria)) {
            $regraCarroceria = new TipoCarroceriaBLL();
            $this->tipo_carroceria = $regraCarroceria->pegar($this->getIdCarroceria());
        }
        return $this->tipo_carroceria;
    }

    /**
     * @throws Exception
     * @return string
     */
    public function getCarroceriaStr() {
        $carroceria = $this->getCarroceria();
        if (!is_null($carroceria)) {
            return $carroceria->getNome();
        }
        return null;
    }

    /**
     * @return string
     */
    public function getDisponibilidadeStr() {
        $regraMotorista = MotoristaBLLFactory::create();
        $disponibilidades = $regraMotorista->listarDisponibilidade();
        return $disponibilidades[$this->getCodDisponibilidade()];
    }

    /**
     * @return string
     */
    public function getSituacaoStr() {
        $regraMotorista = MotoristaBLLFactory::create();
        $situacoes = $regraMotorista->listarSituacao();
        return $situacoes[$this->getCodSituacao()];
    }

    /**
     * @throws Exception
     * @return stdClass
     */
    public function jsonSerialize() {
        $value = new stdClass();
        $value->id_usuario = $this->getId();
        if (!is_null($this->getUsuario())) {
            $value->usuario = $this->getUsuario()->jsonSerialize();
        }
        $value->id_tipo = $this->getIdTipo();
        $value->tipo = null;
        if (!is_null($this->getTipo())) {
            $value->tipo = $this->getTipo()->jsonSerialize();
        }
        $value->tipo_str = $this->getTipoStr();
        $value->id_carroceria = $this->getIdCarroceria();
        $value->carroceria = null;
        if (!is_null($this->getCarroceria())) {
            $value->carroceria = $this->getCarroceria()->jsonSerialize();
        }
        $value->carroceria_str = $this->getCarroceriaStr();
        $value->foto_carteira = $this->getFotoCarteira();
        $value->foto_veiculo = $this->getFotoVeiculo();
        $value->foto_endereco = $this->getFotoEndereco();
        $value->foto_cpf = $this->getFotoCpf();
        $value->foto_carteira_url = $this->getFotoCarteiraUrl();
        $value->foto_veiculo_url = $this->getFotoVeiculoUrl();
        $value->foto_endereco_url = $this->getFotoEnderecoUrl();
        $value->foto_cpf_url = $this->getFotoCpfUrl();
        $value->cnh = $this->getCNH();
        $value->placa = $this->getPlaca();
        $value->antt = $this->getAntt();
        $value->veiculo  = $this->getVeiculo();
        $value->latitude = $this->getLatitude();
        $value->longitude = $this->getLongitude();
        $value->direcao = $this->getDirecao();
        $value->valor_hora = floatvalx($this->getValorHora());
        $value->cod_situacao = $this->getCodSituacao();
        $value->cod_disponibilidade = $this->getCodDisponibilidade();
        return $value;
    }

    /**
     * @throws Exception
     * @param stdClass $value
     * @return MotoristaInfo
     */
    public static function fromJson($value) {
        $motorista = new MotoristaInfo();
        $motorista->setId($value->id_usuario);
        if (isset($value->usuario)) {
            $motorista->setUsuario(UsuarioInfo::fromJson($value->usuario));
        }
        $motorista->setIdTipo($value->id_tipo);
        $motorista->setIdCarroceria($value->id_carroceria);
        $motorista->setFotoCarteiraBase64($value->foto_carteira_base64);
        $motorista->setFotoVeiculoBase64($value->foto_veiculo_base64);
        $motorista->setFotoEnderecoBase64($value->foto_endereco_base64);
        $motorista->setFotoCpfBase64($value->foto_cpf_base64);
        $motorista->setCNH($value->cnh);
        $motorista->setPlaca($value->placa);
        $motorista->setAntt($value->antt);
        $motorista->setVeiculo($value->veiculo);
        $motorista->setLatitude($value->latitude);
        $motorista->setLongitude($value->longitude);
        $motorista->setDirecao($value->direcao);
        $motorista->setValorHora($value->valor_hora);
        $motorista->setCodSituacao($value->cod_situacao);
        $motorista->setCodDisponibilidade($value->cod_disponibilidade);
        return $motorista;
    }
}
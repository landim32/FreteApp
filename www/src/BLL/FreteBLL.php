<?php
namespace Emagine\Frete\BLL;

use Exception;
use phpmailerException;
use Emagine\Base\EmagineApp;
use Landim32\EasyDB\DB;
use Emagine\Frete\DALFactory\FreteDALFactory;
use Emagine\Frete\DALFactory\FreteTipoCarroceriaDALFactory;
use Emagine\Frete\DALFactory\FreteTipoVeiculoDALFactory;
use Emagine\Frete\BLLFactory\FreteHistoricoBLLFactory;
use Emagine\Base\BLL\MailJetBLL;
use Emagine\Frete\IBLL\IFreteBLL;
use Emagine\Frete\Model\FreteInfo;
use Emagine\Frete\Model\AceiteEnvioInfo;
use Emagine\Frete\Model\AceiteRetornoInfo;
use Emagine\Frete\Model\FreteRetornoInfo;
use Emagine\Frete\Model\TipoVeiculoInfo;

/**
 * Class FreteBLL
 * @package Emagine\Frete\BLL
 * @tablename entrega
 * @author EmagineCRUD
 */
class FreteBLL implements IFreteBLL {

    const ERRO_SELECIONE_VEICULO = "Selecione o veículo.";
    const ERRO_TIPO_VEICULO_NAO_INFORMADO = "Nenhum tipo de veículo informado.";
    const ERRO_MOTORISTA_NAO_DEFINIDO = "O motorista ainda não foi definido.";
    const ERRO_MOTORISTA_NAO_DISPONIVEL = "Motorista não disponível.";
    const ERRO_FRETE_POSSUI_MOTORISTA = "Esse frete já possui um motorista.";

	/**
	 * @return array<string,string>
	 */
	public function listarSituacao() {
		return array(
			FreteInfo::AGUARDANDO_PAGAMENTO => 'Aguardando Pagamento',
			FreteInfo::PROCURANDO_MOTORISTA => 'Procurando Motorista',
            FreteInfo::APROVANDO_MOTORISTA => 'Aguardando aprovação',
            FreteInfo::AGUARDANDO => 'Aguardando',
			FreteInfo::PEGANDO_ENCOMENDA => 'Pegando encomenda',
			FreteInfo::ENTREGANDO => 'Entregando',
			FreteInfo::ENTREGUE => 'Entregue',
      FreteInfo::ENTREGA_CONFIRMADA => 'Entrega Confirmada',
			FreteInfo::CANCELADO => 'Cancelado'
		);
	}

	/**
     * @throws Exception
     * @param int $id_usuario
     * @param int $id_motorista
     * @param int $cod_situacao
	 * @return FreteInfo[]
	 */
	public function listar($id_usuario = 0, $id_motorista = 0, $cod_situacao = 0) {
		$dal = FreteDALFactory::create();
		return $dal->listar($id_usuario, $id_motorista, $cod_situacao);
	}

    /**
     * @param int $id_usuario
     * @return FreteInfo[]
     * @throws Exception
     */
	public function listarDisponivel($id_usuario) {
        $dal = FreteDALFactory::create();
        return $dal->listarDisponivel($id_usuario);
    }

	/**
     * @throws Exception
	 * @param int $id_usuario
	 * @return FreteInfo[]
	 */
	public function listarPorUsuario($id_usuario) {
		$dal = FreteDALFactory::create();
		return $dal->listar($id_usuario, $id_usuario, 0);
	}

    /**
     * @throws Exception
     * @param int $id_usuario
     * @return FreteInfo[]
     */
    public function listarPorCliente($id_usuario) {
        $dal = FreteDALFactory::create();
        return $dal->listar($id_usuario, 0);
    }

    /**
     * @throws Exception
     * @param int $id_usuario
     * @return FreteInfo[]
     */
    public function listarPorMotorista($id_usuario) {
        $dal = FreteDALFactory::create();
        return $dal->listar(0, $id_usuario);
    }

	/**
     * @throws Exception
	 * @param int $id_frete
	 * @return FreteInfo
	 */
	public function pegar($id_frete) {
		$dal = FreteDALFactory::create();
		$frete = $dal->pegar($id_frete);
		if (!is_null($frete)) {
		    $this->atualizarInterno($frete);
        }
        return $frete;
	}

    /**
     * @throws Exception
     * @param int $id_motorista
     * @param bool $atualizado
     * @return FreteInfo
     */
    public function pegarAbertoPorMotorista($id_motorista, $atualizado = true) {
        $dal = FreteDALFactory::create();
        $frete = $dal->pegarAbertoPorMotorista($id_motorista);
        if ($atualizado == true && !is_null($frete)) {
            $this->atualizarInterno($frete);
        }
        return $frete;
    }

	/**
	 * @throws Exception
	 * @param FreteInfo $frete
	 */
	protected function validar(&$frete) {
		if (!($frete->getIdUsuario() > 0)) {
			throw new Exception('Selecione o usuário.');
		}
		$tiposVeiculo = $frete->listarTipoVeiculo();
        if (!(count($tiposVeiculo) > 0)) {
            throw new Exception(FreteBLL::ERRO_SELECIONE_VEICULO);
        }
		if (!($frete->getCodSituacao() > 0)) {
			$frete->setCodSituacao(FreteInfo::PROCURANDO_MOTORISTA);
		}
		if (!($frete->getId() > 0)) {
            $frete->setCodSituacao(FreteInfo::PROCURANDO_MOTORISTA);
        }
        //if (!($frete->getDistancia() > 0)) {
        //    throw new Exception("Não foi possível calcular a distância.");
        //}
        //if (!($frete->getPreco() > 0)) {
        //    throw new Exception("Não foi possível calcular o valor do frete.");
        //}
        if (!isNullOrEmpty($frete->getDataRetirada())) {
            if (!isNullOrEmpty($frete->getDataEntrega())) {
                $dataRetirada = strtotime($frete->getDataRetirada());
                $dataEntrega = strtotime($frete->getDataEntrega());
                if ($dataEntrega < $dataRetirada) {
                    throw new Exception("A data de retirada não pode ser maior que a data de entrega.");
                }
            }
        }
		if (count($frete->listarLocal()) < 2) {
		    throw new Exception("Um frete precisa de pelomenos dois locais.");
        }
        if ($frete->getCodSituacao() == FreteInfo::PROCURANDO_MOTORISTA && $frete->getIdMotorista() > 0) {
            throw new Exception("Um frete com situação 'Procurando motorista' não pode estar ligado a um motorista.");
        }
        if (is_null($frete->getNotaFrete())) {
            $frete->setNotaFrete(0);
        }
        if (is_null($frete->getNotaMotorista())) {
            $frete->setNotaMotorista(0);
        }
	}

    /**
     * @throws Exception
     * @param FreteInfo $frete
     */
    private function gravarFotos(&$frete) {
        if (!isNullOrEmpty($frete->getFotoBase64())) {
            $frete->setFoto($this->gravarFoto($frete->getFotoBase64()));
        }
    }

    /**
     * @throws Exception
     * @param string $fotoBase64
     * @return string
     */
    private function gravarFoto($fotoBase64) {
        $token = md5(uniqid(rand(), true));
        $dir = UPLOAD_PATH . '/frete';
        if (!file_exists($dir)) {
            @mkdir($dir);
        }
        if (!file_exists($dir)) {
            throw new Exception(sprintf("O diretório '%s' não existe.", $dir));
        }
        $arquivoFoto = $dir . "/" . $token . '.png';
        //$data = preg_replace('#^data:image/\w+;base64,#i', '', $fotoBase64);
        file_put_contents($arquivoFoto, base64_decode($fotoBase64));
        return $token . ".png";
    }

    /**
     * @param FreteInfo $frete
     * @throws Exception
     */
	protected function atualizarInterno(&$frete) {
	    $regraRota = new RotaBLL();
        if ($regraRota->usaCalculoRota()) {
            $rotas = array();
            foreach ($frete->listarLocal() as $local) {
                $rotas[] = $local->getPosicaoStr();
            }
            $rota = $regraRota->calcularRota($rotas);
            if ($rota->getStatus() == "OK") {
                $frete->setRotaEncontrada(true);
                $frete->setEnderecoOrigem($rota->getRoutes()[0]->getLegs()[0]->getStartAddress());
                $frete->setEnderecoDestino($rota->getRoutes()[0]->getLegs()[0]->getEndAddress());
                $frete->setDistancia($rota->getRoutes()[0]->getLegs()[0]->getDistance()->getValue());
                $frete->setDistanciaStr($rota->getRoutes()[0]->getLegs()[0]->getDistance()->getText());
                $frete->setTempo($rota->getRoutes()[0]->getLegs()[0]->getDuration()->getValue());
                $frete->setTempoStr($rota->getRoutes()[0]->getLegs()[0]->getDuration()->getText());
                $frete->setPolyline($rota->getRoutes()[0]->getOverviewPolyline()->getPoints());
            }
            else {
                $frete->setRotaEncontrada(false);
            }
        }
        else {
            $distancia = $regraRota->calcularDistanciaFrete($frete);
            $tempo = floor((($distancia / 1000) / $regraRota->velocidadeMediaPorHora()) * (60 * 60));

            $frete->setRotaEncontrada(false);
            $frete->setDistancia($distancia);
            $frete->setDistanciaStr(number_format(($distancia / 1000), 1, ",", ".") . "km");
            $frete->setTempo($tempo);
            $frete->setTempoStr($regraRota->tempoParaTexto($tempo));
            $frete->setPolyline(null);
        }
    }

    /**
     * @param FreteInfo $frete
     */
    private function atualizarData(FreteInfo $frete) {
        $dalFrete = FreteDALFactory::create();
        $processandoEntrega = array(
            FreteInfo::PEGANDO_ENCOMENDA,
            FreteInfo::ENTREGANDO
        );
        $entregue = array(
            FreteInfo::ENTREGUE,
            FreteInfo::ENTREGA_CONFIRMADA
        );
        if (in_array($frete->getCodSituacao(), $processandoEntrega)) {
            $dalFrete->atualizarDataRetirada($frete->getId());
        }
        elseif (in_array($frete->getCodSituacao(), $entregue)) {
            $dalFrete->atualizarDataEntrega($frete->getId());
        }
    }

	/**
	 * @throws Exception
	 * @param FreteInfo $frete
	 * @return int
	 */
	public function inserir($frete) {
		$id_frete = 0;

		$regraRota = new RotaBLL();
        if ($regraRota->usaCalculoRota()) {
            $rotas = array();
            foreach ($frete->listarLocal() as $local) {
                $rotas[] = $local->getPosicaoStr();
            }
            $rota = $regraRota->calcularRota($rotas);
            if ($rota->getStatus() == "OK") {
                $frete->setRotaEncontrada(true);
                $frete->setEnderecoOrigem($rota->getRoutes()[0]->getLegs()[0]->getStartAddress());
                $frete->setEnderecoDestino($rota->getRoutes()[0]->getLegs()[0]->getEndAddress());
                $frete->setDistancia($rota->getRoutes()[0]->getLegs()[0]->getDistance()->getValue());
                $frete->setDistanciaStr($rota->getRoutes()[0]->getLegs()[0]->getDistance()->getText());
                $frete->setTempo($rota->getRoutes()[0]->getLegs()[0]->getDuration()->getValue());
                $frete->setTempoStr($rota->getRoutes()[0]->getLegs()[0]->getDuration()->getText());
                $frete->setPolyline($rota->getRoutes()[0]->getOverviewPolyline()->getPoints());
                if (!($frete->getPreco() > 0)) {
                    $preco = $this->calcularValor($frete);
                    $frete->setPreco($preco);
                }
            }
            else {
                $frete->setRotaEncontrada(false);
            }
        }
        else {
            $distancia = $regraRota->calcularDistanciaFrete($frete);
            $tempo = $regraRota->calcularTempo($distancia);

            $frete->setRotaEncontrada(false);
            $frete->setDistancia($distancia);
            $frete->setDistanciaStr($regraRota->distanciaParaTexto($distancia));
            $frete->setTempo($tempo);
            $frete->setTempoStr($regraRota->tempoParaTexto($tempo));
        }

        $this->validar($frete);

		$dal = FreteDALFactory::create();
		$regraFreteLocal = new FreteLocalBLL();
		$dalTipoVeiculo = FreteTipoVeiculoDALFactory::create();
		$dalCarroceria = FreteTipoCarroceriaDALFactory::create();
		try{
		    DB::beginTransaction();
            $this->gravarFotos($frete);
			$id_frete = $dal->inserir($frete);
			$ordem = 1;
			foreach ($frete->listarLocal() as $item) {
				$item->setIdFrete($id_frete);
				$item->setOrdem($ordem);
				$regraFreteLocal->inserir($item);
				$ordem++;
			}
			foreach ($frete->listarTipoVeiculo() as $veiculo) {
                $dalTipoVeiculo->inserir($id_frete, $veiculo->getId());
            }
            foreach ($frete->listarCarroceria() as $carroceria) {
                $dalCarroceria->inserir($id_frete, $carroceria->getId());
            }
            $this->atualizarData($frete);
			DB::commit();
		}
		catch (Exception $e){
		    DB::rollBack();
			throw $e;
		}
		return $id_frete;
	}

	/**
	 * @throws Exception
	 * @param FreteInfo $frete
	 */
	public function alterar($frete) {
		$this->validar($frete);
		$antigoFrete = $this->pegar($frete->getId());
		$dal = FreteDALFactory::create();
		$regraFreteLocal = new FreteLocalBLL();
        $dalTipoVeiculo = FreteTipoVeiculoDALFactory::create();
        $dalCarroceria = FreteTipoCarroceriaDALFactory::create();
		try{
		    DB::beginTransaction();
            $this->gravarFotos($frete);
			$dal->alterar($frete);
			$id_frete = $frete->getId();
			$frete->listarLocal();
			$frete->listarTipoVeiculo();
            $frete->listarCarroceria();
			$regraFreteLocal->limpar($frete->getId());
            $dalTipoVeiculo->limpar($frete->getId());
            $dalCarroceria->limpar($frete->getId());
            $ordem = 1;
			foreach ($frete->listarLocal() as $item) {
			    $item->setOrdem($ordem);
				$item->setIdFrete($id_frete);
				$regraFreteLocal->inserir($item);
				$ordem++;
			}
            foreach ($frete->listarTipoVeiculo() as $veiculo) {
                $dalTipoVeiculo->inserir($id_frete, $veiculo->getId());
            }
            foreach ($frete->listarCarroceria() as $carroceria) {
                $dalCarroceria->inserir($id_frete, $carroceria->getId());
            }
            $this->atualizarData($frete);
			DB::commit();
		}
		catch (Exception $e){
		    DB::rollBack();
			throw $e;
		}
        if (!is_null($antigoFrete)) {
            if ($antigoFrete->getCodSituacao() != $frete->getCodSituacao()) {
                if ($frete->getCodSituacao() == FreteInfo::ENTREGA_CONFIRMADA) {
                    $usuario = $frete->getUsuario();
                    $this->enviarEmail($usuario->getEmail(), $frete);
                    $motorista = $frete->getMotorista();
                    if (!is_null($motorista)) {
                        $this->enviarEmail($motorista->getUsuario()->getEmail(), $frete);
                    }
                }
            }
        }
	}

	/**
	 * @throws Exception
	 * @param int $id_frete
	 * @param bool $transaction
	 */
	public function excluir($id_frete, $transaction = true) {
		$dal = FreteDALFactory::create();
		$regraFreteLocal = new FreteLocalBLL();
        $dalTipoVeiculo = FreteTipoVeiculoDALFactory::create();
        $dalCarroceria = FreteTipoCarroceriaDALFactory::create();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
            $dal->limparAceite($id_frete);
			$regraFreteLocal->limpar($id_frete);
            $dalTipoVeiculo->limpar($id_frete);
            $dalCarroceria->limpar($id_frete);
			$dal->excluir($id_frete);
			if ($transaction === true) {
				DB::commit();
			}
		}
		catch (Exception $e){
			if ($transaction === true) {
				DB::rollBack();
			}
			throw $e;
		}
	}
	/**
	 * @param int $id_usuario
     * @throws Exception
	 */
	public function limparPorIdUsuario($id_usuario) {
		$dal = FreteDALFactory::create();
		$dal->limparPorUsuario($id_usuario);
	}

	/**
	 * @param int $id_motorista
     * @throws Exception
	 */
	public function limparPorIdMotorista($id_motorista) {
		$dal = FreteDALFactory::create();
		$dal->limparPorMotorista($id_motorista);
	}

    /**
     * @throws Exception
     * @param FreteInfo $frete
     * @return float
     */
    public function calcularValor($frete) {
        $preco = 0;
        $tiposVeiculo = $frete->listarTipoVeiculo();
        if (count($tiposVeiculo) == 0) {
            throw new Exception(FreteBLL::ERRO_TIPO_VEICULO_NAO_INFORMADO);
        }
        $hora = intval(date("G"));
        /** @var TipoVeiculoInfo $tipoVeiculo */
        $tipoVeiculo = array_values($tiposVeiculo)[0];
        switch ($tipoVeiculo->getCodTipo()) {
            case TipoVeiculoInfo::MOTO:
                $preco = ($frete->getDistancia() / 1000) * (($hora >= 6 && $hora <= 16) ? 1.58 : 1.68);
                if ($preco < 8.75) {
                    $preco = 8.75;
                }
                break;
            case TipoVeiculoInfo::CARRO:
                $preco = ($frete->getDistancia() / 1000) * (($hora >= 6 && $hora <= 16) ? 1.94 : 1.88);
                if ($preco < 9.7) {
                    $preco = 9.7;
                }
                break;
            case TipoVeiculoInfo::CAMINHONETE:
                $preco = ($frete->getDistancia() / 1000) * (($hora >= 6 && $hora <= 16) ? 2.95 : 3.15);
                $preco += ($frete->getPeso() * 0.29);
                if ($preco < 14.75) {
                    $preco = 14.75;
                }
                break;
            case TipoVeiculoInfo::CAMINHAO:
                $precoKg = 2.95 * ($frete->getPeso() * 0.29);
                $preco = ($frete->getDistancia() / 1000) * $precoKg;
                if ($preco < 14.75) {
                    $preco = 14.75;
                }
                break;
            default:
                throw new Exception("Tipo de veículo não definido.");
                break;
        }
	    return round($preco, 2);
    }

    /**
     * @param int $id_frete
     * @param int $id_usuario
     * @param bool $aceite
     * @throws Exception
     */
    public function adicionarAceite($id_frete, $id_usuario, $aceite) {
        $dal = FreteDALFactory::create();
        $quantidade = $dal->pegarQuantidadeAceite($id_frete, $id_usuario);
        if ($quantidade > 0) {
            $dal->alterarAceite($id_frete, $id_usuario, $aceite);
        }
        else {
            $dal->inserirAceite($id_frete, $id_usuario, $aceite);
        }
    }

    /**
     * @return int
     */
    protected function pegarSituacaoAoAceitar() {
        return FreteInfo::PEGANDO_ENCOMENDA;
    }

    /**
     * @param FreteInfo $frete
     * @throws Exception
     */
    protected function executarAceite(FreteInfo $frete) {
        $this->alterar($frete);
    }

    /**
     * @throws Exception
     * @param AceiteEnvioInfo $aceite
     * @return AceiteRetornoInfo
     */
    public function aceitar($aceite) {
        $retorno = new AceiteRetornoInfo();
        $retorno->setIdFrete($aceite->getIdFrete());
        $retorno->setIdMotorista($aceite->getIdMotorista());
        $retorno->setAceite($aceite->getAceite());

        if ($aceite->getAceite() == true) {

            $frete = $this->pegar($aceite->getIdFrete());

            if ($frete->getIdMotorista() > 0) {
                $retorno->setAceite(false);
                $retorno->setMensagem(FreteBLL::ERRO_FRETE_POSSUI_MOTORISTA);
                $this->adicionarAceite($frete->getId(), $aceite->getIdMotorista(), false);
                return $retorno;
            }
            $frete->setIdMotorista($aceite->getIdMotorista());
            $frete->setCodSituacao($this->pegarSituacaoAoAceitar());
            $this->executarAceite($frete);

            $this->adicionarAceite($frete->getId(), $aceite->getIdMotorista(), $aceite->getAceite());
        }
        else {
            $this->adicionarAceite($aceite->getIdFrete(), $aceite->getIdMotorista(), false);
        }

        return $retorno;
    }

    /**
     * @param int $id_frete
     * @return FreteRetornoInfo
     * @throws Exception
     */
    public function atualizar($id_frete) {
        $frete = $this->pegar($id_frete);
        if (is_null($frete)) {
            throw new Exception("Nenhum frete encontrado com esse ID.");
        }

        $retorno = new FreteRetornoInfo();
        $retorno->setIdMotorista($frete->getIdMotorista());
        if (!is_null($frete)) {
            $regraRota = new RotaBLL();
            $motorista = $frete->getMotorista();
            if (is_null($motorista)) {
                throw new Exception(FreteBLL::ERRO_MOTORISTA_NAO_DEFINIDO);
            }

            $latitude = round(floatval($motorista->getLatitude()), 5);
            $longitude = round(floatval($motorista->getLongitude()), 5);
            $retorno->setIdFrete($frete->getId());
            $retorno->setCodSituacao($frete->getCodSituacao());
            $retorno->setLatitude($latitude);
            $retorno->setLongitude($longitude);

            if ($regraRota->usaCalculoRota()) {
                $rotas = array($motorista->getPosicaoStr());
                foreach ($frete->listarLocal() as $local) {
                    $rotas[] = $local->getPosicaoStr();
                }
                $rota = $regraRota->calcularRota($rotas);
                if ($rota->getStatus() == "OK") {
                    $retorno->setDistancia($rota->getRoutes()[0]->getLegs()[0]->getDistance()->getValue());
                    $retorno->setDistanciaStr($rota->getRoutes()[0]->getLegs()[0]->getDistance()->getText());
                    $retorno->setTempo($rota->getRoutes()[0]->getLegs()[0]->getDuration()->getValue());
                    $retorno->setTempoStr($rota->getRoutes()[0]->getLegs()[0]->getDuration()->getText());
                    $retorno->setPolyline($rota->getRoutes()[0]->getOverviewPolyline()->getPoints());
                } else {
                    $retorno->adicionarMensagem("Não foi possível calcular a rota.");
                }
            }
            else {
                $distancia = $regraRota->calcularDistanciaFrete($frete);
                $tempo = $regraRota->calcularTempo($distancia);

                $retorno->setDistancia($distancia);
                $retorno->setDistanciaStr($regraRota->distanciaParaTexto($distancia));
                $retorno->setTempo($tempo);
                $retorno->setTempoStr($regraRota->tempoParaTexto($tempo));
            }
        }
        else {
            $retorno->adicionarMensagem(FreteBLL::ERRO_MOTORISTA_NAO_DISPONIVEL);
        }
        return $retorno;
    }

    /**
     * @param int $largura
     * @param int $altura
     * @return string
     * @throws Exception
     */
    private function gerarMapaBaseUrl($largura, $altura) {
        if (!defined("GOOGLE_MAPS_API")) {
            throw new Exception("GOOGLE_MAPS_API não foi definido.");
        }
        $url = "http://maps.googleapis.com/maps/api/staticmap";
        $url .= "?key=" . GOOGLE_MAPS_API;
        $url .= sprintf("&size=%sx%s", $largura, $altura);
        return $url;
    }

    /**
     * @throws Exception
     * @param FreteInfo $frete
     * @param int $largura
     * @param int $altura
     * @return string
     */
    public function gerarMapaURL(FreteInfo $frete, $largura = 640, $altura = 360) {
        $url = $this->gerarMapaBaseUrl($largura, $altura);

        $paths = array();
        foreach ($frete->listarLocal() as $local) {
            $paths[] = $local->getLatitude() . "," . $local->getLongitude();
        }
        if (count($paths) > 0) {
            $url .= "&path=" . urlencode("color:red|weight:3|" . implode("|", $paths));
        }

        $origem = $frete->getOrigem();
        $destino = $frete->getDestino();
        $url .= "&markers=" . urlencode("color:green|label:O|" . $origem->getLatitude() . "," . $origem->getLongitude());
        $url .= "&markers=" . urlencode("color:red|label:D|" . $destino->getLatitude() . "," . $destino->getLongitude());

        $url .= "&sensor=false";
        return $url;
    }

    /**
     * @throws Exception
     * @param string $email
     * @param FreteInfo $frete
     */
    private function enviarEmail($email, FreteInfo $frete) {
        $regraEmail = new MailJetBLL();
        $assunto = "[Easy Barcos] Atendimento Finalizando #" . $frete->getId();
        $conteudo = $this->gerarConteudo($frete);
        $regraEmail->sendmail($email, $assunto, $conteudo);
    }

    /**
     * @throws Exception
     * @param FreteInfo $frete
     * @return string
     */
    private function gerarConteudo(FreteInfo $frete) {
        $app = EmagineApp::getApp();
        $regraRota = new RotaBLL();
        $regraHistorico = FreteHistoricoBLLFactory::create();

        $historicos = $regraHistorico->listar($frete->getId());

        $urlMapaPrevisao = $this->gerarMapaURL($frete, 280, 200);
        $urlMapaExecutado = null;
        if (count($historicos) >= 2) {
            $urlMapaExecutado = $regraHistorico->gerarMapaURL($historicos, 280, 200);
        }

        $tempo = strtotime($frete->getDataEntrega()) - strtotime($frete->getDataRetirada());
        $tempoExecutado = strtotime($frete->getDataEntregaExecutada()) - strtotime($frete->getDataRetiradaExecutada());

        $distancia = $regraHistorico->calcularDistancia($historicos);
        $distanciaStr = $regraRota->distanciaParaTexto($distancia);
        $tempoStr = $regraRota->tempoParaTexto($tempo);
        $tempoExecutadoStr = $regraRota->tempoParaTexto($tempoExecutado);

        $preco = 0;
        if (!is_null($frete->getMotorista())) {
            $motorista = $frete->getMotorista();
            $preco = round($motorista->getValorHora() * ($tempoExecutado / 3600), 2);
        }

        ob_start();
        require dirname(__DIR__) . "/templates/frete-email.php";
        $conteudo = ob_get_contents();
        ob_end_clean();

        return $conteudo;
    }
}


<?php
namespace Emagine\Frete\BLL;

use Emagine\Frete\BLLFactory\FreteBLLFactory;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Frete\DALFactory\FreteDALFactory;
use Emagine\Frete\DALFactory\FreteHistoricoDALFactory;
use Emagine\Frete\DALFactory\MotoristaDALFactory;
use Emagine\Login\DALFactory\UsuarioDALFactory;
use Emagine\Frete\IBLL\IMotoristaBLL;
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
class MotoristaBLL implements IMotoristaBLL {

    /**
     * @return array<int,string>
     */
    public function listarSituacao() {
        return array(
            MotoristaInfo::ATIVO => "Ativo",
            MotoristaInfo::AGUARDANDO_APROVACAO => "Aguardando Aprovação",
            MotoristaInfo::REPROVADO => "Reprovado"
        );
    }

    /**
     * @return array<int,string>
     */
    public function listarDisponibilidade() {
        return array(
            MotoristaInfo::DISPONIVEL => "Disponível para entrega",
            MotoristaInfo::INDISPONIVEL => "Indisponível",
            MotoristaInfo::PEGANDO_ENCOMENDA => "Pegando a encomenda",
            MotoristaInfo::ENTREGANDO => "Entregando"
        );
    }

	/**
     * @throws Exception
	 * @return MotoristaInfo[]
	 */
	public function listar() {
		$dal = MotoristaDALFactory::create();
		return $dal->listar();
	}

	/**
     * @throws Exception
	 * @param int $id_motorista
	 * @return MotoristaInfo
	 */
	public function pegar($id_motorista) {
		$dal = MotoristaDALFactory::create();
		return $dal->pegar($id_motorista);
	}

	/**
	 * @throws Exception
	 * @param MotoristaInfo $motorista
	 */
	protected function validar(&$motorista) {
		if (!($motorista->getId() > 0) && is_null($motorista->getUsuario())) {
			throw new Exception('Usuário não informado.');
		}
		if (!($motorista->getCodSituacao() > 0)) {
		    $motorista->setCodSituacao(MotoristaInfo::AGUARDANDO_APROVACAO);
        }
        if (!($motorista->getCodDisponibilidade() > 0)) {
            $motorista->setCodDisponibilidade(MotoristaInfo::INDISPONIVEL);
        }
        if (!isNullOrEmpty($motorista->getPlaca())) {
            $motorista->setPlaca(strtoupper(substr($motorista->getPlaca(), 0, 8)));
        }
	}

	/**
	 * @throws Exception
	 * @param MotoristaInfo $motorista
     * @return int
	 */
	public function inserir($motorista) {
		$this->validar($motorista);
		if (!is_null($motorista->getUsuario())) {
		    $regraUsuario = UsuarioDALFactory::create();
		    $usuario = $motorista->getUsuario();
		    if ($usuario->getId() > 0) {
                $regraUsuario->alterar($usuario);
            }
            else {
		        $id_usuario = $regraUsuario->inserir($usuario);
		        $motorista->setId($id_usuario);
            }
        }
		$dal = MotoristaDALFactory::create();
		try{
		    DB::beginTransaction();
            $this->gravarFotos($motorista);
			$dal->inserir($motorista);
			DB::commit();
		}
		catch (Exception $e){
		    DB::rollBack();
			throw $e;
		}
		return $motorista->getId();
	}

	/**
	 * @throws Exception
	 * @param MotoristaInfo $motorista
	 */
	public function alterar($motorista) {
		$this->validar($motorista);
        if (!is_null($motorista->getUsuario())) {
            $regraUsuario = UsuarioDALFactory::create();
            $usuario = $motorista->getUsuario();
            $regraUsuario->alterar($usuario);
        }
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
	 * @throws Exception
	 * @param int $id_motorista
	 */
	public function excluir($id_motorista) {
		$dal = MotoristaDALFactory::create();
		try{
		    DB::beginTransaction();
			$dal->excluir($id_motorista);
			DB::commit();
		}
		catch (Exception $e){
		    DB::rollBack();
			throw $e;
		}
	}

    /**
     * @throws Exception
     * @param MotoristaInfo $motorista
     */
	public function gravarFotos(&$motorista) {
	    if (!isNullOrEmpty($motorista->getFotoCarteiraBase64())) {
	        $motorista->setFotoCarteira($this->gravarFoto("carteira", $motorista->getFotoCarteiraBase64()));
        }
        if (!isNullOrEmpty($motorista->getFotoCpfBase64())) {
            $motorista->setFotoCpf($this->gravarFoto("cpf", $motorista->getFotoCpfBase64()));
        }
        if (!isNullOrEmpty($motorista->getFotoEnderecoBase64())) {
            $motorista->setFotoEndereco($this->gravarFoto("endereco", $motorista->getFotoEnderecoBase64()));
        }
        if (!isNullOrEmpty($motorista->getFotoVeiculoBase64())) {
            $motorista->setFotoVeiculo($this->gravarFoto("veiculo", $motorista->getFotoVeiculoBase64()));
        }
    }

    /**
     * @throws Exception
     * @param string $tipo
     * @param string $fotoBase64
     * @return string
     */
    public function gravarFoto($tipo, $fotoBase64) {
        $token = md5(uniqid(rand(), true));
        $dir = UPLOAD_PATH . '/motorista';
        if (!file_exists($dir)) {
            @mkdir($dir);
        }
        $dir .= "/" . $tipo;
        if (!file_exists($dir)) {
            @mkdir($dir);
        }
        if (!file_exists($dir)) {
            throw new Exception(sprintf("O diretório '%s' não existe.", $dir));
        }
        $arquivoFoto = $dir . "/" . $token . '.jpg';
        $data = preg_replace('#^data:image/\w+;base64,#i', '', $fotoBase64);
        file_put_contents($arquivoFoto, base64_decode($data));
        return $tipo . "/" . $token . ".jpg";
    }

    /**
     * @param MotoristaEnvioInfo $envio
     * @return MotoristaRetornoInfo
     * @throws Exception
     */
    public function atualizar($envio)
    {
        $dal = MotoristaDALFactory::create();
        if (!($envio->getCodDisponibilidade() > 0)) {
            $envio->setCodDisponibilidade(MotoristaInfo::DISPONIVEL);
        }

        $regraFrete = FreteBLLFactory::create();
        $frete = $regraFrete->pegarAbertoPorMotorista($envio->getIdMotorista());

        $dal->atualizar($envio);
        $retorno = new MotoristaRetornoInfo();
        $retorno->setIdMotorista($envio->getIdMotorista());
        if (!is_null($frete)) {

            $retorno->setIdFrete($frete->getId());
            $retorno->setCodSituacao($frete->getCodSituacao());
            $regraRota = new RotaBLL();
            if ($regraRota->usaCalculoRota()) {
                $rotas = array($envio->getPosicaoStr());
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
                }
                else {
                    $retorno->adicionarMensagem("Não foi possível calcular a rota.");
                }
            }
            else {
                $rotas = array(new LocalInfo($envio->getLatitude(), $envio->getLongitude()));
                foreach ($frete->listarLocal() as $local) {
                    $rotas[] = new LocalInfo($local->getLatitude(), $local->getLongitude());
                }
                $distancia = $regraRota->calcularDistancia($rotas);
                $tempo = floor((($distancia / 1000) / $regraRota->velocidadeMediaPorHora()) * (60 * 60));

                $retorno->setRotaEncontrada(false);
                $retorno->setDistancia($distancia);
                $retorno->setDistanciaStr(number_format(($distancia / 1000), 1, ",", ".") . "km");
                $retorno->setTempo($tempo);
                $retorno->setTempoStr($regraRota->tempoParaTexto($tempo));
                $retorno->setPolyline(null);
            }
            $situacoes = array(
                FreteInfo::PEGANDO_ENCOMENDA,
                FreteInfo::ENTREGANDO
            );
            if (in_array($frete->getCodSituacao(), $situacoes)) {
                $dalHistorico = FreteHistoricoDALFactory::create();
                $historico = new FreteHistoricoInfo();
                $historico->setIdFrete($frete->getId());
                $historico->setLatitude($envio->getLatitude());
                $historico->setLongitude($envio->getLongitude());
                if (isset($rota)) {
                    $historico->setEndereco($rota->getRoutes()[0]->getLegs()[0]->getStartAddress());
                }
                $dalHistorico->inserir($historico);
            }
        } elseif ($envio->getCodDisponibilidade() == MotoristaInfo::DISPONIVEL) {
            $regraFrete = FreteBLLFactory::create();
            $regraRota = new RotaBLL();
            $fretes = $regraFrete->listarDisponivel($envio->getIdMotorista());
            if (count($fretes) == 0) {
                $retorno->adicionarMensagem("Nenhuma entrega disponível.");
            }
            foreach ($fretes as $frete) {
                if ($regraRota->usaCalculoRota()) {
                    $rotas = array($envio->getPosicaoStr());
                    foreach ($frete->listarLocal() as $local) {
                        $rotas[] = $local->getPosicaoStr();
                    }
                    $rota = $regraRota->calcularRota($rotas);
                    if ($rota->getStatus() == "OK") {
                        $me = new MotoristaFreteInfo();
                        $me->setIdFrete($frete->getId());
                        $me->setDuracaoEncomenda($rota->getRoutes()[0]->getLegs()[0]->getDuration()->getValue());
                        $me->setDuracaoEncomendaStr($rota->getRoutes()[0]->getLegs()[0]->getDuration()->getText());
                        $me->setDistanciaEncomenda($rota->getRoutes()[0]->getLegs()[0]->getDistance()->getValue());
                        $me->setDistanciaEncomendaStr($rota->getRoutes()[0]->getLegs()[0]->getDistance()->getText());

                        $rotas = array();
                        foreach ($frete->listarLocal() as $local) {
                            $rotas[] = $local->getPosicaoStr();
                        }
                        $rota = $regraRota->calcularRota($rotas);
                        if ($rota->getStatus() == "OK") {
                            $me->setDuracao($rota->getRoutes()[0]->getLegs()[0]->getDuration()->getValue());
                            $me->setDuracaoStr($rota->getRoutes()[0]->getLegs()[0]->getDuration()->getText());
                            $me->setDistancia($rota->getRoutes()[0]->getLegs()[0]->getDistance()->getValue());
                            $me->setDistanciaStr($rota->getRoutes()[0]->getLegs()[0]->getDistance()->getText());
                            $me->setEnderecoOrigem($rota->getRoutes()[0]->getLegs()[0]->getStartAddress());
                            $me->setEnderecoDestino($rota->getRoutes()[0]->getLegs()[0]->getEndAddress());
                            $latitude = $rota->getRoutes()[0]->getLegs()[0]->getStartLocation()->getLatitude();
                            $longitude = $rota->getRoutes()[0]->getLegs()[0]->getStartLocation()->getLongitude();
                            $me->setLocalOrigem(new LocalInfo($latitude, $longitude));
                            $latitude = $rota->getRoutes()[0]->getLegs()[0]->getEndLocation()->getLatitude();
                            $longitude = $rota->getRoutes()[0]->getLegs()[0]->getEndLocation()->getLongitude();
                            $me->setLocalDestino(new LocalInfo($latitude, $longitude));
                            if ($frete->getPreco() > 0){
                                $me->setValor($frete->getPreco());
                            }
                            else {
                                $me->setValor($regraFrete->calcularValor($frete));
                            }
                            $retorno->adicionarFrete($me);
                        } else {
                            $retorno->adicionarMensagem("Nenhuma rota para efetuar a entrega.");
                        }
                    } else {
                        $retorno->adicionarMensagem("Nenhuma rota para chegar a encomenda.");
                    }
                }
                else {
                    $me = new MotoristaFreteInfo();
                    $me->setIdFrete($frete->getId());

                    $rotas = array(new LocalInfo($envio->getLatitude(), $envio->getLongitude()));
                    foreach ($frete->listarLocal() as $local) {
                        $rotas[] = new LocalInfo($local->getLatitude(), $local->getLongitude());
                    }
                    $distancia = $regraRota->calcularDistancia($rotas);
                    $tempo = $regraRota->calcularTempo($distancia);

                    $me->setDuracaoEncomenda($tempo);
                    $me->setDuracaoEncomendaStr($regraRota->tempoParaTexto($tempo));
                    $me->setDistanciaEncomenda($distancia);
                    $me->setDistanciaEncomendaStr($regraRota->distanciaParaTexto($distancia));

                    $rotas = array();
                    foreach ($frete->listarLocal() as $local) {
                        $rotas[] = new LocalInfo($local->getLatitude(), $local->getLongitude());
                    }
                    $distancia = $regraRota->calcularDistancia($rotas);
                    $tempo = $regraRota->calcularTempo($distancia);

                    $me->setDuracao($tempo);
                    $me->setDuracaoStr($regraRota->tempoParaTexto($tempo));
                    $me->setDistancia($distancia);
                    $me->setDistanciaStr($regraRota->distanciaParaTexto($distancia));
                    $me->setEnderecoOrigem(null);
                    $me->setEnderecoDestino(null);
                    $me->setLocalOrigem($frete->getOrigem()->getLocal());
                    $me->setLocalDestino($frete->getDestino()->getLocal());
                    if ($frete->getPreco() > 0){
                        $me->setValor($frete->getPreco());
                    }
                    else {
                        $me->setValor($regraFrete->calcularValor($frete));
                    }

                    $retorno->adicionarFrete($me);

                }
            }
        } else {
            $retorno->adicionarMensagem("Motorista não disponível.");
        }
        return $retorno;
    }


    /**
     * @param int $id_motorista
     * @return float
     * @throws Exception
     */
    public function pegarNotaCliente($id_motorista) {
        $dalFrete = FreteDALFactory::create();
        return $dalFrete->pegarNotaCliente($id_motorista);
    }

    /**
     * @param int $id_motorista
     * @return float
     * @throws Exception
     */
    public function pegarNotaMotorista($id_motorista) {
        $dalFrete = FreteDALFactory::create();
        return $dalFrete->pegarNotaMotorista($id_motorista);
    }
}


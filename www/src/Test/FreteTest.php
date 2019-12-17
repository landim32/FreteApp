<?php
namespace Emagine\Frete\Test;

require "config.php";

use Exception;
use phpmailerException;
use joshtronic\LoremIpsum;
use PHPUnit\Framework\TestCase;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Endereco\BLL\CepBLL;
use Emagine\Frete\BLLFactory\FreteBLLFactory;
use Emagine\Frete\BLLFactory\MotoristaBLLFactory;
use Emagine\Frete\BLL\TipoCarroceriaBLL;
use Emagine\Frete\BLL\TipoVeiculoBLL;
use Emagine\Base\Test\TesteUtils;
use Emagine\Login\Test\UsuarioUtils;
use Emagine\Frete\Model\AceiteEnvioInfo;
use Emagine\Frete\Model\AceiteRetornoInfo;
use Emagine\Frete\Model\FreteInfo;
use Emagine\Frete\Model\FreteRetornoInfo;
use Emagine\Frete\Model\MotoristaEnvioInfo;
use Emagine\Frete\Model\MotoristaRetornoInfo;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Frete\Model\MotoristaInfo;
use Emagine\Frete\Model\TipoCarroceriaInfo;
use Emagine\Frete\Model\TipoVeiculoInfo;

final class FreteTest extends TestCase {

    /**
     * FreteTest constructor.
     * @param null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        set_time_limit(600);
    }

    /**
     * @throws Exception
     */
    public function testCriandoNovoPassageiro() {
        $usuario = UsuarioUtils::criarUsuario();

        $regraUsuario = new UsuarioBLL();
        $id_usuario = $regraUsuario->inserir($usuario);

        $this->assertGreaterThanOrEqual(0, $id_usuario);
    }

    /**
     * @throws Exception
     */
    public function testCriandoNovoMotorista() {
        $lipsum = new LoremIpsum();

        $usuario = UsuarioUtils::criarUsuario();

        $regraUsuario = new UsuarioBLL();
        $id_usuario = $regraUsuario->inserir($usuario);

        $this->assertGreaterThanOrEqual(0, $id_usuario);

        $regraTipo = new TipoVeiculoBLL();
        $tipos = $regraTipo->listar();

        $this->assertGreaterThanOrEqual(0, count($tipos));

        /** @var TipoVeiculoInfo $tipoVeiculo */
        $tipoVeiculo = array_values($tipos)[0];

        $regraCarroceria = new TipoCarroceriaBLL();
        $carrocerias = $regraCarroceria->listar();

        $this->assertGreaterThanOrEqual(0, count($carrocerias));

        /** @var TipoCarroceriaInfo $carroceria */
        $carroceria = array_values($carrocerias)[0];

        $motorista = new MotoristaInfo();
        $motorista->setId($id_usuario);
        $motorista->setValorHora(rand(100, 200));
        $motorista->setCNH(TesteUtils::gerarNumeroAleatorio(10));
        $motorista->setAntt(TesteUtils::gerarNumeroAleatorio(10));
        $motorista->setCodDisponibilidade(MotoristaInfo::INDISPONIVEL);
        $motorista->setPlaca(FreteUtils::gerarPlaca());
        $motorista->setIdTipo($tipoVeiculo->getId());
        $motorista->setIdCarroceria($carroceria->getId());
        $motorista->setVeiculo($lipsum->words(1));
        $motorista->setCodSituacao(MotoristaInfo::AGUARDANDO_APROVACAO);

        $regraMotorista = MotoristaBLLFactory::create();
        $regraMotorista->inserir($motorista);
    }

    /**
     * @throws Exception
     */
    public function testVerificandoExistenciaDeUmPassageiroAtivo() {
        $regraUsuario = new UsuarioBLL();
        $usuarios = $regraUsuario->listar(UsuarioInfo::ATIVO);

        $this->assertGreaterThanOrEqual(0, count($usuarios));
    }

    /**
     * @throws Exception
     */
    public function testReprovandoUmMotorista() {
        $regraMotorista = MotoristaBLLFactory::create();
        $motoristas = $regraMotorista->listar();
        $motorista = null;
        foreach ($motoristas as $m) {
            if ($m->getCodSituacao() == MotoristaInfo::AGUARDANDO_APROVACAO) {
                $motorista = $m;
                break;
            }
        }
        $this->assertNotNull($motorista, "Nenhum motorista aguardando aprovação.");
        $motorista->setCodSituacao(MotoristaInfo::REPROVADO);
        $regraMotorista->alterar($motorista);

        $motorista = $regraMotorista->pegar($motorista->getId());
        $this->assertNotNull($motorista);
        $this->assertEquals($motorista->getCodSituacao(), MotoristaInfo::REPROVADO);
    }

    /**
     * @throws Exception
     */
    public function testAprovandoUmMotorista() {
        $regraMotorista = MotoristaBLLFactory::create();
        $motoristas = $regraMotorista->listar();
        $motorista = null;
        foreach ($motoristas as $m) {
            if ($m->getCodSituacao() == MotoristaInfo::REPROVADO) {
                $motorista = $m;
                break;
            }
        }
        $this->assertNotNull($motorista, "Nenhum motorista aguardando aprovação.");
        $motorista->setCodSituacao(MotoristaInfo::ATIVO);
        $regraMotorista->alterar($motorista);

        $motorista = $regraMotorista->pegar($motorista->getId());
        $this->assertNotNull($motorista);
        $this->assertEquals($motorista->getCodSituacao(), MotoristaInfo::ATIVO);
    }

    /**
     * @throws Exception
     */
    public function testCriarNovoFrete() {
        $frete = FreteUtils::gerarFrete($this);
        $regraFrete = FreteBLLFactory::create();
        $regraFrete->inserir($frete);
    }

    /**
     * @throws Exception
     */
    public function testAceitandoFrete() {
        $frete = FreteUtils::pegarFrete($this, FreteInfo::PROCURANDO_MOTORISTA);
        $motorista = FreteUtils::pegarMotorista($this);

        $regraFrete = FreteBLLFactory::create();
        $aceite = new AceiteEnvioInfo();
        $aceite->setIdFrete($frete->getId());
        $aceite->setIdMotorista($motorista->getId());
        $aceite->setAceite(true);
        $retorno = $regraFrete->aceitar($aceite);

        $this->assertNotNull($retorno);
        $this->assertInstanceOf(AceiteRetornoInfo::class, $retorno);
        $mensagem = sprintf("O frete não foi aceito. Mensagem='%s'.", $retorno->getMensagem());
        $this->assertTrue($retorno->getAceite(), $mensagem);

        $frete = $regraFrete->pegar($retorno->getIdFrete());
        $this->assertNotNull($frete);
        $this->assertNotEquals($frete->getCodSituacao(), FreteInfo::PROCURANDO_MOTORISTA);
        $this->assertEquals($frete->getIdMotorista(), $motorista->getId());
    }

    /**
     * @throws Exception
     */
    public function testAprovandoMotoristaARealizarUmFrete() {
        $frete = FreteUtils::pegarFrete($this, FreteInfo::APROVANDO_MOTORISTA);
        if (is_null($frete)) {
            $frete = FreteUtils::pegarFrete($this, FreteInfo::AGUARDANDO_PAGAMENTO);
        }
        $this->assertNotNull($frete);
        $motorista = $frete->getMotorista();
        $this->assertNotNull($motorista);
        $preco = $motorista->getValorHora() * ($frete->getPrevisao() / 3600);
        $frete->setPreco($preco);
        $frete->setCodSituacao(FreteInfo::AGUARDANDO);

        $regraFrete = FreteBLLFactory::create();
        $regraFrete->alterar($frete);

        $frete = $regraFrete->pegar($frete->getId());

        $this->assertNotNull($frete);
        $this->assertEquals($frete->getCodSituacao(), FreteInfo::AGUARDANDO);
    }

    public function testIniciandoAExecucaoDoFrete() {
        $frete = FreteUtils::pegarFrete($this, FreteInfo::AGUARDANDO);
        $this->assertNotNull($frete);
        $frete->setCodSituacao(FreteInfo::PEGANDO_ENCOMENDA);
        $regraFrete = FreteBLLFactory::create();
        $regraFrete->alterar($frete);

        $frete = $regraFrete->pegar($frete->getId());

        $this->assertNotNull($frete);
        $this->assertEquals($frete->getCodSituacao(), FreteInfo::PEGANDO_ENCOMENDA);
    }

    public function testConfirmandoQuePegouAEncomendaEEfetuandoAEntrega() {
        $frete = FreteUtils::pegarFrete($this, FreteInfo::PEGANDO_ENCOMENDA);
        $this->assertNotNull($frete);
        $frete->setCodSituacao(FreteInfo::ENTREGANDO);
        $regraFrete = FreteBLLFactory::create();
        $regraFrete->alterar($frete);

        $frete = $regraFrete->pegar($frete->getId());

        $this->assertNotNull($frete);
        $this->assertEquals($frete->getCodSituacao(), FreteInfo::ENTREGANDO);
    }

    /**
     * @throws Exception
     */
    public function testExecutandoViagem() {

        $regraCep = new CepBLL();

        $regraMotorista = MotoristaBLLFactory::create();
        $regraFrete = FreteBLLFactory::create();
        $frete = FreteUtils::pegarFrete($this, FreteInfo::ENTREGANDO);
        $this->assertNotNull($frete);

        $motorista = $frete->getMotorista();
        $this->assertNotNull($motorista);

        $origem = $frete->getOrigem();
        $destino = $frete->getDestino();
        $diffLatitude = abs($destino->getLatitude() - $origem->getLatitude()) / 10.0;
        $diffLongitude = abs($destino->getLongitude() - $origem->getLongitude()) / 10.0;

        $latitude = floatval($origem->getLatitude());
        $longitude = floatval($origem->getLongitude());

        for ($i = 0; $i <= 9; $i++) {
            $latitude += ($destino->getLatitude() >= 0) ? $diffLatitude : -$diffLatitude;
            $longitude += ($destino->getLongitude() >= 0) ? $diffLongitude : -$diffLongitude;

            $cep = $regraCep->pegarCepMaisProximo($latitude, $longitude);
            $endereco = $regraCep->pegarPorCep($cep);
            $this->assertNotNull($endereco);
            $latitude = floatval($endereco->getLatitude());
            $longitude = floatval($endereco->getLongitude());

            $envio = new MotoristaEnvioInfo();
            $envio->setIdMotorista($motorista->getId());
            $envio->setLatitude($latitude);
            $envio->setLongitude($longitude);
            $envio->setCodDisponibilidade(MotoristaInfo::DISPONIVEL);
            $retornoMotorista = $regraMotorista->atualizar($envio);

            $this->assertNotNull($retornoMotorista);
            $this->assertInstanceOf(MotoristaRetornoInfo::class, $retornoMotorista);

            $retornoFrete = $regraFrete->atualizar($frete->getId());

            $this->assertNotNull($retornoFrete);
            $this->assertInstanceOf(FreteRetornoInfo::class, $retornoFrete);
        }
    }

    public function testClienteAcompanhandoOFrete() {
        $regraFrete = FreteBLLFactory::create();
        $frete = FreteUtils::pegarFrete($this, FreteInfo::ENTREGANDO);
        $this->assertNotNull($frete);
        $retorno = $regraFrete->atualizar($frete->getId());
        $this->assertNotNull($retorno);
        $this->assertInstanceOf(FreteRetornoInfo::class, $retorno);
    }

    public function testEntregandoFrete() {
        $frete = FreteUtils::pegarFrete($this, FreteInfo::ENTREGANDO);
        $this->assertNotNull($frete);
        $frete->setCodSituacao(FreteInfo::ENTREGUE);
        $regraFrete = FreteBLLFactory::create();
        $regraFrete->alterar($frete);

        $frete = $regraFrete->pegar($frete->getId());

        $this->assertNotNull($frete);
        $this->assertEquals($frete->getCodSituacao(), FreteInfo::ENTREGUE);
    }

    public function testDandoNotaAoPassageiro() {
        $nota = rand(1,5);
        $frete = FreteUtils::pegarFrete($this, FreteInfo::ENTREGUE);
        $this->assertNotNull($frete);
        $frete->setNotaFrete($nota);
        $regraFrete = FreteBLLFactory::create();
        $regraFrete->alterar($frete);

        $frete = $regraFrete->pegar($frete->getId());

        $this->assertNotNull($frete);
        $this->assertEquals($frete->getNotaFrete(), $nota);
    }

    public function testConfirmandoAEntregaPeloPassageiro() {
        $frete = FreteUtils::pegarFrete($this, FreteInfo::ENTREGUE);
        $this->assertNotNull($frete);
        $frete->setCodSituacao(FreteInfo::ENTREGA_CONFIRMADA);
        $regraFrete = FreteBLLFactory::create();
        $regraFrete->alterar($frete);
        $frete = $regraFrete->pegar($frete->getId());
        $this->assertNotNull($frete);
        $this->assertEquals($frete->getCodSituacao(), FreteInfo::ENTREGA_CONFIRMADA);
    }

    public function testDandoNotaAoMotorista() {
        $nota = rand(1,5);
        $frete = FreteUtils::pegarFrete($this, FreteInfo::ENTREGA_CONFIRMADA);
        $this->assertNotNull($frete);
        $frete->setNotaMotorista($nota);
        $regraFrete = FreteBLLFactory::create();
        $regraFrete->alterar($frete);

        $frete = $regraFrete->pegar($frete->getId());

        $this->assertNotNull($frete);
        $this->assertEquals($frete->getNotaMotorista(), $nota);
    }
}
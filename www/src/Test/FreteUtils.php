<?php
namespace Emagine\Frete\Test;

use Emagine\Frete\BLLFactory\FreteBLLFactory;
use Emagine\Frete\Model\MotoristaInfo;
use Exception;
use joshtronic\LoremIpsum;
use PHPUnit\Framework\TestCase;
use Emagine\Base\Test\TesteUtils;
use Emagine\Endereco\Model\BairroInfo;
use Emagine\Endereco\Model\CidadeInfo;
use Emagine\Endereco\Model\EnderecoInfo;
use Emagine\Endereco\Model\UfInfo;
use Emagine\Frete\BLL\TipoCarroceriaBLL;
use Emagine\Frete\BLL\TipoVeiculoBLL;
use Emagine\Frete\BLLFactory\MotoristaBLLFactory;
use Emagine\Frete\Model\FreteLocalInfo;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Endereco\BLL\CepBLL;
use Emagine\Frete\Model\FreteInfo;
use Webmozart\Assert\Assert;

class FreteUtils
{
    /**
     * @return string
     */
    public static function gerarPlaca() {
        $p = str_split("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz");
        shuffle($p);
        $p = array_values($p);
        return $p[0] . $p[1] . $p[2] . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9);
    }

    /**
     * @throws Exception
     * @param TestCase $testCase
     * @return UsuarioInfo
     */
    public static function pegarPassageiro(TestCase $testCase) {
        $regraUsuario = new UsuarioBLL();
        $regraMotorista = MotoristaBLLFactory::create();
        $usuarios = $regraUsuario->listar(UsuarioInfo::ATIVO);

        $testCase->assertGreaterThan(0, count($usuarios), "Nenhum usuário ativo disponível.");

        shuffle($usuarios);

        $usuario = null;
        foreach ($usuarios as $u) {
            $motorista = $regraMotorista->pegar($u->getId());
            if (is_null($motorista)) {
                $usuario = $u;
                break;
            }
        }

        $testCase->assertNotNull($usuario, "Nenhum usuário ativo disponível.");
        return $usuario;
    }

    /**
     * @param TestCase $testCase
     * @return MotoristaInfo
     * @throws Exception
     */
    public static function pegarMotorista(TestCase $testCase) {
        $regraMotorista = MotoristaBLLFactory::create();
        $motoristas = $regraMotorista->listar();
        $testCase->assertGreaterThan(0, count($motoristas), "Nenhum motorista disponível.");
        shuffle($motoristas);
        $motorista = null;
        foreach ($motoristas as $m) {
            if ($m->getCodSituacao() == MotoristaInfo::ATIVO) {
                $motorista = $m;
                break;
            }
        }
        $testCase->assertNotNull($motorista, "Nenhum motorista ativo disponível.");
        return $motorista;
    }

    /**
     * @param TestCase $testCase
     * @return UfInfo
     * @throws Exception
     */
    public static function pegarUf(TestCase $testCase) {
        $regraCep = new CepBLL();
        $estados = $regraCep->listarUf();
        $testCase->assertGreaterThan(0, count($estados), "Nenhum usuário ativo disponível.");
        shuffle($estados);
        /** @var UfInfo $uf */
        $uf = array_values($estados)[0];
        $testCase->assertNotNull($uf, "Nenhum estado disponível.");
        return $uf;
    }

    /**
     * @throws Exception
     * @param TestCase $testCase
     * @param string $uf
     * @return CidadeInfo
     */
    public static function pegarCidade(TestCase $testCase, $uf) {
        $regraCep = new CepBLL();
        $cidades = $regraCep->buscarCidadePorUf("", $uf);
        $testCase->assertGreaterThan(0, count($cidades), "Nenhum usuário ativo disponível.");
        shuffle($cidades);
        /** @var CidadeInfo $cidade */
        $cidade = array_values($cidades)[0];
        $testCase->assertNotNull($cidade, "Nenhuma cidade disponível.");
        return $cidade;
    }

    /**
     * @param TestCase $testCase
     * @param CidadeInfo $cidade
     * @return BairroInfo
     * @throws Exception
     */
    public static function pegarBairro(TestCase $testCase, CidadeInfo $cidade) {
        $regraCep = new CepBLL();
        $bairros = $regraCep->buscarBairroPorIdCidade("", $cidade->getId());
        $mensagem = sprintf("Nenhum bairro na cidade %s.", $cidade->getNome());
        $testCase->assertGreaterThan(0, count($bairros), $mensagem);
        shuffle($bairros);
        /** @var BairroInfo $bairro */
        $bairro = array_values($bairros)[0];
        $testCase->assertNotNull($bairro, "Nenhum bairro disponível.");
        return $bairro;
    }

    /**
     * @param TestCase $testCase
     * @param BairroInfo $bairro
     * @return EnderecoInfo
     * @throws Exception
     */
    public static function pegarLogradouro(TestCase $testCase, BairroInfo $bairro) {
        $regraCep = new CepBLL();
        $logradouros = $regraCep->buscarLogradouroPorIdBairro("", $bairro->getId());
        $mensagem = sprintf("Nenhum logradouro para o bairro %s.", $bairro->getNome());
        $testCase->assertGreaterThan(0, count($logradouros), $mensagem);
        shuffle($logradouros);
        /** @var EnderecoInfo $logradouro */
        $logradouro = array_values($logradouros)[0];
        $testCase->assertNotNull($logradouro, "Nenhum logradouro disponível.");
        return $logradouro;
    }


    /**
     * @param TestCase $testCase
     * @param UfInfo $uf
     * @param CidadeInfo $cidade
     * @return EnderecoInfo
     * @throws Exception
     */
    public static function pegarEnderecoAleatorio(TestCase $testCase, UfInfo $uf, CidadeInfo &$cidade) {
        $regraCep = new CepBLL();

        $tentativas = 0;
        $endereco = null;
        do {
            $tentativas++;

            $bairros = $regraCep->buscarBairroPorIdCidade("", $cidade->getId());
            if (count($bairros) == 0) {
                $cidade = self::pegarCidade($testCase, $uf->getUf());
                continue;
            }
            shuffle($bairros);
            /** @var BairroInfo $bairro */
            $bairro = array_values($bairros)[0];

            $logradouros = $regraCep->buscarLogradouroPorIdBairro("", $bairro->getId());
            if (count($logradouros) == 0) {
                continue;
            }
            shuffle($logradouros);
            /** @var EnderecoInfo $logradouro */
            $endereco = array_values($logradouros)[0];

        } while (is_null($endereco) && $tentativas < 50);

        $testCase->assertNotNull($endereco, "Nenhum endereço encontrado após 50 tentativas.");

        return $endereco;
    }

    /**
     * @param TestCase $testCase
     * @return FreteInfo
     * @throws Exception
     */
    public static function gerarFrete(TestCase $testCase) {
        $lipsum = new LoremIpsum();

        $usuario = self::pegarPassageiro($testCase);
        $dataRetirada = time() + (rand(0,3) * (60 * 60 * 24));
        $dataEntrega = $dataRetirada + (rand(0,3) * (60 * 60 * 24));

        $frete = new FreteInfo();
        $frete->setIdUsuario($usuario->getId());
        $frete->setPreco(0);
        $frete->setDataRetirada(date( 'Y-m-d H:i:s', $dataRetirada));
        $frete->setDataEntrega(date( 'Y-m-d H:i:s', $dataEntrega));
        $frete->setLargura(rand(0, 100));
        $frete->setAltura(rand(0, 100));
        $frete->setProfundidade(rand(0, 100));
        $frete->setPeso(rand(0, 100));
        $frete->setObservacao($lipsum->paragraph());
        $frete->setCodSituacao(FreteInfo::PROCURANDO_MOTORISTA);

        $regraVeiculo = new TipoVeiculoBLL();
        $veiculos = $regraVeiculo->listar();
        $testCase->assertGreaterThan(0, count($veiculos), "Nenhum tipo de veículo encontrado.");
        shuffle($veiculos);

        $veiculos = array_slice($veiculos, 0, rand(1,4));
        foreach ($veiculos as $veiculo) {
            $frete->adicionarTipoVeiculo($veiculo);
        }

        $regraCarroceria = new TipoCarroceriaBLL();
        $carrocerias = $regraCarroceria->listar();
        shuffle($carrocerias);

        $carrocerias = array_slice($carrocerias, 0, rand(0,2));
        foreach ($carrocerias as $carroceria) {
            $frete->adicionarCarroceria($carroceria);
        }

        $quantidadeLocal = rand(2,4);
        $tentativas = 0;
        do {
            $frete->limparLocal();
            $tentativas++;
            $uf = self::pegarUf($testCase);
            $cidade = self::pegarCidade($testCase, $uf->getUf());
            for ($i = 1; $i <= $quantidadeLocal; $i++) {
                $endereco = self::pegarEnderecoAleatorio($testCase, $uf, $cidade);

                $local = new FreteLocalInfo();
                $local->setCep($endereco->getCep());
                $local->setUf($endereco->getUf());
                $local->setCidade($endereco->getCidade());
                $local->setBairro($endereco->getBairro());
                $local->setLogradouro($endereco->getLogradouro());
                $local->setComplemento($endereco->getComplemento());
                $local->setNumero(TesteUtils::gerarNumeroAleatorio(3));
                $local->setLatitude(floatval($endereco->getLatitude()));
                $local->setLongitude(floatval($endereco->getLongitude()));
                if ($i == 1) {
                    $local->setCodTipo(FreteLocalInfo::ORIGEM);
                } elseif ($i == $quantidadeLocal) {
                    $local->setCodTipo(FreteLocalInfo::DESTINO);
                } else {
                    $local->setCodTipo(FreteLocalInfo::PARADA);
                }
                $frete->adicionarLocal($local);
            }
            $o = $frete->getOrigem();
            $d = $frete->getDestino();
        }
        while($o->getPosicaoStr() == $d->getPosicaoStr() && $tentativas < 20);
        $testCase->assertGreaterThanOrEqual($tentativas, 20);
        return $frete;
    }

    public static function pegarFrete(TestCase $testCase, $codSituacao = 0) {
        $regraFrete = FreteBLLFactory::create();
        $situacoes = $regraFrete->listarSituacao();
        $fretes = $regraFrete->listar(0, 0, $codSituacao);
        shuffle($fretes);
        /** @var FreteInfo $frete */
        $frete = array_values($fretes)[0];
        if ($codSituacao > 0) {
            $situacao = $situacoes[$codSituacao];
            $mensagem = sprintf("Nenhum frete com a situaçao '%s'.", $situacao);
        }
        else {
            $mensagem = "Nenhum frete encontrado.";
        }
        $testCase->assertNotNull($frete, $mensagem);
        return $frete;
    }
}
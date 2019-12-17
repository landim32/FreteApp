<?php
namespace Emagine\Frete;

use Emagine\Frete\Model\LocalInfo;
use Exception;
use Emagine\Base\EmagineApp;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Frete\BLLFactory\FreteBLLFactory;
use Emagine\Frete\BLLFactory\MotoristaBLLFactory;
use Emagine\Frete\BLL\TipoCarroceriaBLL;
use Emagine\Frete\BLL\TipoVeiculoBLL;
use Emagine\Frete\BLL\DisponibilidadeBLL;
use Emagine\Frete\Model\DisponibilidadeInfo;
use Emagine\Frete\Model\AceiteEnvioInfo;
use Emagine\Frete\Model\FreteInfo;
use Emagine\Frete\Model\MotoristaEnvioInfo;
use Emagine\Frete\Model\MotoristaInfo;

$app = EmagineApp::getApp();

$app->group('/api/motorista', function () use ($app) {

    $app->put('/atualizar', function (Request $request, Response $response, $args) {
        $json = json_decode($request->getBody()->getContents());
        $envio = MotoristaEnvioInfo::fromJson($json);
        $bll = MotoristaBLLFactory::create();
        $retorno = $bll->atualizar($envio);
        return $response->withJson($retorno);
    });

    $app->get('/listar', function (Request $request, Response $response, $args) {
        $bll = MotoristaBLLFactory::create();
        $motoristas = $bll->listar();
        return $response->withJson($motoristas);
    });

    $app->get('/pegar/{id_usuario}', function (Request $request, Response $response, $args) {
        $bll = MotoristaBLLFactory::create();
        $motorista = $bll->pegar($args['id_usuario']);
        return $response->withJson($motorista);
    });

    $app->get('/nota/cliente/{id_usuario}', function (Request $request, Response $response, $args) {
        try {
            $regraMotorista = MotoristaBLLFactory::create();
            $nota = $regraMotorista->pegarNotaCliente($args['id_frete']);
            $body = $response->getBody();
            $body->write($nota);
            return $response->withStatus(200);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->get('/nota/{id_usuario}', function (Request $request, Response $response, $args) {
        try {
            $regraMotorista = MotoristaBLLFactory::create();
            $nota = $regraMotorista->pegarNotaMotorista($args['id_frete']);
            $body = $response->getBody();
            $body->write($nota);
            return $response->withStatus(200);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->put('/inserir', function (Request $request, Response $response, $args) {
        try {
            $json = json_decode($request->getBody()->getContents());
            $motorista = MotoristaInfo::fromJson($json);
            $regraMotorista = MotoristaBLLFactory::create();
            $id_motorista = $regraMotorista->inserir($motorista);
            $body = $response->getBody();
            $body->write($id_motorista);
            return $response->withStatus(200);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->put('/alterar', function (Request $request, Response $response, $args) {
        try {
            $json = json_decode($request->getBody()->getContents());
            $motorista = MotoristaInfo::fromJson($json);
            $regraMotorista = MotoristaBLLFactory::create();
            $regraMotorista->alterar($motorista);
            return $response->withStatus(200);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->get('/excluir/{id_usuario}', function (Request $request, Response $response, $args) {
        try {
            $regraMotorista = MotoristaBLLFactory::create();
            $regraMotorista->excluir($args['id_usuario']);
            return $response->withStatus(200);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });
});

$app->group('/api/frete', function () use ($app) {

    $app->get('/atualizar/{id_frete}', function (Request $request, Response $response, $args) {
        try {
            $regraFrete = FreteBLLFactory::create();
            $retorno = $regraFrete->atualizar($args['id_frete']);
            return $response->withJson($retorno);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->put('/aceitar', function (Request $request, Response $response, $args) {
        try {
            $json = json_decode($request->getBody()->getContents());
            $envio = AceiteEnvioInfo::fromJson($json);
            $regraFrete = FreteBLLFactory::create();
            $retorno = $regraFrete->aceitar($envio);
            return $response->withJson($retorno)->withStatus(200);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->get('/historico/{id_frete}', function (Request $request, Response $response, $args) {
        try {
            $regraFrete = FreteBLLFactory::create();
            $historicos = $regraFrete->listarHistorico($args['id_frete']);
            return $response->withJson($historicos);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->get('/listar[/{id_usuario}[/{id_motorista}[/{cod_situacao}]]]', function (Request $request, Response $response, $args) {
        try {
            $regraFrete = FreteBLLFactory::create();
            $id_usuario = intval($args['id_usuario']);
            $id_motorista = intval($args['id_motorista']);
            $cod_situacao = intval($args['cod_situacao']);
            $fretes = $regraFrete->listar($id_usuario, $id_motorista, $cod_situacao);
            return $response->withJson($fretes);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->get('/listar-disponivel[/{id_usuario}]', function (Request $request, Response $response, $args) {
        try {
            $regraFrete = FreteBLLFactory::create();
            $id_usuario = 0;
            if (array_key_exists("id_usuario", $args)) {
                $id_usuario = intval($args['id_usuario']);
            }
            $fretes = $regraFrete->listarDisponivel($id_usuario);
            return $response->withJson($fretes);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->get('/pegar/{id_frete}', function (Request $request, Response $response, $args) {
        $regraFrete = FreteBLLFactory::create();
        $frete = $regraFrete->pegar($args['id_frete']);
        return $response->withJson($frete);
    });

    $app->put('/orcar', function (Request $request, Response $response, $args) {
        try {
            $json = json_decode($request->getBody()->getContents());
            $regraFrete = FreteBLLFactory::create();
            $frete = FreteInfo::fromJson($json);
            $frete = $regraFrete->orcar($frete);
            return $response->withJson($frete);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->put('/inserir', function (Request $request, Response $response, $args) {
        try {
            $json = json_decode($request->getBody()->getContents());
            $frete = FreteInfo::fromJson($json);
            $regraFrete = FreteBLLFactory::create();
            $id_frete = $regraFrete->inserir($frete);
            $body = $response->getBody();
            $body->write($id_frete);
            return $response->withStatus(200);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->put('/alterar', function (Request $request, Response $response, $args) {
        try {
            $json = json_decode($request->getBody()->getContents());
            $frete = FreteInfo::fromJson($json);
            $regraFrete = FreteBLLFactory::create();
            $regraFrete->alterar($frete);
            return $response->withStatus(200);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->get('/situacao/{id_frete}/{cod_situacao}', function (Request $request, Response $response, $args) {
        try {
            $regraFrete = FreteBLLFactory::create();
            $frete = $regraFrete->pegar($args['id_frete']);
            $frete->setCodSituacao($args['cod_situacao']);
            $regraFrete->alterar($frete);
            return $response->withStatus(200);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->get('/excluir/{id_frete}', function (Request $request, Response $response, $args) {
        try {
            $regraFrete = FreteBLLFactory::create();
            $regraFrete->excluir($args['id_frete']);
            return $response->withStatus(200);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });
});

$app->group('/api/disponibilidade', function () use ($app) {

    $app->get('/listar/{id_usuario}', function (Request $request, Response $response, $args) {
        try {
            $regraDisponibilidade = new DisponibilidadeBLL();
            $disponibilidades = $regraDisponibilidade->listar($args['id_usuario']);
            return $response->withJson($disponibilidades);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->get('/pegar/{id_disponibilidade}', function (Request $request, Response $response, $args) {
        try {
            $regraDisponibilidade = new DisponibilidadeBLL();
            $disponibilidade = $regraDisponibilidade->pegar($args['id_disponibilidade']);
            return $response->withJson($disponibilidade);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->put('/inserir', function (Request $request, Response $response, $args) {
        try {
            $json = json_decode($request->getBody()->getContents());
            $disponibilidade = DisponibilidadeInfo::fromJson($json);
            $regraDisponibilidade = new DisponibilidadeBLL();
            $id_disponibilidade = $regraDisponibilidade->inserir($disponibilidade);
            $body = $response->getBody();
            $body->write($id_disponibilidade);
            return $response->withStatus(200);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->put('/alterar', function (Request $request, Response $response, $args) {
        try {
            $json = json_decode($request->getBody()->getContents());
            $disponibilidade = DisponibilidadeInfo::fromJson($json);
            $regraDisponibilidade = new DisponibilidadeBLL();
            $regraDisponibilidade->alterar($disponibilidade);
            return $response->withStatus(200);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->get('/excluir/{id_disponibilidade}', function (Request $request, Response $response, $args) {
        try {
            $regraDisponibilidade = new DisponibilidadeBLL();
            $regraDisponibilidade->excluir($args['id_disponibilidade']);
            return $response->withStatus(200);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });
});

$app->get('/api/veiculo-tipo/listar', function (Request $request, Response $response, $args) {
    $bll = new TipoVeiculoBLL();
    $tipos = $bll->listar();
    return $response->withJson($tipos);
});

$app->get('/api/carroceria/listar', function (Request $request, Response $response, $args) {
    $bll = new TipoCarroceriaBLL();
    $carrocerias = $bll->listar();
    return $response->withJson($carrocerias);
});
<?php
namespace Emagine\Frete;

use Exception;
use Emagine\Base\EmagineApp;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Frete\BLLFactory\FreteBLLFactory;
use Emagine\Frete\BLLFactory\FreteHistoricoBLLFactory;
use Emagine\Frete\BLLFactory\MotoristaBLLFactory;
use Emagine\Frete\BLL\RotaBLL;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\Model\UsuarioInfo;

$app = EmagineApp::getApp();

$app->group('/cliente', function () use ($app) {
    $app->get('/listar', function (Request $request, Response $response, $args) use ($app) {
        $regraUsuario = new UsuarioBLL();
        $usuarios = array();

        foreach ($regraUsuario->listar() as $usuario) {
            if (!$usuario->temPermissao(UsuarioInfo::ADMIN)) {
                $usuarios[] = $usuario;
            }
        }

        $args['app'] = $app;
        $args['usuarios'] = $usuarios;

        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');
        /** @var PhpRenderer $rendererFrete */
        $rendererFrete = $this->get('frete');
        $response = $rendererMain->render($response, 'header.php', $args);
        $response = $rendererFrete->render($response, 'usuario-lista.php', $args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });

    $app->get('/{slug}', function (Request $request, Response $response, $args) use ($app) {
        $args['app'] = $app;
        $bll = new UsuarioBLL();
        $args['usuario'] = $bll->pegarPorSlug($args["slug"]);
        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');
        /** @var PhpRenderer $rendererFrete */
        $rendererFrete = $this->get('frete');
        $response = $rendererMain->render($response, 'header.php', $args);
        $response = $rendererFrete->render($response, 'usuario.php', $args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });
});

$app->group('/motorista', function () use ($app) {

    $app->get('/listar', function (Request $request, Response $response, $args) use ($app) {
        $args['app'] = $app;
        $bll = MotoristaBLLFactory::create();
        $args['motoristas'] = $bll->listar();
        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');
        /** @var PhpRenderer $rendererFrete */
        $rendererFrete = $this->get('frete');
        $response = $rendererMain->render($response, 'header.php', $args);
        $response = $rendererFrete->render($response, 'motorista-lista.php', $args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });

    $app->get('/{slug}/situacao/{cod_situacao}', function (Request $request, Response $response, $args) use ($app) {
        $regraUsuario = new UsuarioBLL();
        $regraMotorista = MotoristaBLLFactory::create();
        $usuario = $regraUsuario->pegarPorSlug($args["slug"]);
        $motorista = $regraMotorista->pegar($usuario->getId());

        $cod_situacao = intval($args["cod_situacao"]);
        $motorista->setCodSituacao($cod_situacao);
        $regraMotorista->alterar($motorista);

        $url = $app->getBaseUrl() . "/motorista/" . $usuario->getSlug();
        return $response->withStatus(302)->withHeader('Location', $url);
    });

    $app->get('/excluir/{id_usuario}', function (Request $request, Response $response, $args) use ($app) {
        $id_usuario = intval($args['id_usuario']);
        try {
            $regraMotorista = MotoristaBLLFactory::create();
            $regraMotorista->excluir($id_usuario);
            $url = $app->getBaseUrl() . "/motorista/listar";
            return $response->withStatus(302)->withHeader('Location', $url);
        }
        catch (Exception $e) {
            $regraUsuario = new UsuarioBLL();
            $usuario = $regraUsuario->pegar($id_usuario);
            $url = $app->getBaseUrl() . "/motorista/" . $usuario->getSlug();
            $url .= "?erro=" . urlencode($e->getMessage());
            return $response->withStatus(302)->withHeader('Location', $url);
        }
    });

    $app->get('/{slug}', function (Request $request, Response $response, $args) use ($app) {
        $regraUsuario = new UsuarioBLL();
        $regraMotorista = MotoristaBLLFactory::create();
        $usuario = $regraUsuario->pegarPorSlug($args["slug"]);
        $motorista = $regraMotorista->pegar($usuario->getId());

        $args['app'] = $app;
        $args['motorista'] = $motorista;

        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');
        /** @var PhpRenderer $rendererFrete */
        $rendererFrete = $this->get('frete');
        $response = $rendererMain->render($response, 'header.php', $args);
        $response = $rendererFrete->render($response, 'motorista.php', $args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });
});

$app->group('/frete', function () use ($app) {

    $app->get('/listar', function (Request $request, Response $response, $args) use ($app) {
        $args['app'] = $app;
        $bll = FreteBLLFactory::create();
        $args['fretes'] = $bll->listar();
        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');
        /** @var PhpRenderer $rendererFrete */
        $rendererFrete = $this->get('frete');
        $response = $rendererMain->render($response, 'header.php', $args);
        $response = $rendererFrete->render($response, 'frete-lista.php', $args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });

    $app->get('/email/{id_frete}', function (Request $request, Response $response, $args) use ($app) {
        $regraRota = new RotaBLL();
        $regraFrete = FreteBLLFactory::create();
        $regraHistorico = FreteHistoricoBLLFactory::create();

        $id_frete = intval($args['id_frete']);
        $frete = $regraFrete->pegar($id_frete);
        $historicos = $regraHistorico->listar($id_frete);

        $urlMapaPrevisao = $regraFrete->gerarMapaURL($frete, 280, 200);
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

        $args['app'] = $app;
        $args['frete'] = $frete;
        $args['historicos'] = $historicos;
        $args['distancia'] = $distancia;
        $args['distanciaStr'] = $distanciaStr;
        $args['preco'] = $preco;
        $args['tempo'] = $tempo;
        $args['tempoStr'] = $tempoStr;
        $args['tempoExecutado'] = $tempoExecutado;
        $args['tempoExecutadoStr'] = $tempoExecutadoStr;
        $args['urlMapaPrevisao'] = $urlMapaPrevisao;
        $args['urlMapaExecutado'] = $urlMapaExecutado;

        /** @var PhpRenderer $renderer */
        $renderer = $this->get('frete');
        $response = $renderer->render($response, 'frete-email.php', $args);
        return $response;
    });

    $app->get('/{id_frete}', function (Request $request, Response $response, $args) use ($app) {
        $regraFrete = FreteBLLFactory::create();
        $regraHistorico = FreteHistoricoBLLFactory::create();
        $id_frete = intval($args['id_frete']);
        $frete = $regraFrete->pegar($id_frete);
        $historicos = $regraHistorico->listar($id_frete);

        $urlMapaPrevisao = $regraFrete->gerarMapaURL($frete);
        $urlMapaExecutado = null;
        if (count($historicos) >= 2) {
            $urlMapaExecutado = $regraHistorico->gerarMapaURL($historicos);
        }

        $args['app'] = $app;
        $args['frete'] = $frete;
        $args['historicos'] = $historicos;
        $args['urlMapaPrevisao'] = $urlMapaPrevisao;
        $args['urlMapaExecutado'] = $urlMapaExecutado;

        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');
        /** @var PhpRenderer $rendererFrete */
        $rendererFrete = $this->get('frete');

        $response = $rendererMain->render($response, 'header.php', $args);
        $response = $rendererFrete->render($response, 'frete.php', $args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });

});
<?php
namespace Emagine\Frete;

use Exception;
use Emagine\Base\EmagineApp;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Frete\BLLFactory\MotoristaBLLFactory;
use Emagine\Frete\MaisCargas\BLL\FreteFaturaBLL;
use Emagine\Frete\MaisCargas\BLL\MotoristaBLL;

$app = EmagineApp::getApp();

$app->group('/api/motorista', function () use ($app) {
    $app->put('/logar', function (Request $request, Response $response, $args) {
        try {
            $json = json_decode($request->getBody()->getContents());
            /** @var MotoristaBLL $regraMotorista */
            $regraMotorista = MotoristaBLLFactory::create();
            $id_motorista = $regraMotorista->logar($json->email, $json->senha);
            $body = $response->getBody();
            $body->write($id_motorista);
            return $response->withStatus(200);
        } catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });
});

$app->group('/api/fatura', function () use ($app) {

    $app->get('/listar/{id_usuario}', function (Request $request, Response $response, $args) {
        try {
            $regraFatura = new FreteFaturaBLL();
            $faturas = $regraFatura->listar($args['id_usuario']);
            return $response->withJson($faturas);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });
});
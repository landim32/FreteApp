<?php

namespace Emagine\CRM;

use Exception;
use Emagine\Base\EmagineApp;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\CRM\BLL\ClienteBLL;
use Emagine\CRM\Model\ClienteInfo;

$app = EmagineApp::getApp();


$app->group('/api/cliente', function () use ($app) {

    $app->get('/listar[/{id_usuario}]', function (Request $request, Response $response, $args) {
        $regraCliente = new ClienteBLL();
        $id_usuario = intval($args['id_usuario']);
        $clientes = $regraCliente->listar($id_usuario, ClienteInfo::ATIVO);
        return $response->withJson($clientes);
    });

    $app->get('/pegar/{id_cliente}', function (Request $request, Response $response, $args) {
        $regraCliente = new ClienteBLL();
        $cliente = $regraCliente->pegar($args['id_cliente']);
        return $response->withJson($cliente);
    });

    $app->put('/pegar-por-email', function (Request $request, Response $response, $args) {
        $json = json_decode($request->getBody()->getContents());
        $regraCliente = new ClienteBLL();
        $cliente = $regraCliente->pegarPorEmail($json);
        return $response->withJson($cliente);
    });

    $app->put('/inserir', function (Request $request, Response $response, $args) {
        $regraCliente = new ClienteBLL();
        try {
            $json = json_decode($request->getBody()->getContents());
            $cliente = ClienteInfo::fromJson($json);
            $id_cliente = $regraCliente->inserir($cliente);
            $body = $response->getBody();
            $body->write($id_cliente);
            return $response->withStatus(200);
        } catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->put('/alterar', function (Request $request, Response $response, $args) {
        $regraCliente = new ClienteBLL();
        try {
            $json = json_decode($request->getBody()->getContents());
            $cliente = ClienteInfo::fromJson($json);
            $regraCliente->alterar($cliente);
            return $response->withStatus(200);
        } catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->get('/excluir/{id_cliente}', function (Request $request, Response $response, $args) {
        $regraCliente = new ClienteBLL();
        try {
            $regraCliente->excluir($args['id_cliente']);
            return $response->withStatus(200);
        } catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });
});
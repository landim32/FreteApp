<?php

namespace Emagine\CRM;

use Exception;
use Emagine\Base\EmagineApp;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\CRM\BLL\AtendimentoBLL;
use Emagine\CRM\Model\AtendimentoInfo;

$app = EmagineApp::getApp();


$app->group('/api/atendimento', function () use ($app) {

    /*
    $app->get('/listar[/{id_usuario}]', function (Request $request, Response $response, $args) {
        $regraCliente = new ClienteBLL();
        $id_usuario = intval($args['id_usuario']);
        $clientes = $regraCliente->listar($id_usuario, ClienteInfo::ATIVO);
        return $response->withJson($clientes);
    });
    */

    $app->get('/pegar/{id_atendimento}', function (Request $request, Response $response, $args) {
        $regraAtendimento = new AtendimentoBLL();
        $id_atendimento = intval($args['id_atendimento']);
        $atendimento = $regraAtendimento->pegar($id_atendimento);
        return $response->withJson($atendimento);
    });

    $app->put('/pegar-por-url', function (Request $request, Response $response, $args) {
        $json = json_decode($request->getBody()->getContents());
        $regraAtendimento = new AtendimentoBLL();
        $atendimento = $regraAtendimento->pegarPorUrl($json);
        return $response->withJson($atendimento);
    });

    $app->put('/inserir', function (Request $request, Response $response, $args) {
        $regraAtendimento = new AtendimentoBLL();
        try {
            $json = json_decode($request->getBody()->getContents());
            $atendimento = AtendimentoInfo::fromJson($json);
            $id_atendimento = $regraAtendimento->inserir($atendimento);
            $body = $response->getBody();
            $body->write($id_atendimento);
            return $response->withStatus(200);
        } catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->put('/alterar', function (Request $request, Response $response, $args) {
        $regraAtendimento = new AtendimentoBLL();
        try {
            $json = json_decode($request->getBody()->getContents());
            $atendimento = AtendimentoInfo::fromJson($json);
            $regraAtendimento->alterar($atendimento);
            return $response->withStatus(200);
        } catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->get('/excluir/{id_atendimento}', function (Request $request, Response $response, $args) {
        $regraAtendimento = new AtendimentoBLL();
        try {
            $regraAtendimento->excluir($args['id_atendimento']);
            return $response->withStatus(200);
        } catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });
});
<?php

namespace Emagine\CRM;

use Emagine\CRM\BLL\ProjetoBLL;
use Exception;
use Emagine\Base\EmagineApp;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\CRM\BLL\ClienteBLL;
use Emagine\CRM\Model\ClienteInfo;

$app = EmagineApp::getApp();


$app->group('/api/projeto', function () use ($app) {

    $app->put('/pegar-proxima-url', function (Request $request, Response $response, $args) {
        $json = json_decode($request->getBody()->getContents());
        $regraProjeto = new ProjetoBLL();
        $projeto = $regraProjeto->pegarProximaUrl($json);
        return $response->withJson($projeto);
    });

    $app->put('/inserir-url', function (Request $request, Response $response, $args) {
        $regraProjeto = new ProjetoBLL();
        try {
            $json = json_decode($request->getBody()->getContents());
            $id_projeto = $regraProjeto->inserirUrl($json);
            $body = $response->getBody();
            $body->write($id_projeto);
            return $response->withStatus(200);
        } catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->put('/excluir-url', function (Request $request, Response $response, $args) {
        $regraProjeto = new ProjetoBLL();
        try {
            $json = json_decode($request->getBody()->getContents());
            $regraProjeto->excluirUrl($json);
            return $response->withStatus(200);
        } catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });
});
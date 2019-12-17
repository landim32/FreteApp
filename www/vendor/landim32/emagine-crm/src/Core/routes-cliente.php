<?php

namespace Emagine\CRM;

use Exception;
use Emagine\Base\Utils\HtmlUtils;
use Emagine\Base\EmagineApp;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\PhpRenderer;
use Emagine\CRM\BLL\ClienteBLL;
use Emagine\CRM\Model\ClienteInfo;

$app = EmagineApp::getApp();

$app->group('/cliente', function () use ($app) {

    $app->get('/{slug_cliente}_{id_cliente}', function (Request $request, Response $response, $args) use ($app) {
        $regraCliente = new ClienteBLL();
        $cliente = $regraCliente->pegar($args['id_cliente']);

        $args['app'] = $app;
        $args['cliente'] = $cliente;

        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');
        /** @var PhpRenderer $rendererCrm */
        $rendererCrm = $this->get('crm');

        $response = $rendererMain->render($response, 'header.php', $args);
        $response = $rendererCrm->render($response, 'header.php', $args);
        $response = $rendererCrm->render($response, 'cliente-header.php', $args);
        $response = $rendererCrm->render($response, 'cliente.php', $args);
        $response = $rendererCrm->render($response, 'cliente-footer.php', $args);
        $response = $rendererCrm->render($response, 'footer.php', $args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });

    $app->get('[/{situacao}/{pg}]', function (Request $request, Response $response, $args) use ($app) {

        $pg = $args['pg'];
        if ($pg < 1) $pg = 1;

        $cod_situacao = 0;
        switch ($args['situacao']) {
            case "ativo":
                $cod_situacao = ClienteInfo::ATIVO;
                $url = $app->getBaseUrl() . "/cliente/ativo/%s";
                break;
            case "inativo":
                $cod_situacao = ClienteInfo::INATIVO;
                $url = $app->getBaseUrl() . "/cliente/inativo/%s";
                break;
            default:
                $cod_situacao = 0;
                $url = $app->getBaseUrl() . "/cliente/todos/%s";
                break;
        }

        $regraCliente = new ClienteBLL();
        $retorno = $regraCliente->listarPaginado($cod_situacao, "", $pg, MAX_PAGE_COUNT);
        $paginacao = HtmlUtils::admin_pagination(ceil($retorno->getTotal() / MAX_PAGE_COUNT), $url, $pg);

        $nargs = array(
            'app' => $app,
            'clientes' => $retorno->getClientes(),
            'cod_situacao' => $cod_situacao,
            'paginacao' => $paginacao
        );

        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');
        /** @var PhpRenderer $rendererCrm */
        $rendererCrm = $this->get('crm');

        $response = $rendererMain->render($response, 'header.php', $nargs);
        $response = $rendererCrm->render($response, 'header.php', $nargs);
        $response = $rendererCrm->render($response, 'clientes.php', $nargs);
        $response = $rendererCrm->render($response, 'footer.php', $nargs);
        $response = $rendererMain->render($response, 'footer.php', $nargs);
        return $response;
    });
});

<?php

namespace Emagine\CRM;

use Exception;
use Emagine\Base\EmagineApp;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\PhpRenderer;
use Emagine\CRM\BLL\AtendimentoBLL;
use Emagine\CRM\Model\AndamentoInfo;
use Emagine\Base\Utils\HtmlUtils;
use Emagine\Base\Utils\StringUtils;

$app = EmagineApp::getApp();

$app->group('/atendimento', function () use ($app) {

    $app->get('[/{situacao}/{pg}]', function (Request $request, Response $response, $args) use ($app) {

        $regraAtendimento = new AtendimentoBLL();

        $pg = $args['pg'];
        if ($pg < 1) $pg = 1;

        $cod_situacao = 0;
        switch ($args['situacao']) {
            case "ativo":
                $url = $app->getBaseUrl() . "/crm/atendimentos/ativo/%s";
                $cod_situacao = AndamentoInfo::ATIVO;
                break;
            case "proposta":
                $url = $app->getBaseUrl() . "/crm/atendimentos/proposta/%s";
                $cod_situacao = AndamentoInfo::PROPOSTA;
                break;
            case "inativo":
                $url = $app->getBaseUrl() . "/crm/atendimentos/inativo/%s";
                $cod_situacao = AndamentoInfo::INATIVO;
                break;
            default:
                $url = $app->getBaseUrl() . "/crm/atendimentos/todos/%s";
                break;
        }

        $queryParam = $request->getQueryParams();
        $palavraChave = $queryParam["p"];
        if (!StringUtils::isNullOrEmpty($palavraChave)) {
            $url .= "?p=" . urlencode($palavraChave);
        }

        $retorno = $regraAtendimento->buscarPaginado($palavraChave, $cod_situacao, $pg, MAX_PAGE_COUNT);
        $paginacao = HtmlUtils::admin_pagination(ceil($retorno->getTotal() / MAX_PAGE_COUNT), $url, $pg);
        $nargs = array(
            'app' => $app,
            'palavra_chave' => $palavraChave,
            'atendimentos' => $retorno->getAtendimentos(),
            'cod_situacao' => $cod_situacao,
            'paginacao' => $paginacao
        );

        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');
        /** @var PhpRenderer $rendererCrm */
        $rendererCrm = $this->get('crm');

        $response = $rendererMain->render($response, 'header.php', $nargs);
        $response = $rendererCrm->render($response, 'header.php', $nargs);
        $response = $rendererCrm->render($response, 'atendimentos.php', $nargs);
        $response = $rendererCrm->render($response, 'footer.php', $nargs);
        $response = $rendererMain->render($response, 'footer.php', $nargs);
        return $response;
    });

    $app->get('/{id_atendimento}', function (Request $request, Response $response, $args) use ($app) {

        $id_atendimento = $args["id_atendimento"];
        $regraAtendimento = new AtendimentoBLL();
        $atendimento = $regraAtendimento->pegar($id_atendimento);

        $args['app'] = $app;
        $args['atendimento'] = $atendimento;
        $args['cliente'] = $atendimento->getCliente();

        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');
        /** @var PhpRenderer $rendererCrm */
        $rendererCrm = $this->get('crm');

        $response = $rendererMain->render($response, 'header.php', $args);
        //$response = $rendererCrm->render($response, 'header.php', $args);
        //$response = $rendererCrm->render($response, 'cliente-header.php', $args);
        $response = $rendererCrm->render($response, 'atendimento.php', $args);
        //$response = $rendererCrm->render($response, 'cliente-footer.php', $args);
        //$response = $rendererCrm->render($response, 'footer.php', $args);
        $response = $rendererMain->render($response, 'footer.php', $args);

        return $response;
    });
});

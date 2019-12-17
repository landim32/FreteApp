<?php
namespace Emagine\Frete;

use Emagine\Frete\BLL\TipoVeiculoBLL;
use Emagine\Frete\Model\TipoVeiculoInfo;
use Exception;
use Slim\Http\UploadedFile;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Base\EmagineApp;

$app = EmagineApp::getApp();

$app->group("/veiculo", function () use ($app) {

    $app->get('/listar', function (Request $request, Response $response, $args) use ($app) {
        $queryParam = $request->getQueryParams();

        $regraVeiculo = new TipoVeiculoBLL();
        $veiculos = $regraVeiculo->listar();

        $args['app'] = $app;
        $args['veiculos'] = $veiculos;
        if (array_key_exists("erro", $queryParam)) {
            $args['erro'] = $queryParam["erro"];
        }

        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');
        /** @var PhpRenderer $rendererFrete */
        $rendererFrete = $this->get('frete');

        $response = $rendererMain->render($response, 'header.php', $args);
        $response = $rendererFrete->render($response, 'veiculos.php', $args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });

    $app->get('/novo', function (Request $request, Response $response, $args) use ($app) {
        $regraVeiculo = new TipoVeiculoBLL();

        $args['app'] = $app;
        $args['veiculo'] = new TipoVeiculoInfo();
        //$args['veiculos'] = $veiculos;
        $args['tipos'] = $regraVeiculo->listarTipo();

        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');
        /** @var PhpRenderer $rendererFrete */
        $rendererFrete = $this->get('frete');

        $response = $rendererMain->render($response, 'header.php', $args);
        $response = $rendererFrete->render($response, 'veiculo-form.php', $args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });

    $app->get('/{slug}_{id_veiculo}/alterar', function (Request $request, Response $response, $args) use ($app) {
        $id_veiculo = intval($args['id_veiculo']);
        $regraVeiculo = new TipoVeiculoBLL();
        $veiculo = $regraVeiculo->pegar($id_veiculo);

        $args['app'] = $app;
        $args['veiculo'] = $veiculo;
        $args['tipos'] = $regraVeiculo->listarTipo();

        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');
        /** @var PhpRenderer $rendererFrete */
        $rendererFrete = $this->get('frete');

        $response = $rendererMain->render($response, 'header.php', $args);
        $response = $rendererFrete->render($response, 'veiculo-form.php', $args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });

    $app->get('/{slug}/excluir', function (Request $request, Response $response, $args) use ($app) {
        $id_veiculo = intval($args['id_veiculo']);
        $regraVeiculo = new TipoVeiculoBLL();

        $args['app'] = $app;

        try {
            $regraVeiculo->excluir($id_veiculo);
            $url = $app->getBaseUrl() . "/veiculo/listar";
            return $response->withStatus(302)->withHeader('Location', $url);
        }
        catch (Exception $e) {
            $url = $app->getBaseUrl() . "/veiculo/listar";
            $url .= "?erro=" . urlencode($e->getMessage());
            return $response->withStatus(302)->withHeader('Location', $url);
        }
    });

    $app->get('/{slug}_{id_veiculo}', function (Request $request, Response $response, $args) use ($app) {
        $id_veiculo = intval($args['id_veiculo']);
        $regraVeiculo = new TipoVeiculoBLL();
        $veiculo = $regraVeiculo->pegar($id_veiculo);

        $args['app'] = $app;
        $args['veiculo'] = $veiculo;

        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');
        /** @var PhpRenderer $rendererFrete */
        $rendererFrete = $this->get('frete');

        $response = $rendererMain->render($response, 'header.php', $args);
        $response = $rendererFrete->render($response, 'veiculo.php', $args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });

    $app->post('', function (Request $request, Response $response, $args) use ($app) {
        $regraVeiculo = new TipoVeiculoBLL();

        $args['app'] = $app;

        $paramPost = $request->getParsedBody();

        $id_veiculo = intval($paramPost['id_tipo']);
        $veiculo = null;
        if ($id_veiculo > 0) {
            $veiculo = $regraVeiculo->pegar($id_veiculo);
        }

        $regraVeiculo->pegarDoPost($paramPost, $veiculo);

        $uploadedFiles = $request->getUploadedFiles();
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $uploadedFiles['foto'];

        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            $filename = $regraVeiculo->moveUploadedFile($uploadedFile);
            $veiculo->setFoto($filename);
        }

        if ($id_veiculo > 0) {
            $regraVeiculo->alterar($veiculo);
        } else {
            $id_veiculo = $regraVeiculo->inserir($veiculo);
        }
        $veiculo = $regraVeiculo->pegar($id_veiculo);

        $url = $app->getBaseUrl() . "/veiculo/" . strtolower(sanitize_slug($veiculo->getNome())) . "_" . $veiculo->getId();
        return $response->withStatus(302)->withHeader('Location', $url);
    });
});


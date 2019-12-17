<?php
namespace Emagine\Frete\BLL;

use Exception;
use Emagine\Frete\DALFactory\FreteHistoricoDALFactory;
use Emagine\Frete\IBLL\IFreteHistoricoBLL;
use Emagine\Frete\Model\FreteHistoricoInfo;

class FreteHistoricoBLL implements IFreteHistoricoBLL
{
    /**
     * @param int $id_frete
     * @return FreteHistoricoInfo[]
     * @throws Exception
     */
    public function listar($id_frete) {
        $dalHistorico = FreteHistoricoDALFactory::create();
        return $dalHistorico->listar($id_frete);
    }

    /**
     * @param int $largura
     * @param int $altura
     * @return string
     * @throws Exception
     */
    private function gerarMapaBaseUrl($largura, $altura) {
        if (!defined("GOOGLE_MAPS_API")) {
            throw new Exception("GOOGLE_MAPS_API não foi definido.");
        }
        $url = "http://maps.googleapis.com/maps/api/staticmap";
        $url .= "?key=" . GOOGLE_MAPS_API;
        $url .= sprintf("&size=%sx%s", $largura, $altura);
        return $url;
    }

    /**
     * @throws Exception
     * @param FreteHistoricoInfo[] $historicos
     * @param int $largura
     * @param int $altura
     * @return string
     */
    public function gerarMapaURL($historicos, $largura = 640, $altura = 360) {

        if (count($historicos) <= 2) {
            throw new Exception("Precisa de pelomenos 2 históricos para gerar um mapa.");
        }

        $url = $this->gerarMapaBaseUrl($largura, $altura);

        $paths = array();
        foreach ($historicos as $historico) {
            $paths[] = $historico->getLatitude() . "," . $historico->getLongitude();
        }
        $url .= "&path=" . urlencode("color:blue|weight:5|" . implode("|", $paths));

        /** @var FreteHistoricoInfo $origem */
        $origem = array_values($historicos)[0];
        /** @var FreteHistoricoInfo $destino */
        $destino = array_values(array_reverse($historicos))[0];

        $url .= "&markers=" . urlencode("color:green|label:O|" . $origem->getLatitude() . "," . $origem->getLongitude());
        $url .= "&markers=" . urlencode("color:red|label:D|" . $destino->getLatitude() . "," . $destino->getLongitude());
        $url .= "&sensor=false";
        return $url;
    }

    /**
     * @param FreteHistoricoInfo[] $historicos
     * @return float
     */
    public function calcularDistancia($historicos)
    {
        $regraRota = new RotaBLL();
        $distancia = 0;
        $latAnterior = null;
        $lngAnterior = null;
        foreach ($historicos as $historico) {
            if (!(is_null($latAnterior) && is_null($lngAnterior))) {
                $distancia += $regraRota->distancia(
                    $latAnterior,
                    $lngAnterior,
                    $historico->getLatitude(),
                    $historico->getLongitude()
                );
            }
            $latAnterior = $historico->getLatitude();
            $lngAnterior = $historico->getLongitude();
        }
        return floor($distancia * 1000);
    }
}
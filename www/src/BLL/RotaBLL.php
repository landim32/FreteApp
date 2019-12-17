<?php
/**
 * Created by PhpStorm.
 * User: foursys
 * Date: 30/12/17
 * Time: 00:34
 */

namespace Emagine\Frete\BLL;

use Emagine\Frete\Model\FreteInfo;
use Emagine\Frete\Model\LocalInfo;
use Exception;
use Landim32\GoogleDirectionApi\BLL\GoogleDirectionApi;
use Landim32\GoogleDirectionApi\Model\GDResponse;

class RotaBLL
{
    /**
     * @return bool
     */
    public function usaCalculoRota() {
        if (defined("FRETE_CALCULO_ROTA")) {
            return (FRETE_CALCULO_ROTA == true);
        }
        return true;
    }

    /**
     * @return int
     */
    public function velocidadeMediaPorHora() {
        if (defined("FRETE_VELOCIDADE_MEDIA")) {
            return FRETE_VELOCIDADE_MEDIA;
        }
        return 30;
    }

    /**
     * @param string[] $rotas
     * @return GDResponse
     * @throws Exception
     */
    public function calcularRota($rotas) {
        if (!defined("GOOGLE_MAPS_API")) {
            throw new Exception("GOOGLE_MAPS_API não está definido.");
        }
        $gd = new GoogleDirectionApi(GOOGLE_MAPS_API);
        $gd->setOrigin($rotas[0]);
        $gd->setDestination($rotas[count($rotas) - 1]);

        $gd->clearWaypoints();
        if (count($rotas) > 2) {
            $waypoints = array_slice($rotas, 1, count($rotas) - 2);
            if (count($waypoints) > 0) {
                foreach ($waypoints as $waypoint) {
                    $gd->addWaypoint($waypoint);
                }
            }
        }
        //$mensagem = implode("|", $rotas) . "=" . implode("|", $gd->getWaypoints());
        //throw new Exception($mensagem);
        return $gd->execute();
    }


    /**
     * @param float $lat1
     * @param float $lon1
     * @param float $lat2
     * @param float $lon2
     * @param string $unit
     * @return float
     */
    public function distancia($lat1, $lon1, $lat2, $lon2, $unit = "K") {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

    /**
     * @param FreteInfo $frete
     * @return int
     * @throws Exception
     */
    public function calcularDistanciaFrete(FreteInfo $frete)
    {
        $distancia = 0;
        $latAnterior = null;
        $lngAnterior = null;
        foreach ($frete->listarLocal() as $local) {
            if (!(is_null($latAnterior) && is_null($lngAnterior))) {
                $distancia += $this->distancia(
                    $latAnterior,
                    $lngAnterior,
                    $local->getLatitude(),
                    $local->getLongitude()
                );
            }
            $latAnterior = $local->getLatitude();
            $lngAnterior = $local->getLongitude();
        }
        return floor($distancia * 1000);
    }

    /**
     * @param LocalInfo[] $locais
     * @return float
     */
    public function calcularDistancia($locais)
    {
        $distancia = 0;
        $latAnterior = null;
        $lngAnterior = null;
        foreach ($locais as $local) {
            if (!(is_null($latAnterior) && is_null($lngAnterior))) {
                $distancia += $this->distancia(
                    $latAnterior,
                    $lngAnterior,
                    $local->getLatitude(),
                    $local->getLongitude()
                );
            }
            $latAnterior = $local->getLatitude();
            $lngAnterior = $local->getLongitude();
        }
        return floor($distancia * 1000);
    }

    /**
     * @param int $distancia
     * @return int
     */
    public function calcularTempo($distancia) {
        return floor((($distancia / 1000) / $this->velocidadeMediaPorHora()) * (60 * 60));
    }

    /**
     * @param int $distancia
     * @return string
     */
    public function distanciaParaTexto($distancia) {
        return number_format(($distancia / 1000), 1, ",", ".") . " km";
    }

    /**
     * @param int $tempo
     * @return string
     */
    public function tempoParaTexto($tempo) {
        $str = "";
        if ($tempo < 60) {
            $str = sprintf("%s sec", $tempo);
        }
        elseif ($tempo >= 60 && $tempo < (60 * 60)) {
            $str = sprintf("%s min", floor($tempo / 60));
        }
        elseif ($tempo >= (60 * 60)) {
            $hora = floor($tempo / (60 * 60));
            $str = sprintf("%sh", $hora);
            $min = floor(($tempo - ($hora * 3600)) / 60);
            if ($min > 0) {
                $str .= sprintf("%sm", $min);
            }
        }
        return $str;
    }
}
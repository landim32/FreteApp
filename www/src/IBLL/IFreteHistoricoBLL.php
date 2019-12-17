<?php
namespace Emagine\Frete\IBLL;

use Emagine\Frete\Model\FreteHistoricoInfo;

interface IFreteHistoricoBLL
{
    /**
     * @param int $id_frete
     * @return FreteHistoricoInfo[]
     */
    public function listar($id_frete);

    /**
     * @param FreteHistoricoInfo[] $historicos
     * @param int $largura
     * @param int $altura
     * @return string
     */
    public function gerarMapaURL($historicos, $largura = 640, $altura = 360);

    /**
     * @param FreteHistoricoInfo[] $historicos
     * @return float
     */
    public function calcularDistancia($historicos);
}
<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 01/07/2017
 * Time: 09:13
 */

namespace Emagine\CRM\BLL;

use Exception;
use Emagine\CRM\DAL\ProjetoDAL;

class ProjetoBLL
{
    /**
     * @throws Exception
     * @param string $comecaCom
     * @return string
     */
    public function pegarProximaUrl($comecaCom) {
        $dal = new ProjetoDAL();
        return $dal->pegarProximaUrl($comecaCom);
    }

    /**
     * @throws Exception
     * @param string $url
     * @return int
     */
    public function inserirUrl($url) {
        $dal = new ProjetoDAL();
        $id_projeto = $dal->pegarPorUrl($url);
        if (!($id_projeto > 0)) {
            $regraAtendimento = new AtendimentoBLL();
            $atendimento = $regraAtendimento->pegarPorUrl($url);
            if (is_null($atendimento)) {
                $id_projeto = $dal->inserirUrl($url);
            }
            else {
                $id_projeto = 0;
            }
        }
        return $id_projeto;
    }

    /**
     * @throws Exception
     * @param string $url
     */
    public function excluirUrl($url) {
        $dal = new ProjetoDAL();
        $dal->excluirUrl($url);
    }
}
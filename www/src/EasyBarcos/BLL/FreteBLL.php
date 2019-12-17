<?php
namespace Emagine\Frete\EasyBarcos\BLL;

use Emagine\Frete\BLLFactory\MotoristaBLLFactory;
use Exception;
use Emagine\Frete\Model\FreteInfo;

class FreteBLL extends \Emagine\Frete\BLL\FreteBLL
{
    const ERRO_SELECIONE_VEICULO = 'Selecione a embarcação.';
    const ERRO_TIPO_VEICULO_NAO_INFORMADO = "Nenhum tipo de embarcação informado.";
    const ERRO_MOTORISTA_NAO_DEFINIDO = "O marinheiro ainda não foi definido.";
    const ERRO_MOTORISTA_NAO_DISPONIVEL = "Marinheiro não disponível.";
    const ERRO_FRETE_POSSUI_MOTORISTA = "Esse frete já possui um marinheiro.";

    /**
     * @return array<string,string>
     */
    public function listarSituacao() {
        return array(
            FreteInfo::AGUARDANDO_PAGAMENTO => 'Aguardando Pagamento',
            FreteInfo::PROCURANDO_MOTORISTA => 'Procurando Marinheiro',
            FreteInfo::APROVANDO_MOTORISTA => 'Aguardando aprovação',
            FreteInfo::AGUARDANDO => 'Aguardando',
            FreteInfo::PEGANDO_ENCOMENDA => 'Pegando encomenda',
            FreteInfo::ENTREGANDO => 'Entregando',
            FreteInfo::ENTREGUE => 'Entregue',
            FreteInfo::ENTREGA_CONFIRMADA => 'Entrega Confirmada',
            FreteInfo::CANCELADO => 'Cancelado'
        );
    }

    /**
     * @return int
     */
    protected function pegarSituacaoAoAceitar() {
        return FreteInfo::APROVANDO_MOTORISTA;
    }

    /**
     * @param FreteInfo $frete
     * @throws Exception
     */
    protected function executarAceite(FreteInfo $frete) {
        $previsao = $frete->getPrevisao();
        if ($previsao < 3600) {
            $previsao = 3600;
        }
        $regraMotorista = MotoristaBLLFactory::create();
        $motorista = $regraMotorista->pegar($frete->getIdMotorista());
        $preco = $motorista->getValorHora() * ($previsao / 3600);
        $frete->setPreco(round($preco, 2));
        parent::executarAceite($frete);
    }

}
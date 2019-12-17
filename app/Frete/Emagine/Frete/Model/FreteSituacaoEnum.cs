using System;
namespace Emagine.Frete.Model
{
    public enum FreteSituacaoEnum
    {
        AguardandoPagamento = 1,
        ProcurandoMotorista = 2,
        //Aguardando = 3,
        PegandoEncomenda = 3,
        Entregando = 4,
        Entregue = 5,
        EntregaConfirmada = 6,
        Cancelado = 7
    }
}

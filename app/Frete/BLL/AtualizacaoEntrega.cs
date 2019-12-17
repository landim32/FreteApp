using System;
using System.Linq;
using System.Threading.Tasks;
using Acr.UserDialogs;
using Emagine.Frete.BLL;
using Emagine.Frete.Factory;
using Emagine.Frete.Model;
using EmagineFrete.Controls;
using EmagineFrete.Model;
using EmagineFrete.Pages;
using Xamarin.Forms;

namespace EmagineFrete.BLL
{
    public static class AtualizacaoEntrega
    {
        private static Task task;
        private static bool confirm = false;
        private static int delay = 10000;

        public static void start(){
            task = Task.Factory.StartNew(() =>
            {
                Task.Delay(delay).Wait();
                atualizaPedidosMotoristaAsync().Wait();
            });
        }

        public static void setConfirm(bool value){
            confirm = value;
        } 

        private static async Task atualizaPedidosMotoristaAsync(){
            /*
            var regraMotorista = MotoristaFactory.create();
            var motorista = regraMotorista.pegarAtual();
            MotoristaRetornoInfo ret = null;
            if(motorista != null && motorista.Situacao == MotoristaSituacaoEnum.Ativo)
            {
                ret = await new MotoristaBLL().listarPedidosAsync();
                if (!confirm && ret != null)
                {
                    if (ret.IdFrete == null && ret.Fretes != null && ret.Fretes.Count > 0)
                    {
                        Device.BeginInvokeOnMainThread(async () =>
                        {
                            var pedido = ret.Fretes.First();
                            confirm = await UserDialogs.Instance.ConfirmAsync("Nova entrega no valor de R$ " + pedido.Valor.ToString("N2") + " disponível para iniciar.", "Entrega", "Ver", "Não quero");
                            if (confirm)
                            {
                                ((NavigationPage)((MyRootPage)App.Current.MainPage).Detail).PushAsync(new ConfirmaEntregaPage(pedido));
                            }
                            else
                            {
                                var retAceite = await new FreteBLL().aceitar(false, pedido.IdEntrega, new MotoristaBLL().pegarAtual().Id);

                            }
                        });

                    }
                }
            }

            task = Task.Factory.StartNew(() =>
            {
                Task.Delay(delay).Wait();
                atualizaPedidosMotoristaAsync().Wait();
            });
            */
        }
    }
}

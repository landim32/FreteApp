using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;
using EmagineFrete.Cells;
using EmagineFrete.Model;
using Acr.UserDialogs;
using Emagine.Frete.BLL;
using Emagine.Frete.Model;
using Emagine.Login.BLL;
using Emagine.Login.Factory;
using Emagine.Frete.Factory;

namespace EmagineFrete.Pages
{
    public class FreteListaPage : ContentPage
    {
        private ListView _freteListView;
        private bool _Historico;
        private bool _Inicio = true;

        public FreteListaPage(bool historico)
        {
            Title = "Meus Pedidos";
            inicializarComponente();
            _Historico = historico;
            Content = _freteListView;
        }

        public async Task<List<FreteInfo>> listarFreteAsync()
        {
            var regraUsuario = UsuarioFactory.create();
            var usuario = regraUsuario.pegarAtual();

            var regraMotorista = MotoristaFactory.create();
            var motorista = regraMotorista.pegarAtual();

            var regraFrete = FreteFactory.create();

            List<FreteInfo> fretes = null;
            if (motorista != null)
            {
                fretes = await regraFrete.listar(0, motorista.Id);
            }
            else {
                fretes = await regraFrete.listar(usuario.Id);
            }

            if(_Historico){
                fretes = fretes.Where(x => x.Situacao == FreteSituacaoEnum.Cancelado || x.Situacao == FreteSituacaoEnum.EntregaConfirmada).ToList();
            } else {
                fretes = fretes.Where(x => x.Situacao != FreteSituacaoEnum.Cancelado && x.Situacao != FreteSituacaoEnum.EntregaConfirmada).ToList();

            }
            return fretes;
        }

        private void inicializarComponente()
        {
            _freteListView = new ListView();
            _freteListView.HasUnevenRows = true;
            _freteListView.RowHeight = -1;
            _freteListView.SeparatorVisibility = SeparatorVisibility.None;
            _freteListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            _freteListView.ItemTemplate = new DataTemplate(typeof(FreteCell));
            _freteListView.ItemTapped += async (sender, e) =>
            {
                //if (e == null || _Historico)
                if (e == null)
                    return;

                FreteInfo entrInfo = (FreteInfo)((ListView)sender).SelectedItem;

                _freteListView.SelectedItem = null;

                var situacoes = new List<FreteSituacaoEnum>() {
                    //FreteSituacaoEnum.Aguardando,
                    FreteSituacaoEnum.PegandoEncomenda,
                    FreteSituacaoEnum.Entregando,
                    FreteSituacaoEnum.Entregue
                };
                if (situacoes.Contains(entrInfo.Situacao)) {
                    //var retEntr = await new FreteBLL().atualizar(entrInfo.Id);
                    //if (retEntr.IdMotorista == new UsuarioBLL().pegarAtual().Id){
                    if (entrInfo.IdMotorista == new UsuarioBLL().pegarAtual().Id) {
                        //var retMot = await new MotoristaBLL().listarPedidosAsync();
                        //await Navigation.PushAsync(new AcompanharEntregaMotoristaOld(retMot));   
                        await Navigation.PushAsync(new AcompanhaEntregaMotorista(entrInfo));
                    }
                    else{
                        var retEntr = await new FreteBLL().atualizar(entrInfo.Id);
                        await Navigation.PushAsync(new AcompanhaEntrega(retEntr));
                    }
                        
                }


            };
        }

        protected override async void OnAppearing()
        {
            base.OnAppearing();
            if(_Inicio){
                UserDialogs.Instance.ShowLoading("carregando...");
                try
                {
                    _freteListView.ItemsSource = await listarFreteAsync();
                    UserDialogs.Instance.HideLoading();
                    _Inicio = false;
                }
                catch (Exception erro) {
                    UserDialogs.Instance.HideLoading();
                    await UserDialogs.Instance.AlertAsync(erro.Message, "Erro", "Entendi");
                }
            }
        }
    }
}

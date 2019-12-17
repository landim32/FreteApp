using System;
using System.Collections.Generic;
using System.Threading.Tasks;
using Acr.UserDialogs;
using Emagine;
using Emagine.Base.Pages;
using Emagine.Frete.BLL;
using Emagine.Frete.Factory;
using Emagine.Frete.Model;
using Emagine.Login.Factory;
using EmagineFrete.Cells;
using EmagineFrete.Controls;
using EmagineFrete.Model;
using Xamarin.Forms;

namespace EmagineFrete.Pages
{
    public class CorridasEmEspera : ContentPage
    {
        private ListView _freteListView;
        private bool _Inicio = true;

        public CorridasEmEspera()
        {
            Title = "Corridas em espera";
            inicializarComponente();
            Content = _freteListView;
        }

        /*
        public async Task<MotoristaRetornoInfo> listarFreteAsync()
        {
            var ret = await new MotoristaBLL().listarPedidosAsync();
            return ret;
        }
        */

        private void inicializarComponente()
        {
            _freteListView = new ListView();
            _freteListView.HasUnevenRows = true;
            _freteListView.RowHeight = -1;
            _freteListView.SeparatorVisibility = SeparatorVisibility.None;
            _freteListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            _freteListView.ItemTemplate = new DataTemplate(typeof(PedidoPendenteCell));
            _freteListView.ItemTapped += (sender, e) =>
            {
                if (e == null)
                    return;

                //MotoristaFreteInfo entrInfo = (MotoristaFreteInfo)((ListView)sender).SelectedItem;
                FreteInfo frete = (FreteInfo)((ListView)sender).SelectedItem;
                //((RootPage)App.Current.MainPage).PushAsync(new ConfirmaEntregaPage(frete));
                //var mainPage = ((RootPage)App.Current.MainPage).PaginaAtual;
                Navigation.PushAsync(new ConfirmaEntregaPage(frete));

            };
        }

        protected override async void OnAppearing()
        {
            base.OnAppearing();
            if (_Inicio)
            {
                _Inicio = false;
                try
                {
                    UserDialogs.Instance.ShowLoading("carregando...");
                    //_freteListView.ItemsSource = (await listarFreteAsync()).Pedidos;
                    var regraUsuario = UsuarioFactory.create();
                    var usuario = regraUsuario.pegarAtual();
                    var regraFrete = FreteFactory.create();
                    var fretes = await regraFrete.listarDisponivel(usuario.Id);
                    _freteListView.ItemsSource = fretes;
                    UserDialogs.Instance.HideLoading();
                }
                catch (Exception erro) {
                    UserDialogs.Instance.HideLoading();
                    await UserDialogs.Instance.AlertAsync(erro.Message, "Erro", "Entendi");
                }
            }
        }
    }
}


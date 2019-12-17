using System;
using System.Collections.Generic;
using System.Threading.Tasks;
using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Frete.BLL;
using Emagine.Frete.Cells;
using Emagine.Frete.Model;
using Emagine.Login.Factory;
using Frete.Factory;
using Frete.Model;
using Xamarin.Forms;

namespace Frete.Pages
{
    public class FreteFaturaListaPage : ContentPage
    {
        private ListView _faturaList;
        private bool _Inicio = true;

        public FreteFaturaListaPage()
        {
            Title = "Faturas";
            inicializarComponente();
            Content = new StackLayout{
                Margin = 10,
                Children = {
                    _faturaList
                }
            };
        }

        public async Task<List<FreteFaturaInfo>> listarDisponibilidadeAsync()
        {
            var ret = await FreteFaturaFactory.create().listar(UsuarioFactory.create().pegarAtual().Id);
            return ret;
        }

        private void inicializarComponente()
        {
            _faturaList = new ListView()
            {
                Style = Estilo.Current[Estilo.LISTA_PADRAO]
            };
            _faturaList.HasUnevenRows = true;
            _faturaList.RowHeight = -1;
            _faturaList.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            _faturaList.ItemTemplate = new DataTemplate(typeof(FaturaCell));
            _faturaList.ItemTapped += (sender, e) => {
                if (e == null)
                    return;
                _faturaList.SelectedItem = null;
            };
        }

        protected override async void OnAppearing()
        {
            base.OnAppearing();
            if (_Inicio)
            {
                _Inicio = false;
                UserDialogs.Instance.ShowLoading("carregando...");
                try
                {
                    var regraUsuario = UsuarioFactory.create();
                    var regraFatura = FreteFaturaFactory.create();
                    var usuario = regraUsuario.pegarAtual();
                    _faturaList.ItemsSource = await regraFatura.listar(usuario.Id);
                    UserDialogs.Instance.HideLoading();
                }
                catch (Exception erro) {
                    await UserDialogs.Instance.AlertAsync(erro.Message, "Erro", "Entendi");
                    UserDialogs.Instance.HideLoading();
                }
            }
        }
    }
}


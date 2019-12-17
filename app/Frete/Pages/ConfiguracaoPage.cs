using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;
using Emagine.Base.Controls;
using Emagine.Login.Factory;
using Emagine;

namespace Frete.Pages
{
    public class ConfiguracaoPage : ContentPage
    {
        private MenuButton _SairButton;

        public ConfiguracaoPage()
        {
            Title = "Configurações";
            inicializarComponente();

            Content = new ScrollView{
                Orientation = ScrollOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Fill,
                Content = new StackLayout
                {
                    Orientation = StackOrientation.Vertical,
                    HorizontalOptions = LayoutOptions.Fill,
                    VerticalOptions = LayoutOptions.Start,
                    Children = {
                        _SairButton
                    }
                }
            };
        }

        private void inicializarComponente()
        {
            _SairButton = new MenuButton {
                Icon = "fa-remove",
                Text = "Sair"
            };
            _SairButton.Click += async (sender, e) =>
            {
                var regraUsuario = UsuarioFactory.create();
                await regraUsuario.limparAtual();
                App.Current.MainPage = new NavigationPage(new InicialPage());
            };
        }

        /*
        protected override async void OnAppearing()
        {
            base.OnAppearing();
            if (_imoveis == null) {
                _imoveis = await ImovelUtils.listar();
                _imovelListView.ItemsSource = _imoveis;
            }
        }
        */
    }
}

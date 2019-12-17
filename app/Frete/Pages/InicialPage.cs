using System;
using FormsPlugin.Iconize;
using Plugin.Iconize;
using Rg.Plugins.Popup.Extensions;
using Xamarin.Forms;
using Emagine.Base.Estilo;
using Emagine.Base.Utils;
using Emagine.Login.Pages;
using Emagine.Frete.Popups;
using Emagine.Frete.Pages;
using Emagine;
using Emagine.Base.Pages;
using Emagine.Login.Model;
using Emagine.Frete.Factory;
using Emagine.Frete.Model;

namespace Frete.Pages
{
    public class InicialPage : ContentPage
    {
        private Button _AcessarContaButton;
        private Button _CriarContaButton;
        private bool _TelasApareceu = false;

        public InicialPage()
        {
            inicializarComponente();
            NavigationPage.SetHasNavigationBar(this, false);
            Content = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                VerticalOptions = LayoutOptions.CenterAndExpand,
                HorizontalOptions = LayoutOptions.Fill,
                Spacing = 10,
                Children = {
                    new Image{
                        Source = "logo.png",
                        Margin = new Thickness(20),
                        VerticalOptions = LayoutOptions.Start,
                        HorizontalOptions = LayoutOptions.Center
                    },
                    new StackLayout{
                        Orientation = StackOrientation.Horizontal,
                        VerticalOptions = LayoutOptions.Fill,
                        HorizontalOptions = LayoutOptions.Start,
                        Margin = new Thickness(10, 0),
                        Spacing = 10,
                        Children = {
                            new Label {
                                HorizontalOptions = LayoutOptions.Fill,
                                TextColor = Color.Black,
                                //TextColor = Estilo.Current.PrimaryColor,
                                Text = "Seja bem vindo",
                                FontSize = 18
                            }
                        }
                    },
                    _CriarContaButton,
                    _AcessarContaButton
                }
            };
        }

        protected override void OnAppearing()
        {
            base.OnAppearing();
            if (!_TelasApareceu) {
                PermissaoUtils.pedirPermissao();
                _TelasApareceu = true;
            }
        }

        public void inicializarComponente() {
            _CriarContaButton = new Button()
            {
                Text = "Criar conta",
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Margin = new Thickness(10, 0)
            };
            _CriarContaButton.Clicked += (sender, e) => {
                var cadastroPage = new FreteUsuarioFormPage {
                    Gravar = false
                };
                cadastroPage.AoCadastrarMotorista += (s2, motorista) =>
                {
                    var motoristaPage = new CadastroMotoristaPage(motorista);
                    motoristaPage.AoCompletar += (s3, motorista2) =>
                    {
                        App.Current.MainPage = new RootPage
                        {
                            NomeApp = "Mais Cargas",
                            PaginaAtual = new AvisoPage(),
                            Menus = ((App)App.Current).gerarMenu()
                        };
                    };
                    Navigation.PushAsync(motoristaPage);
                };
                cadastroPage.AoCadastrarEmpresa += (s2, usuario) =>
                {
                    var empresaPage = new CadastroEmpresaPage(usuario);
                    empresaPage.AoCompletar += (s3, usuario2) =>
                    {
                        App.Current.MainPage = new RootPage
                        {
                            NomeApp = "Mais Cargas",
                            PaginaAtual = new AvisoPage(),
                            Menus = ((App)App.Current).gerarMenu()
                        };
                    };
                    Navigation.PushAsync(empresaPage);
                };
                Navigation.PushAsync(cadastroPage);
            };
            _AcessarContaButton = new Button()
            {
                Text = "Entrar",
                Style = Estilo.Current[Estilo.BTN_SUCESSO],
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Margin = new Thickness(10,0)
            };
            _AcessarContaButton.Clicked += (sender, e) => {
                var loginPage = new LoginCargaPage {
                    Email = "48.948.949/8498-48",
                    Senha = "123"
                };
                loginPage.AoLogar += usuarioAoLogar;
                loginPage.AoLogarMotorista += motoristaAoLogar;
                Navigation.PushAsync(loginPage);
            };
        }

        private async void motoristaAoLogar(object sender, MotoristaInfo motorista)
        {
            if (motorista == null)
            {
                await DisplayAlert("Erro", "Motorista não informado.", "Fechar");
                return;
            }
            App.Current.MainPage = new RootPage
            {
                NomeApp = "Mais Cargas",
                PaginaAtual = new AvisoPage(),
                Menus = ((App)App.Current).gerarMenu()
            };
        }

        private async void usuarioAoLogar(object sender, UsuarioInfo usuario)
        {
            if (usuario == null)
            {
                await DisplayAlert("Erro", "Usuário não informado.", "Fechar");
                return;
            }
            App.Current.MainPage = new RootPage {
                NomeApp = "Mais Cargas",
                PaginaAtual = new AvisoPage(),
                Menus = ((App)App.Current).gerarMenu()
            };
        }
    }
}


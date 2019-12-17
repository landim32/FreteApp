using System;
using FormsPlugin.Iconize;
using EmagineFrete.Popups;
using EmagineFrete.Utils;
using Plugin.Iconize;
using Rg.Plugins.Popup.Extensions;
using Xamarin.Forms;
using Emagine.Login.Pages;
using Emagine.Base.Utils;
using Emagine.Base.Estilo;
using Emagine.Frete.Utils;
using Emagine;

namespace EmagineFrete.Pages
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
                        HeightRequest = 120,
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
                            new IconImage() {
                                Icon = "fa-gift",
                                IconColor = Color.Black,
                                IconSize = 25,
                                WidthRequest = 30,
                                VerticalOptions = LayoutOptions.Start,
                                HorizontalOptions = LayoutOptions.Start
                            },
                            new Label {
                                HorizontalOptions = LayoutOptions.Fill,
                                //TextColor = TemaUtils.CorSecundaria,
                                Text = "Envie seus produtos com maior segurança, rapidez e comodidade.",
                                FontSize = 18
                            }
                        }
                    },
                    new StackLayout{
                        Orientation = StackOrientation.Horizontal,
                        VerticalOptions = LayoutOptions.Fill,
                        HorizontalOptions = LayoutOptions.Start,
                        Margin = new Thickness(10, 0),
                        Spacing = 10,
                        Children = {
                            new IconImage() {
                                Icon = "fa-truck",
                                IconColor = Color.Black,
                                IconSize = 25,
                                WidthRequest = 30,
                                VerticalOptions = LayoutOptions.Start,
                                HorizontalOptions = LayoutOptions.Start
                            },
                            new Label {
                                HorizontalOptions = LayoutOptions.Fill,
                                //TextColor = TemaUtils.CorSecundaria,
                                Text = "Use seu veículo (carro, pick-up ou moto) e complemente sua renda com a entrega de cargas complementares no percurso do seu frete.",
                                FontSize = 18
                            }
                        }
                    },
                    /*
                    new StackLayout{
                        Orientation = StackOrientation.Horizontal,
                        VerticalOptions = LayoutOptions.Fill,
                        HorizontalOptions = LayoutOptions.Start,
                        Margin = new Thickness(10, 0),
                        Spacing = 10,
                        Children = {
                            new IconImage() {
                                Icon = "fa-car",
                                IconColor = Color.Black,
                                IconSize = 25,
                                WidthRequest = 30,
                                VerticalOptions = LayoutOptions.Start,
                                HorizontalOptions = LayoutOptions.Start
                            },
                            new Label {
                                HorizontalOptions = LayoutOptions.Fill,
                                //TextColor = TemaUtils.CorSecundaria,
                                Text = "Complemente sua renda fazendo pequenas entregas com seu carro ou pick-up do dia-a-dia.",
                                FontSize = 18
                            }
                        }
                    },
                    */
                    _AcessarContaButton,
                    _CriarContaButton
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
            _AcessarContaButton = new Button()
            {
                Text = "ACESSAR MINHA CONTA",
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Margin = new Thickness(10,0)
            };
            _AcessarContaButton.Clicked += (sender, e) => {                
                Navigation.PushAsync(LoginUtils.gerarLogin(() => {
                    App.Current.MainPage = App.gerarRootPage(new PrincipalPage());
                }));
            };
            _CriarContaButton = new Button()
            {
                Text = "CRIAR NOVA CONTA",
                //Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                Style = Estilo.Current[Estilo.BTN_SUCESSO],
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Margin = new Thickness(10, 0)
            };
            _CriarContaButton.Clicked += (sender, e) => {
                Navigation.PushModalAsync(new CadastroTipoPopup(true), true);
            };
        }
    }
}


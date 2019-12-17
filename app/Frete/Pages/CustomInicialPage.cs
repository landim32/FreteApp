using Emagine.Base.Estilo;
using Emagine.Frete.Pages;
using Frete.Utils;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Frete.Pages
{
    public class CustomInicialPage: InicialPage
    {
        public CustomInicialPage() : base()
        {
            _acessarContaButton.Text = "ACESSAR A MINHA CONTA";
            _acessarContaButton.FontSize = 18;
            _acessarContaButton.Style = Estilo.Current[Estilo.BTN_AVISO];

            _criarContaButton.Text = "CRIAR UMA NOVA CONTA";
            _criarContaButton.FontSize = 18;
            _criarContaButton.Style = Estilo.Current[Estilo.BTN_PRINCIPAL];
        }

        protected override void atualizarTela()
        {
            _mainLayout.Children.Clear();
            _mainLayout.Children.Add(new Image {
                Source = "logo.png",
                Margin = new Thickness(20, 20, 20, 10),
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Center
            });
            _mainLayout.Children.Add(gerarSubTitulo());
            /*
            _mainLayout.Children.Add( new StackLayout {
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
                        HorizontalTextAlignment = TextAlignment.Center,
                        Text = "Seja bem vindo",
                        FontSize = 18
                    }
                }
            });
            */
            _mainLayout.Children.Add(new Label {
                VerticalOptions = LayoutOptions.StartAndExpand,
                HorizontalOptions = LayoutOptions.Center,
                Margin = new Thickness(5, 10),
                Text = "VOCÊ JÁ POSSUI CONTA NO EASYBARCOS?"
            });
            _mainLayout.Children.Add(_acessarContaButton);
            _mainLayout.Children.Add(_criarContaButton);
        }

        private StackLayout gerarSubTitulo() {
            return new StackLayout {
                Orientation = StackOrientation.Vertical,
                VerticalOptions = LayoutOptions.StartAndExpand,
                HorizontalOptions = LayoutOptions.Center,
                Margin = new Thickness(30, 20),
                Children = {
                    new Label {
                        VerticalOptions = LayoutOptions.StartAndExpand,
                        HorizontalOptions = LayoutOptions.Center,
                        HorizontalTextAlignment = TextAlignment.Center,
                        Text = "UMA NOVA",
                        FontSize = 24
                    },
                    new Label {
                        VerticalOptions = LayoutOptions.StartAndExpand,
                        HorizontalOptions = LayoutOptions.Center,
                        HorizontalTextAlignment = TextAlignment.Center,
                        TextColor = Estilo.Current.WarningColor,
                        Text = "EXPERIÊNCIA",
                        FontAttributes = FontAttributes.Bold,
                        FontSize = 30
                    },
                    new Label {
                        VerticalOptions = LayoutOptions.StartAndExpand,
                        HorizontalOptions = LayoutOptions.Center,
                        HorizontalTextAlignment = TextAlignment.Center,
                        Text = "EM ATENDIMENTO MARÍTIMO",
                        FontSize = 24
                    }
                }
            };
        }

        protected override async void criarContaClicked(object sender, EventArgs e)
        {
            await FreteUtils.criarUsuario(() =>
            {
                executarLogin();
            });
        }
    }
}

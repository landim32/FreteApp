using Emagine.Base.Estilo;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Frete.Pages
{
    public class PagamentoSucessoPage : ContentPage
    {
        private Button _voltarButton;

        public PagamentoSucessoPage()
        {
            Title = "Aguardando Validação";
            inicializarComponente();
            Content = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Padding = 5,
                Spacing = 15,
                Children = {
                    new Label {
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Style = Estilo.Current[Estilo.TITULO1],
                        HorizontalTextAlignment = TextAlignment.Center,
                        Text = "Taxa de Adesão paga com sucesso!"
                    },
                    new Label {
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Text = "A taxa de adesão foi paga com sucesso e sua conta foi criada. Envie os documentos necessários " +
                            "para cadastromarinheiro@easybarcos.com.br para continuarmos com a validação do seu cadastro."
                    },
                    _voltarButton
                }
            };
        }

        private void inicializarComponente()
        {
            _voltarButton = new Button()
            {
                Text = "VOLTAR",
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.End,
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL]
            };
            _voltarButton.Clicked += (sender, e) => {
                Navigation.PopToRootAsync();
            };
        }
    }
}
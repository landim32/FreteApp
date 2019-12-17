using System;
using Emagine.Base.Estilo;
using EmagineFrete.Utils;
using FormsPlugin.Iconize;
using Xamarin.Forms;

namespace EmagineFrete.Cells
{
    public class PedidoPendenteCell : ViewCell
    {
        private Label _OrigemLabel;
        private Label _DestinoLabel;
        private Label _ValorLabel;
        private Label _DistanciaLabel;

        public PedidoPendenteCell()
        {
            inicializarComponente();

            View = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Children = {
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Spacing = 5,
                        Children = {
                            new IconImage{
                                HorizontalOptions = LayoutOptions.Start,
                                VerticalOptions = LayoutOptions.Center,
                                Icon = "fa-map-marker",
                                IconSize = 16,
                                WidthRequest = 20,
                                IconColor = Estilo.Current.PrimaryColor
                            },
                            _OrigemLabel
                        }
                    },
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Spacing = 5,
                        Children = {
                            new IconImage{
                                HorizontalOptions = LayoutOptions.Start,
                                VerticalOptions = LayoutOptions.Center,
                                Icon = "fa-map-marker",
                                IconSize = 16,
                                WidthRequest = 20,
                                IconColor = Estilo.Current.PrimaryColor
                            },
                            _DestinoLabel
                        }
                    },
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Spacing = 5,
                        Children = {
                            new IconImage{
                                HorizontalOptions = LayoutOptions.Start,
                                VerticalOptions = LayoutOptions.Center,
                                Icon = "fa-map",
                                IconSize = 16,
                                WidthRequest = 20,
                                IconColor = Estilo.Current.PrimaryColor
                            },
                            _DistanciaLabel
                        }
                    },
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Spacing = 5,
                        Children = {
                            new IconImage{
                                HorizontalOptions = LayoutOptions.Start,
                                VerticalOptions = LayoutOptions.Center,
                                Icon = "fa-dollar",
                                IconSize = 16,
                                WidthRequest = 20,
                                IconColor = Estilo.Current.PrimaryColor
                            },
                            _ValorLabel
                        }
                    },
                    new BoxView{
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Color = Estilo.Current.PrimaryColor,
                        HeightRequest = 1
                    }
                }
            };
        }

        private void inicializarComponente()
        {
            _OrigemLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.CenterAndExpand,
            };
            _OrigemLabel.SetBinding(Label.TextProperty, new Binding("EnderecoOrigem"));
            _DestinoLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.CenterAndExpand,
            };
            _DestinoLabel.SetBinding(Label.TextProperty, new Binding("EnderecoDestino"));
            _ValorLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                FontAttributes = FontAttributes.Bold,
            };
            _ValorLabel.SetBinding(Label.TextProperty, new Binding("Preco", stringFormat: "Valor: R$ {0:N2}"));
            _DistanciaLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                FontAttributes = FontAttributes.Bold,
            };
            _DistanciaLabel.SetBinding(Label.TextProperty, new Binding("DistanciaStr", stringFormat: "Distância: {0}"));

        }

    }
}


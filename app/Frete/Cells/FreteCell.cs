using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using FormsPlugin.Iconize;
using EmagineFrete.Model;
using EmagineFrete.Pages;
using EmagineFrete.Utils;
using Xamarin.Forms;
using Emagine.Base.Estilo;
using Emagine.Frete.Model;
using Emagine.Login.Factory;
using Emagine.Frete.Factory;
using Emagine.Pagamento.Model;

namespace EmagineFrete.Cells
{
    public class FreteCell : ViewCell
    {
        private StackLayout _mainLayout;
        private Label _OrigemLabel;
        //private Label _DestinoLabel;
        private Label _PesoLabel;
        private Label _DimensaoLabel;
        private Label _ValorLabel;
        private Label _DistanciaLabel;
        private Label _SituacaoLabel;
        //private MenuItem _excluirButton;

        public FreteCell()
        {
            inicializarComponente();

            /*_excluirButton = new MenuItem()
            {
                Text = "Excluir",
                IsDestructive = true
            };
            _excluirButton.SetBinding(MenuItem.CommandParameterProperty, new Binding("."));
            _excluirButton.Clicked += (sender, e) =>
            {
                //IImovelBLL regraImovel = ImovelFactory.create();
                FreteInfo frete = (FreteInfo)((MenuItem)sender).BindingContext;
                var mainPage = (NavigationPage)App.Current.MainPage;
                mainPage.Navigation.PushAsync(new FretePage());
            };
            ContextActions.Add(_excluirButton);*/

            _mainLayout = new StackLayout
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
                   /* new StackLayout {
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
                                IconColor = TemaUtils.CorPrincipal
                            },
                            _DestinoLabel
                        }
                    },*/
                    gerarAtributo(),
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Spacing = 5,
                        Children = {
                            new IconImage{
                                HorizontalOptions = LayoutOptions.Start,
                                VerticalOptions = LayoutOptions.Center,
                                Icon = "fa-arrows-alt",
                                IconSize = 16,
                                WidthRequest = 20,
                                IconColor = Estilo.Current.PrimaryColor
                            },
                            _DimensaoLabel
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
                                Icon = "fa-shopping-bag",
                                IconSize = 16,
                                WidthRequest = 20,
                                IconColor = Estilo.Current.PrimaryColor
                            },
                            _SituacaoLabel
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
            View = _mainLayout;
        }

        private Grid gerarAtributo() {
            var grid = new Grid
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
            };
            grid.Children.Add(new StackLayout
            {
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
            }, 0, 0);
            grid.Children.Add(new StackLayout
            {
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
            }, 1, 0);
            grid.Children.Add(new StackLayout
            {
                Orientation = StackOrientation.Horizontal,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Spacing = 5,
                Children = {
                    new IconImage{
                        HorizontalOptions = LayoutOptions.Start,
                        VerticalOptions = LayoutOptions.Center,
                        Icon = "fa-balance-scale",
                        IconSize = 16,
                        WidthRequest = 20,
                        IconColor = Estilo.Current.PrimaryColor
                    },
                    _PesoLabel
                }
            }, 2, 0);
            return grid;
        }

        private void inicializarComponente()
        {
            _OrigemLabel = new Label {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.CenterAndExpand,
            };
            _OrigemLabel.SetBinding(Label.TextProperty, new Binding("EnderecoDestino"));
            /*_DestinoLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.CenterAndExpand,
            };
            _DestinoLabel.SetBinding(Label.TextProperty, new Binding("EnderecoDestino"));*/
            _PesoLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                FontAttributes = FontAttributes.Bold,
            };
            _PesoLabel.SetBinding(Label.TextProperty, new Binding("Peso", stringFormat: "{0:N1}Kg"));
            _DimensaoLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                FontAttributes = FontAttributes.Bold,
            };
            _DimensaoLabel.SetBinding(Label.TextProperty, new Binding("Dimensao"));
            _ValorLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                FontAttributes = FontAttributes.Bold,
            };
            _ValorLabel.SetBinding(Label.TextProperty, new Binding("Preco", stringFormat: "R$ {0:N2}"));
            _DistanciaLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                FontAttributes = FontAttributes.Bold,
            };
            _DistanciaLabel.SetBinding(Label.TextProperty, new Binding("DistanciaStr", stringFormat: "{0:N2} km"));
            _SituacaoLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                FontAttributes = FontAttributes.Bold,
            };
            _SituacaoLabel.SetBinding(Label.TextProperty, new Binding("SituacaoStr"));
        }

        protected override void OnBindingContextChanged()
        {
            base.OnBindingContextChanged();
            var frete = (FreteInfo)BindingContext;

            //var regraUsuario = UsuarioFactory.create();
            //var usuario = regraUsuario.pegarAtual();

            var regraMotorista = MotoristaFactory.create();
            var motorista = regraMotorista.pegarAtual();

            if (frete != null && frete.IdPagamento > 0 && frete.Pagamento != null)
            {
                var texto = string.Empty;
                var cor = Estilo.Current.PrimaryColor;
                switch (frete.Pagamento.Tipo)
                {
                    case TipoPagamentoEnum.Boleto:
                        texto = (motorista != null) ? "Receber por Boleto Bancário" : "Pagar com Boleto Bancário";
                        cor = Estilo.Current.DangerColor;
                        break;
                    case TipoPagamentoEnum.CartaoOffline:
                        texto = (motorista != null) ? "Receber por máquina de cartão" : "Pagar com máquina de cartão";
                        cor = Estilo.Current.DangerColor;
                        break;
                    case TipoPagamentoEnum.CreditoOnline:
                        if (frete.Pagamento.Situacao == SituacaoPagamentoEnum.Pago)
                        {
                            texto = "Pago com cartão de crédito";
                            cor = Estilo.Current.SuccessColor;
                        }
                        else
                        {
                            texto = (motorista != null) ? "Receber por cartão de crédito" : "Pagar com cartão de crédito";
                            cor = Estilo.Current.DangerColor;
                        }
                        break;
                    case TipoPagamentoEnum.DebitoOnline:
                        if (frete.Pagamento.Situacao == SituacaoPagamentoEnum.Pago)
                        {
                            texto = "Pago com cartão de débito";
                            cor = Estilo.Current.SuccessColor;
                        }
                        else
                        {
                            texto = (motorista != null) ? "Receber por cartão de débito" : "Pagar com cartão de débito";
                            cor = Estilo.Current.DangerColor;
                        }
                        break;
                    case TipoPagamentoEnum.Dinheiro:
                        texto = (motorista != null) ? "Receber por dinheiro" : "Pagar com dinheiro";
                        cor = Estilo.Current.DangerColor;
                        break;
                }

                _mainLayout.Children.Insert(_mainLayout.Children.Count - 1, new StackLayout
                {
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
                            IconColor = cor
                        },
                        new Label {
                            HorizontalOptions = LayoutOptions.Start,
                            VerticalOptions = LayoutOptions.Start,
                            FontAttributes = FontAttributes.Bold,
                            TextColor = cor,
                            Text = texto
                        }
                    }
                });
            }
        }
    }
}

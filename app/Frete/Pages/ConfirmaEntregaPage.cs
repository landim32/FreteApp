using System;
using Acr.UserDialogs;
using FormsPlugin.Iconize;
using EmagineFrete.Model;
using EmagineFrete.Utils;
using Xamarin.Forms;
using Emagine.Frete.BLL;
using Emagine.Frete.Model;
using Emagine.Base.Estilo;
using System.Threading.Tasks;
using Emagine.Frete.Factory;
using Emagine.Frete.Utils;

namespace EmagineFrete.Pages
{
    public class ConfirmaEntregaPage : ContentPage
    {
        private FreteInfo _Info;
        private Label _EnderecoOrigem;
        private Label _EnderecoDestino;
        private Label _Duracao;
        //private Label _DuracaoEncomenda;
        private Label _DistanciaEncomenda;
        private Label _Distancia;
        private Label _Valor;
        private Button _Aceitar;
        private Button _Negar;


        public ConfirmaEntregaPage(FreteInfo Info)
        {
            _Info = Info;
            inicializarComponente();
            Title = "Resumo da entrega";
            Content = new StackLayout
            {
                Children = {
                    new StackLayout(){
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Orientation = StackOrientation.Vertical,
                        Padding = new Thickness(5, 10, 5, 10),
                        Spacing = 5,
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
                                        IconSize = 20,
                                        WidthRequest = 24,
                                        IconColor = Estilo.Current.PrimaryColor
                                    },
                                    _EnderecoOrigem
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
                                        IconSize = 20,
                                        WidthRequest = 24,
                                        IconColor = Estilo.Current.PrimaryColor
                                    },
                                    _EnderecoDestino
                                }
                            },
                            /*
                            new StackLayout {
                                Orientation = StackOrientation.Horizontal,
                                HorizontalOptions = LayoutOptions.Fill,
                                VerticalOptions = LayoutOptions.Start,
                                Spacing = 5,
                                Children = {
                                    new IconImage{
                                        HorizontalOptions = LayoutOptions.Start,
                                        VerticalOptions = LayoutOptions.Center,
                                        Icon = "fa-clock-o",
                                        IconSize = 20,
                                        WidthRequest = 24,
                                        IconColor = Estilo.Current.PrimaryColor
                                    },
                                    _DuracaoEncomenda
                                }
                            },
                            */
                            new StackLayout {
                                Orientation = StackOrientation.Horizontal,
                                HorizontalOptions = LayoutOptions.Fill,
                                VerticalOptions = LayoutOptions.Start,
                                Spacing = 5,
                                Children = {
                                    new IconImage{
                                        HorizontalOptions = LayoutOptions.Start,
                                        VerticalOptions = LayoutOptions.Center,
                                        Icon = "fa-map-o",
                                        IconSize = 20,
                                        WidthRequest = 24,
                                        IconColor = Estilo.Current.PrimaryColor
                                    },
                                    _DistanciaEncomenda
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
                                        Icon = "fa-clock-o",
                                        IconSize = 20,
                                        WidthRequest = 24,
                                        IconColor = Estilo.Current.PrimaryColor
                                    },
                                    _Duracao
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
                                        Icon = "fa-map-o",
                                        IconSize = 20,
                                        WidthRequest = 24,
                                        IconColor = Estilo.Current.PrimaryColor
                                    },
                                    _Distancia
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
                                        Icon = "fa-money",
                                        IconSize = 20,
                                        WidthRequest = 24,
                                        IconColor = Estilo.Current.PrimaryColor
                                    },
                                    _Valor
                                }
                            },
                            _Aceitar,
                            _Negar
                        }
                    }
                }
            };
        }

        protected override void OnAppearing()
        {
            base.OnAppearing();
            MotoristaUtils.Avisando = true;
        }

        protected override void OnDisappearing()
        {
            //AtualizacaoEntrega.setConfirm(false);
            MotoristaUtils.Avisando = false;
            base.OnDisappearing();
        }

        private async Task aceitaFreteAsync()
        {
            UserDialogs.Instance.ShowLoading("Confirmando...");
            try
            {
                //var ret = await new FreteBLL().aceitar(true, _Info.IdFrete, new MotoristaBLL().pegarAtual().Id);
                var ret = await new FreteBLL().aceitar(new AceiteEnvioInfo {
                    Aceite = true,
                    IdFrete = _Info.Id,
                    IdMotorista = MotoristaFactory.create().pegarAtual().Id
                });
                if (ret.Aceite){
                    UserDialogs.Instance.HideLoading();
                    UserDialogs.Instance.Alert("Frete aceito com sucesso", "Sucesso", "Entendi");
                    await Navigation.PopAsync();
                } else {
                    UserDialogs.Instance.HideLoading();
                    UserDialogs.Instance.Alert(ret.Mensagem, "Falha", "Entendi");
                }
            }
            catch (Exception e)
            {
                UserDialogs.Instance.HideLoading();
                UserDialogs.Instance.Alert("Erro ao confirmar o frete", "Falha", "Entendi");
            }
        }

        private async Task negaFreteAsync()
        {
            UserDialogs.Instance.ShowLoading("Negando...");
            try
            {
                //var ret = await new FreteBLL().aceitar(false, _Info.IdFrete, new MotoristaBLL().pegarAtual().Id);
                var ret = await new FreteBLL().aceitar(new AceiteEnvioInfo {
                    Aceite = false,
                    IdFrete = _Info.Id,
                    IdMotorista = MotoristaFactory.create().pegarAtual().Id
                });
                UserDialogs.Instance.HideLoading();
                await Navigation.PopAsync();
            }
            catch (Exception e)
            {
                UserDialogs.Instance.HideLoading();
                //AtualizacaoEntrega.setConfirm(false);
                MotoristaUtils.Avisando = false;
            }
        }

        private void inicializarComponente()
        { 
            _EnderecoOrigem = new Label(){
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                FontAttributes = FontAttributes.Bold,
                Text = "Endereço da encomenda: " + _Info.EnderecoOrigem
            };
            _EnderecoDestino = new Label()
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                FontAttributes = FontAttributes.Bold,
                Text = "Endereço da entrega: "  + _Info.EnderecoDestino
            };
            /*
            _DuracaoEncomenda = new Label()
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                FontAttributes = FontAttributes.Bold,
                Text = "Duração até a encomenda: " + _Info.Duracao
            };
            */
            _DistanciaEncomenda = new Label()
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                FontAttributes = FontAttributes.Bold,
                Text = "Distância até a encomenda: " + _Info.DistanciaStr
            };
            _Duracao = new Label()
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                FontAttributes = FontAttributes.Bold,
                Text = "Duração da entrega: " + _Info.TempoStr
            };
            _Distancia = new Label()
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                FontAttributes = FontAttributes.Bold,
                Text = "Distância da entrega: " + _Info.DistanciaStr
            };
            _Valor = new Label()
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                FontAttributes = FontAttributes.Bold,
                Text = "Valor: R$ " + _Info.Preco.ToString("N2")
            };
            _Aceitar = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.End,
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                Text = "Aceitar"
            };
            _Aceitar.Clicked += (sender, e) => {
                aceitaFreteAsync();
            };
            _Negar = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.End,
                BackgroundColor = Color.Red,
                TextColor = Color.White,
                Text = "Negar"
            };
            _Negar.Clicked += (sender, e) => {
                negaFreteAsync();
            };
        }
    }
}


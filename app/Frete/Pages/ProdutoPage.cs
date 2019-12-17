using System;
using System.Collections.Generic;
using Acr.UserDialogs;
using FormsPlugin.Iconize;
using EmagineFrete.Controls;
using EmagineFrete.Model;
using EmagineFrete.Utils;
using Xamarin.Forms;
using Emagine.Login.BLL;
using Emagine.Frete.Model;
using Emagine.Base.Estilo;
using Emagine.Base.Model;
using Emagine.Base.Utils;
using Emagine.Frete.Factory;
using System.Linq;

namespace EmagineFrete.Pages
{
    public class ProdutoPage : ContentPage
    {
        private bool _inicializado = false;
        private IList<TipoVeiculoInfo> _tiposVeiculo;

        private ProdutoButton _MotoButton;
        private ProdutoButton _CarroButton;
        private ProdutoButton _UtilitarioButton;
        private ProdutoButton _CaminhaoButton;

        private Button _EnviarButton;
        private Label _ObservacaoLbl;

        private Entry _PesoEntry;
        private Entry _LarguraEntry;
        private Entry _AlturaEntry;
        private Entry _ProfundidadeEntry;

        public ProdutoPage()
        {
            Title = "Escolha o transporte";
            inicializarComponente();
            Content = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Children = {
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.CenterAndExpand,
                        VerticalOptions = LayoutOptions.Start,
                        Margin = new Thickness(5, 10, 5, 0),
                        Children = {
                            _MotoButton,
                            _CarroButton,
                            _UtilitarioButton,
                            _CaminhaoButton
                        }
                    },
                    gerarPainelProduto(),
                    _EnviarButton,
                    _ObservacaoLbl
                }
            };
        }

        private void inicializarComponente() {
            _MotoButton = new ProdutoButton("fa-motorcycle", "Moto", true);
            _MotoButton.AoClicar = (sender, e) => {
                _MotoButton.Marcado = true;
                _CarroButton.Marcado = false;
                _UtilitarioButton.Marcado = false;
                _CaminhaoButton.Marcado = false;
            };
            _CarroButton = new ProdutoButton("fa-car", "Carro");
            _CarroButton.AoClicar = (sender, e) => {
                _MotoButton.Marcado = false;
                _CarroButton.Marcado = true;
                _UtilitarioButton.Marcado = false;
                _CaminhaoButton.Marcado = false;
            };
            _UtilitarioButton = new ProdutoButton("fa-bus", "Utilitário");
            _UtilitarioButton.AoClicar = (sender, e) => {
                _MotoButton.Marcado = false;
                _CarroButton.Marcado = false;
                _UtilitarioButton.Marcado = true;
                _CaminhaoButton.Marcado = false;
            };
            /*
            if(GlobalUtils.getAplicacaoAtual() == AplicacaoEnum.APLICACAO02){
                _CaminhaoButton = new ProdutoButton("fa-truck", "#Exclusive");    
            } else {
            */
            _CaminhaoButton = new ProdutoButton("fa-truck", "Caminhão");
            //}

            _CaminhaoButton.AoClicar = (sender, e) => {
                /*
                if (GlobalUtils.getAplicacaoAtual() == AplicacaoEnum.APLICACAO02)
                {
                    Navigation.PushAsync(new FormMailNvoid());
                }
                else 
                {
                */
                _MotoButton.Marcado = false;
                _CarroButton.Marcado = false;
                _UtilitarioButton.Marcado = false;
                _CaminhaoButton.Marcado = true;
                //}
            };


            _PesoEntry = new Entry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                //WidthRequest = 120,
                //HeightRequest = 30,
                BackgroundColor = Estilo.Current.PrimaryColor,
                TextColor = Color.White,
                FontSize = 14,
                Keyboard = Keyboard.Numeric,
                HorizontalTextAlignment = TextAlignment.End,
                //Text = "80"
            };

            _LarguraEntry = new Entry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                BackgroundColor = Estilo.Current.PrimaryColor,
                TextColor = Color.White,
                FontSize = 14,
                Keyboard = Keyboard.Numeric,
                HorizontalTextAlignment = TextAlignment.End,
                //Text = "40"
            };

            _AlturaEntry = new Entry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                BackgroundColor = Estilo.Current.PrimaryColor,
                TextColor = Color.White,
                FontSize = 14,
                Keyboard = Keyboard.Numeric,
                HorizontalTextAlignment = TextAlignment.End,
                //Text = "60"
            };

            _ProfundidadeEntry = new Entry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                BackgroundColor = Estilo.Current.PrimaryColor,
                TextColor = Color.White,
                FontSize = 14,
                Keyboard = Keyboard.Numeric,
                HorizontalTextAlignment = TextAlignment.End,
                //Text = "10"
            };

            _ObservacaoLbl = new Label
            {
                FontSize = 11,
                TextColor = Color.DarkGray,
                Margin = 10
            };
            if(GlobalUtils.getAplicacaoAtual() == AplicacaoEnum.APLICACAO02){
                //_ObservacaoLbl.Text = "Observação: O transporte só será realizado mediante as características do volume e peso; devendo se adequar ao tipo de veículo da prestação do serviço.";
                //_ObservacaoLbl.Text = "Observação: Em caso de ociosidade no serviço, entre em contato conosco. Para maior comodidade e pontualidade, utilize a aba #exclusive.";
                string texto = "";
                texto += "Observações: Em caso de ociosidade no serviço, entre em contato conosco. Para maior comodidade e pontualidade, utilize a opção #exclusive.\n\n";
                texto += "O transporte só será realizado mediante as características do volume e peso, devendo se adequar ao tipo de veículo selecionado para a prestação do serviço.\n\n";
                texto += "O valor cobrado se restringe apenas ao transporte, não incluindo carga e descarga de mercadorias.";
                _ObservacaoLbl.Text = texto;
            }

            _EnviarButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(8, 0),
                BackgroundColor = Estilo.Current.PrimaryColor,
                TextColor = Color.White,
                Text = "Continuar"
            };
            _EnviarButton.Clicked += (sender, e) => {
                continuaPedido();
            };
        }

        private void continuaPedido(){
            if(validaPedido()){
                var infoPedido = new FreteInfo()
                {
                    Altura = double.Parse(_AlturaEntry.Text),
                    Largura = double.Parse(_LarguraEntry.Text),
                    Peso = double.Parse(_PesoEntry.Text),
                    Profundidade = double.Parse(_ProfundidadeEntry.Text),
                    //CodigoVeiculo = getCodigoVeiculo(),
                    IdUsuario = new UsuarioBLL().pegarAtual().Id
                };
                if (_MotoButton.Marcado) {
                    var tipo = (from t in _tiposVeiculo where t.Tipo == TipoVeiculoEnum.Moto select t).FirstOrDefault();
                    if (tipo != null) {
                        infoPedido.Veiculos.Add(tipo);
                    }
                }

                if (_CarroButton.Marcado) {
                    var tipo = (from t in _tiposVeiculo where t.Tipo == TipoVeiculoEnum.Carro select t).FirstOrDefault();
                    if (tipo != null)
                    {
                        infoPedido.Veiculos.Add(tipo);
                    }
                }
                if (_UtilitarioButton.Marcado) {
                    var tipo = (from t in _tiposVeiculo where t.Tipo == TipoVeiculoEnum.Caminhonete select t).FirstOrDefault();
                    if (tipo != null)
                    {
                        infoPedido.Veiculos.Add(tipo);
                    }
                }
                if (_CaminhaoButton.Marcado) {
                    var tipo = (from t in _tiposVeiculo where t.Tipo == TipoVeiculoEnum.Caminhao select t).FirstOrDefault();
                    if (tipo != null)
                    {
                        infoPedido.Veiculos.Add(tipo);
                    }
                }
                Navigation.PushAsync(new RotaProdutoPage(infoPedido));
            } else {
                DisplayAlert("Atenção", "Dados inválidos, verifique todas as entradas.", "Entendi");
            }
        }

        private bool validaPedido(){
            double dblAux;
            if (_AlturaEntry.Text == String.Empty || !double.TryParse(_AlturaEntry.Text, out dblAux))
                return false;
            if (_LarguraEntry.Text == String.Empty || !double.TryParse(_LarguraEntry.Text, out dblAux))
                return false;
            if (_PesoEntry.Text == String.Empty || !double.TryParse(_PesoEntry.Text, out dblAux))
                return false;
            if (_ProfundidadeEntry.Text == String.Empty || !double.TryParse(_ProfundidadeEntry.Text, out dblAux))
                return false;
            return true;
        }

        /*
        public TipoVeiculoEnum getCodigoVeiculo(){
            if (_MotoButton.Marcado)
                return TipoVeiculoEnum.Moto;
            if (_CarroButton.Marcado)
                return TipoVeiculoEnum.Carro;
            if (_UtilitarioButton.Marcado)
                return TipoVeiculoEnum.Caminhonete;
            if (_CaminhaoButton.Marcado)
                return TipoVeiculoEnum.Caminhao;
            return TipoVeiculoEnum.Moto;
        }
        */

        private Grid gerarPainelProduto()
        {
            var grid = new Grid {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(10, 5),
                Padding = 5,
                RowSpacing = 0,
                BackgroundColor = Estilo.Current.PrimaryColor,
            };
            grid.ColumnDefinitions.Add(new ColumnDefinition { Width = new GridLength(4, GridUnitType.Star) });
            grid.ColumnDefinitions.Add(new ColumnDefinition { Width = new GridLength(3, GridUnitType.Absolute) });
            grid.ColumnDefinitions.Add(new ColumnDefinition { Width = new GridLength(2, GridUnitType.Star) });
            grid.RowDefinitions.Add(new RowDefinition { Height = new GridLength(23, GridUnitType.Absolute) });
            grid.RowDefinitions.Add(new RowDefinition { Height = new GridLength(3, GridUnitType.Absolute) });
            grid.RowDefinitions.Add(new RowDefinition { Height = new GridLength(1, GridUnitType.Star) });

            var labelProduto = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                VerticalTextAlignment = TextAlignment.Center,
                Text = "Medidas do Produto",
                TextColor = Color.White,
                FontSize = 16
            };
            grid.Children.Add(labelProduto, 0, 0);
            Grid.SetColumnSpan(labelProduto, 3);

            var linhaHorizontal = new BoxView
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                BackgroundColor = Color.White,
                HeightRequest = 1
            };
            grid.Children.Add(linhaHorizontal, 0, 1);
            Grid.SetColumnSpan(linhaHorizontal, 3);

            var labelPeso = new Label
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Center,
                Text = "Peso(kg)",
                TextColor = Color.White,
                FontSize = 14
            };
            var linhaVertical = new BoxView
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Fill,
                BackgroundColor = Color.White,
                WidthRequest = 1//,
                //HeightRequest = 70
            };
            grid.Children.Add(labelPeso, 0, 2);
            grid.Children.Add(linhaVertical, 1, 2);
            grid.Children.Add(_PesoEntry, 2, 2);
            Grid.SetRowSpan(linhaVertical, 4);

            var labelLargura = new Label
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Center,
                Text = "Largura(cm)",
                TextColor = Color.White,
                FontSize = 14
            };
            grid.Children.Add(labelLargura, 0, 3);
            grid.Children.Add(_LarguraEntry, 2, 3);

            var labelAltura = new Label
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Center,
                Text = "Altura(cm)",
                TextColor = Color.White,
                FontSize = 14
            };
            grid.Children.Add(labelAltura, 0, 4);
            grid.Children.Add(_AlturaEntry, 2, 4);

            var labelProfundidade = new Label
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Center,
                Text = "Comprimento(cm)",
                TextColor = Color.White,
                FontSize = 14
            };
            grid.Children.Add(labelProfundidade, 0, 5);
            grid.Children.Add(_ProfundidadeEntry, 2, 5);
            return grid;
        }

        protected override async void OnAppearing()
        {
            base.OnAppearing();
            if (!_inicializado)
            {
                UserDialogs.Instance.ShowLoading("Enviando...");
                try
                {
                    var regraTipoVeiculo = TipoVeiculoFactory.create();
                    _tiposVeiculo = await regraTipoVeiculo.listar();
                    //TipoVeiculoInfo
                    UserDialogs.Instance.HideLoading();
                }
                catch (Exception erro)
                {
                    UserDialogs.Instance.HideLoading();
                    await UserDialogs.Instance.AlertAsync(erro.Message, "Erro", "Entendi");
                }
                _inicializado = true;
            }
        }

    }
     
}


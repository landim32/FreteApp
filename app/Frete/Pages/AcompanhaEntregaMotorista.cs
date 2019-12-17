using System;
using System.Collections.Generic;
using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Frete.BLL;
using Emagine.Frete.Factory;
using Emagine.Frete.Model;
using Emagine.Frete.Utils;
using Emagine.Mapa.Controls;
using Emagine.Mapa.Utils;
using EmagineFrete.Controls;
using EmagineFrete.Model;
using EmagineFrete.Utils;
using Plugin.Geolocator;
using Plugin.Geolocator.Abstractions;
using Xamarin.Forms;
using Xamarin.Forms.Maps;

namespace EmagineFrete.Pages
{
    public class AcompanhaEntregaMotorista : ContentPage
    {
        private FreteInfo _frete;
        private MotoristaInfo _motorista;

        private CustomMap _customMap;
        private Button _acaoButton;
        private Button _abrirRotaButton;
        private Button _contatoButton;
        private Label _distanciaLabel;
        private Label _tempoLabel;
        private Picker _rotaPicker;

        //private event EventHandler<PositionEventArgs> _aoAlterarPosicao;


        public AcompanhaEntregaMotorista(FreteInfo frete)
        {
            _frete = frete;
            inicializarComponente();
            Title = "Acompanhar entrega";
            Content = new StackLayout()
            {
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    _customMap,
                    _distanciaLabel,
                    _tempoLabel,
                    _contatoButton,
                    _rotaPicker,
                    _abrirRotaButton,
                    _acaoButton
                }
            };
            if (_frete.Situacao == FreteSituacaoEnum.Entregue)
            {
                ((StackLayout)Content).Children.Remove(_acaoButton);
            }
            /*
            this._aoAlterarPosicao += async (sender, e) =>
            {
                var retPedido = await new MotoristaBLL().listarPedidosAsync();
                apresentaInfo(retPedido, new Position(e.Position.Latitude, e.Position.Longitude));
            };
            */
        }

        protected override void OnDisappearing()
        {
            CrossGeolocator.Current.PositionChanged -= atualizarPosicao;
            CrossGeolocator.Current.StopListeningAsync().Wait();
            //AtualizacaoEntrega.setConfirm(false);
            MotoristaUtils.Avisando = false;
            base.OnDisappearing();  
        }

        protected override async void OnAppearing()
        {
            base.OnAppearing();
            //var retPedido = await new MotoristaBLL().listarPedidosAsync();
            if (MapsUtils.IsLocationAvailable())
            {
                var posicao = await CrossGeolocator.Current.GetLastKnownLocationAsync();

                //var regraFrete = FreteFactory.create();
                //var retorno = await regraFrete.atualizar(_frete.Id);
                var regraMotorista = MotoristaFactory.create();
                _motorista = regraMotorista.pegarAtual();
                if (_motorista == null) {
                    throw new Exception("Você não está logado como motorista.");
                }
                var envio = new MotoristaEnvioInfo {
                    IdMotorista = _motorista.Id,
                    IdFrete = _frete.Id,
                    Latitude = posicao.Latitude,
                    Longitude = posicao.Longitude,
                    CodDisponibilidade = 1
                };
                var retorno = await regraMotorista.atualizar(envio);
                atualizarTela(retorno, posicao);
                //apresentaInfo(info, new Position(ret.Latitude, ret.Longitude));
                if (CrossGeolocator.Current.IsListening)
                {
                    await CrossGeolocator.Current.StopListeningAsync();
                }
                //await CrossGeolocator.Current.StartListeningAsync(new TimeSpan(5000), 20);
                await CrossGeolocator.Current.StartListeningAsync(new TimeSpan(10000), 20);
                //CrossGeolocator.Current.PositionChanged += testeAsync;
                CrossGeolocator.Current.PositionChanged += atualizarPosicao;
            }
        }

        private async void atualizarPosicao(object sender, PositionEventArgs e)
        {
            if (e.Position != null)
            {
                var regraMotorista = MotoristaFactory.create();
                var envio = new MotoristaEnvioInfo
                {
                    IdMotorista = _motorista.Id,
                    IdFrete = _frete.Id,
                    Latitude = e.Position.Latitude,
                    Longitude = e.Position.Longitude,
                    CodDisponibilidade = 1
                };
                var retorno = await regraMotorista.atualizar(envio);
                atualizarTela(retorno, e.Position);
            }
        }


        //private void apresentaInfo(MotoristaRetornoInfo info, Position localizacaoAtual)
        private void atualizarTela(MotoristaRetornoInfo retorno, Plugin.Geolocator.Abstractions.Position posicao)
        {
            _customMap.Polyline = MapaUtils.decodePolyline(retorno.Polyline);
            var pos = new Xamarin.Forms.Maps.Position(posicao.Latitude, posicao.Longitude);
            _customMap.MoveToRegion(MapSpan.FromCenterAndRadius(pos, Distance.FromMiles(0.1)));
            _distanciaLabel.Text = "Distância até o destino: " + retorno.DistanciaStr;
            _tempoLabel.Text = "Tempo até o destino: " + retorno.TempoStr;
        }

        private async System.Threading.Tasks.Task confirmaPegaEntregaAsync()
        {
            UserDialogs.Instance.ShowLoading("Aguarde...");
            try{
                var regraFrete = FreteFactory.create();
                //regraFrete.alterarSituacao(_IdEntrega, )
                //var infoEntrega = await new FreteBLL().pegar(_IdEntrega);
                if (_frete.Situacao == FreteSituacaoEnum.PegandoEncomenda)
                {
                    _frete.Situacao = FreteSituacaoEnum.Entregando;
                }
                else if (_frete.Situacao == FreteSituacaoEnum.Entregando)
                {
                    _frete.Situacao = FreteSituacaoEnum.Entregue;
                }
                try{
                    //await new FreteBLL().alterar(infoEntrega);
                    await regraFrete.alterarSituacao(_frete.Id, _frete.Situacao);
                    //_Situacao = infoEntrega.Situacao;
                    UserDialogs.Instance.HideLoading();
                    if (_frete.Situacao == FreteSituacaoEnum.Entregando)
                        _acaoButton.Text = "Entreguei a encomenda";
                    else if (_frete.Situacao == FreteSituacaoEnum.Entregue)
                    {
                        await UserDialogs.Instance.AlertAsync("Obrigado, agora é só aguardar a confirmação de entrega.");
                        await Navigation.PopToRootAsync();
                    }
                } catch(Exception e){
                    UserDialogs.Instance.HideLoading();
                    UserDialogs.Instance.Alert("Ocorreu um erro ao tentar alterar a situação da entrega", "Falha");
                }

            } catch(Exception e){
                UserDialogs.Instance.HideLoading();
            }
        }

        private async void mostraDadosEntrega(){
            //UserDialogs.Instance.ShowLoading("Carregando...");
            //var ret = await new FreteBLL().pegar(_IdEntrega);
            //UserDialogs.Instance.HideLoading();
            UserDialogs.Instance.Alert(
                "Usuário: " + _frete.Usuario.Nome +
                "\nTelefone: " + _frete.Usuario.Telefone +
                "\nObservação: " + _frete.Observacao,
                "Dados da entrega", "Fechar");
        } 

        private void inicializarComponente()
        {
            _customMap = new CustomMap
            {
                MapType = MapType.Street,
                VerticalOptions = LayoutOptions.FillAndExpand,
                HorizontalOptions = LayoutOptions.Fill,
                IsShowingUser = true
            };

            _acaoButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(8, 0),
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                Text = (_frete.Situacao == FreteSituacaoEnum.PegandoEncomenda ? "Peguei a encomenda" : "Entreguei a encomenda")
            };
            _acaoButton.Clicked += (sender, e) => {
                confirmaPegaEntregaAsync();
            };

            _abrirRotaButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(8, 0),
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                Text = "Ver rota externamente"
            };
            _abrirRotaButton.Clicked += (sender, e) => {
                _rotaPicker.Focus();
            };

            _rotaPicker = new Picker()
             {
                 ItemsSource = new List<string>(){
                        "Waze",
                        "Maps"
                    },
                 HeightRequest = 0,
                IsVisible = false
             };
            _rotaPicker.SelectedIndexChanged += async (sender2, e2) =>
            {
                //UserDialogs.Instance.ShowLoading("Aguarde...");
                //var infoEntrega = await new FreteBLL().pegar(_IdEntrega);
                //UserDialogs.Instance.HideLoading();
                switch ((string)_rotaPicker.SelectedItem)
                {
                    case "Maps":
                        Device.OpenUri(new Uri("http://maps.google.com/maps?daddr=" + _frete.EnderecoDestino));
                        break;
                    case "Waze":
                        Device.OpenUri(new Uri("waze://?q=" + _frete.EnderecoDestino));
                        break;
                }
            };

            _contatoButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(8, 0),
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                Text = "Ver dados do pedido"
            };
            _contatoButton.Clicked += (sender, e) => {
                mostraDadosEntrega();
            };


            _distanciaLabel = new Label()
            {
                VerticalOptions = LayoutOptions.End,
                HorizontalOptions = LayoutOptions.Fill,
                HeightRequest = 22,
                Margin = new Thickness(8, 0),
                FontSize = 18,
                TextColor = Color.Black
            };
            _tempoLabel = new Label()
            {
                VerticalOptions = LayoutOptions.End,
                HorizontalOptions = LayoutOptions.Fill,
                Margin = new Thickness(8, 0),
                HeightRequest = 22,
                FontSize = 18,
                TextColor = Color.Black
            };

        }
    }
}


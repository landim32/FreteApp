﻿using System;
using System.Threading.Tasks;
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
using Xamarin.Forms;
using Xamarin.Forms.Maps;

namespace EmagineFrete.Pages
{
    public class AcompanhaEntrega : ContentPage
    {
        private CustomMap _CustomMap;
        private Button _AcaoButton;
        private Label _Distancia;
        private Label _Tempo;
        private int _IdEntrega;
        private FreteSituacaoEnum _Situacao;
        private string _LastLatLon = "";
        private static Task task;
        private static int delay = 5000;
        private FreteInfo _FreteInfo;

        public AcompanhaEntrega(FreteRetornoInfo info)
        {
            _IdEntrega = (int)info.IdFrete;
            _Situacao = (FreteSituacaoEnum)info.Situacao;
            inicializarComponente();
            iniciaAsync(info).Wait();
            Title = "Acompanhar entrega";
            Content = new StackLayout()
            {
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    _CustomMap,
                    _Distancia,
                    _Tempo,
                    _AcaoButton
                }
            };
        }

        protected override void OnDisappearing()
        {
            //AtualizacaoEntrega.setConfirm(false);
            MotoristaUtils.Avisando = false;
            base.OnDisappearing();
        }

        public async Task iniciaAsync(FreteRetornoInfo info)
        {
            if((info.Latitude + ";" + info.Longitude) != _LastLatLon){
                Device.BeginInvokeOnMainThread(async () =>
                {
                    if (_FreteInfo == null)
                    {
                        _FreteInfo = await new FreteBLL().pegar(info.IdFrete);
                    }
                    apresentaInfo(info, new Position(info.Latitude, info.Longitude));
                });

                _LastLatLon = (info.Latitude + ";" + info.Longitude);
            }

            task = Task.Factory.StartNew(async () =>
            {
                await Task.Delay(delay);
                var retInfo = await new FreteBLL().atualizar(_IdEntrega);
                await iniciaAsync(retInfo);
            });

        }

        private void apresentaInfo(FreteRetornoInfo info, Position localizacaoAtual)
        {

            _AcaoButton.Text = getMensagemBotao(info.Situacao);
            _AcaoButton.IsEnabled = (info.Situacao == FreteSituacaoEnum.Entregue ? true : false);
            _CustomMap.Polyline = MapaUtils.decodePolyline(info.Polyline);
            /*
            if (_CustomMap.RouteCoordinates == null || _CustomMap.RouteCoordinates.Count == 0)
            {
                foreach (var ponto in pontos)
                {
                    _CustomMap.RouteCoordinates.Add(ponto);
                }
            }
            else
            {
                _CustomMap.addPolyline(pontos);
            }
            */
            _CustomMap.MoveToRegion(MapSpan.FromCenterAndRadius(localizacaoAtual, Distance.FromMiles(0.1)));

            var pin = new Pin
            {
                Type = PinType.Place,
                Position = localizacaoAtual,
                Label = "Parceiro",
                Address = "Toque para ver detalhes..."
            };
            pin.Clicked += (sender, e) => {
                UserDialogs.Instance.Alert((_FreteInfo == null ?
                         "Obtendo parceiro"
                         : "Nome: " + _FreteInfo.Motorista.Usuario.Nome
                         + "\nTelefone: " + _FreteInfo.Motorista.Usuario.Telefone
                         + "\nPlaca: " + _FreteInfo.Motorista.Placa), "Parceiro", "Fechar");
            };
            _CustomMap.Pins.Clear();
            _CustomMap.Pins.Add(pin);
            _Distancia.Text = "Distância até o destino: " + info.DistanciaStr;
            _Tempo.Text = "Tempo até o destino: " + info.TempoStr;
        }

        private async System.Threading.Tasks.Task confirmaPegaEntregaAsync()
        {
            UserDialogs.Instance.ShowLoading("Aguarde...");
            try
            {
                var regraFrete = FreteFactory.create();
                var infoEntrega = await regraFrete.pegar(_IdEntrega);
                infoEntrega.Situacao = FreteSituacaoEnum.EntregaConfirmada;
                try
                {
                    //await new FreteBLL().alterar(infoEntrega);
                    await regraFrete.alterarSituacao(infoEntrega.Id, infoEntrega.Situacao);
                    _Situacao = infoEntrega.Situacao;
                    UserDialogs.Instance.HideLoading();
                    if (_Situacao == FreteSituacaoEnum.EntregaConfirmada){
                        _AcaoButton.Text = "Entreguei a encomenda";
                        _AcaoButton.IsEnabled = false; 
                        await UserDialogs.Instance.AlertAsync("Entrega confirmada");
                        await Navigation.PopToRootAsync();
                    }
                }
                catch (Exception e)
                {
                    UserDialogs.Instance.HideLoading();
                    UserDialogs.Instance.Alert("Ocorreu um erro ao tentar alterar a situação da entrega", "Falha");
                }

            }
            catch (Exception e)
            {
                UserDialogs.Instance.HideLoading();
            }
        }

        private string getMensagemBotao(FreteSituacaoEnum sit){
            switch(sit){
                case FreteSituacaoEnum.Cancelado:
                    return "Entrega cancelada";
                case FreteSituacaoEnum.EntregaConfirmada:
                    return "Entrega confirmada";
                case FreteSituacaoEnum.Entregando:
                    return "Parceiro a caminho";
                case FreteSituacaoEnum.Entregue:
                    return "Confirmar entrega";
                case FreteSituacaoEnum.PegandoEncomenda:
                    return "Parceiro a caminho da entrega";
                case FreteSituacaoEnum.ProcurandoMotorista:
                    return "Aguardando parceiro";

            }
            return "Status desconhecido.";
        }

        private void inicializarComponente()
        {
            _AcaoButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(8, 0),
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                Text = getMensagemBotao(_Situacao),
                IsEnabled = (_Situacao == FreteSituacaoEnum.Entregue ? true : false)
            };
            _AcaoButton.Clicked += (sender, e) => {
                confirmaPegaEntregaAsync();
            };


            _Distancia = new Label()
            {
                VerticalOptions = LayoutOptions.End,
                HorizontalOptions = LayoutOptions.Fill,
                HeightRequest = 22,
                Margin = new Thickness(8, 0),
                FontSize = 18,
                TextColor = Color.Black
            };
            _Tempo = new Label()
            {
                VerticalOptions = LayoutOptions.End,
                HorizontalOptions = LayoutOptions.Fill,
                Margin = new Thickness(8, 0),
                HeightRequest = 22,
                FontSize = 18,
                TextColor = Color.Black
            };

            _CustomMap = new CustomMap
            {
                MapType = MapType.Street,
                VerticalOptions = LayoutOptions.FillAndExpand,
                HorizontalOptions = LayoutOptions.Fill
            };

        }
    }
}

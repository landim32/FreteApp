using System;
using System.Linq;
using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Frete.Factory;
using Emagine.Frete.Model;
using Emagine.Frete.Utils;
using Emagine.Mapa.Controls;
using Emagine.Mapa.Utils;
using Emagine.Pagamento.Model;
using EmagineFrete.Controls;
using EmagineFrete.Model;
using EmagineFrete.Utils;
using Xamarin.Forms;
using Xamarin.Forms.Maps;

namespace EmagineFrete.Pages
{
    public class DetalheProdutoPage : ContentPage
    {
        private CustomMap _CustomMap;
        private FreteInfo _Info;
        private Button _EnviarButton;
        private Label _ObservacaoLbl;
        private Label _Valor;
        //private bool _Exclusive;

        public DetalheProdutoPage(FreteInfo info)
        {
            _Info = info;
            //_Exclusive = _Info.Distancia > 150000;
            inicializarComponente();
            Title = "Resumo";
            Content = new StackLayout(){
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    _CustomMap,
                    new Label(){
                        Text = "Distância: " + info.DistanciaStr,
                        VerticalOptions = LayoutOptions.End,
                        HorizontalOptions = LayoutOptions.Fill,
                        HeightRequest = 22,
                        Margin = new Thickness(8, 0),
                        FontSize = 18,
                        TextColor = Color.Black
                    },
                    new Label(){
                        Text = "Tempo: " + info.TempoStr,
                        VerticalOptions = LayoutOptions.End,
                        HorizontalOptions = LayoutOptions.Fill,
                        Margin = new Thickness(8, 0),
                        HeightRequest = 22,
                        FontSize = 18,
                        TextColor = Color.Black
                    },
                    _Valor,
                    _EnviarButton,
                    _ObservacaoLbl
                }
            };
        }

        private string getTextItem(FreteLocalTipoEnum tipo)
        {
            switch (tipo)
            {
                case FreteLocalTipoEnum.Saida:
                    return "Carga";
                case FreteLocalTipoEnum.Destino:
                    return "Destino";
                case FreteLocalTipoEnum.Parada:
                    return "Parada";
                default:
                    return "-";
            }
        }

        private void inicializarComponente(){
            _EnviarButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(8, 0),
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                //Text = _Exclusive ? "Ir para #Exclusive" : "Ir para pagamento"
                Text = "Ir para pagamento"
            };

            _EnviarButton.Clicked += (sender, e) => {
                var frete = _Info;
                var pagamentoPage = FreteUtils.gerarPagamento(frete, (pagamento) =>
                {
                    UserDialogs.Instance.Toast("Frete cadastrado com sucesso. Procurando motorista!");
                    Navigation.PopToRootAsync();
                });
                Navigation.PushAsync(pagamentoPage);
            };

            /*
            if(!_Exclusive){
                _Valor = new Label()
                {
                    Text = "Preço: R$ " + _Info.Preco.ToString("N2"),
                    VerticalOptions = LayoutOptions.End,
                    HorizontalOptions = LayoutOptions.Fill,
                    HeightRequest = 22,
                    FontSize = 18,
                    TextColor = Color.Black,
                    Margin = new Thickness(8, 0, 8, 10)
                };
                _ObservacaoLbl = new Label
                {
                    FontSize = 9,
                    TextColor = Color.DarkGray,
                    Margin = 10
                };
            } else {
            */
            _Valor = new Label
            {
                HeightRequest = 0
            };
            _ObservacaoLbl = new Label
            {
                HeightRequest = 0
            };
            //}

            _CustomMap = new CustomMap
            {
                MapType = MapType.Street,
                VerticalOptions = LayoutOptions.FillAndExpand,
                HorizontalOptions = LayoutOptions.Fill
            };
            var pontos = MapaUtils.decodePolyline(_Info.Polyline);
            _CustomMap.Polyline = pontos;
            foreach(var pin in _Info.Locais){
                if (pin.Latitude.HasValue && pin.Longitude.HasValue)
                {
                    _CustomMap.Pins.Add(new Pin()
                    {
                        Position = new Position(pin.Latitude.Value, pin.Longitude.Value),
                        Label = getTextItem(pin.Tipo)
                    });
                }
            }
            var aux = _Info.Locais.First();
            var midleLat = pontos.Average(x => x.Latitude);
            var midleLon = pontos.Average((x => x.Longitude));
            var degressLat = Math.Abs(pontos.Max(x => x.Latitude) - pontos.Min(x => x.Latitude));
            var degressLon = Math.Abs(pontos.Max(x => x.Longitude) - pontos.Min(x => x.Longitude));
            _CustomMap.MoveToRegion(new MapSpan(new Position(midleLat, midleLon), degressLat + (degressLat*0.2), degressLon + (degressLon * 0.2)));
        }
    }
}


using System;
using System.Collections.Generic;
using Emagine.Base.Estilo;
using Emagine.Frete.Model;
using EmagineFrete.Cells;
using EmagineFrete.Model;
using EmagineFrete.Utils;
using Xamarin.Forms;

namespace EmagineFrete.Pages
{
    public class RotaProdutoPage : ContentPage
    {
        public List<PontoTransporte> _ListaPontos;

        private FreteInfo _FreteInfo;
        private Button _EnviarButton;

        public RotaProdutoPage(FreteInfo FreteInfo)
        {
            _FreteInfo = FreteInfo;
            Title = "Escolha a rota";
            inicializarComponente();
            Content = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Fill,
                Children = {
                    gerarPainelDestino(),
                    _EnviarButton
                }
            };
        }

        public void inicializarComponente(){
            _EnviarButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.End,
                Margin = new Thickness(8, 0),
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                //BackgroundColor = TemaUtils.BotaoPrincipalCor,
                //TextColor = TemaUtils.BotaoPrincipalFonteCor,
                Text = "Continuar"
            };
            _EnviarButton.Clicked += (sender, e) => {
                continuaPedido();
            };
        }

        public void continuaPedido(){
            if (validaPedido())
            {
                if(_ListaPontos != null){
                    _FreteInfo.Locais = new List<FreteLocalInfo>();
                    var ordemAux = 0;
                    foreach(var ponto in _ListaPontos){
                        ordemAux++;
                        if (ponto.Tipo != TipoPontoTransporte.Add)
                            _FreteInfo.Locais.Add(new FreteLocalInfo()
                            {
                                Tipo = getTipoValueByEnum(ponto.Tipo),
                                Latitude = ponto.Posicao.Value.Latitude,
                                Longitude = ponto.Posicao.Value.Longitude,
                                Ordem = ordemAux
                            });
                    }
                }
                Navigation.PushAsync(new ImagemProdutoPage(_FreteInfo));
            }
            else
            {
                DisplayAlert("Atenção", "Preencha pelo menos o local de saída e entrega do produto.", "Entendi");
            }
        }

        private bool validaPedido()
        {
            foreach (var ponto in _ListaPontos)
            {
                if (ponto.Tipo != TipoPontoTransporte.Add)
                    if (ponto.Posicao == null)
                        return false;
            }
            return true;
        }

        private FreteLocalTipoEnum getTipoValueByEnum(TipoPontoTransporte tipo){
            switch(tipo){
                case TipoPontoTransporte.Carga:
                    return FreteLocalTipoEnum.Saida;
                case TipoPontoTransporte.Destino:
                    return FreteLocalTipoEnum.Destino;
                case TipoPontoTransporte.Trecho:
                    return FreteLocalTipoEnum.Parada;
                default:
                    return FreteLocalTipoEnum.Saida;
            }
        }

        private ListView gerarPainelDestino()
        {
            var ret = new ListView();
            ret.HasUnevenRows = true;
            ret.RowHeight = -1;
            ret.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            ret.ItemTemplate = new DataTemplate(typeof(PointTransporteCell));
            ret.SeparatorVisibility = SeparatorVisibility.None;
            _ListaPontos = new List<PontoTransporte>()
            {
                new PontoTransporte(){ Text = "", Tipo = TipoPontoTransporte.Carga },
                new PontoTransporte(){ Text = "", Tipo = TipoPontoTransporte.Destino },
                new PontoTransporte(){ Text = "", Tipo = TipoPontoTransporte.Add }
            };
            ret.ItemsSource = _ListaPontos;
            ret.ItemTapped += (sender, e) =>
            {
                if (e == null)
                    return;
                var item = (PontoTransporte)((ListView)sender).SelectedItem;
                Navigation.PushAsync(new MapaPage(item.Text, ref item, ref _ListaPontos, ref ret));
            };

            return ret;
        }
    }
}


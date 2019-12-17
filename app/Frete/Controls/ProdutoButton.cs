using System;
using FormsPlugin.Iconize;
using EmagineFrete.Utils;
using Xamarin.Forms;
using Emagine.Base.Estilo;

namespace EmagineFrete.Controls
{
    public class ProdutoButton : ContentView
    {
        private StackLayout _Fundo;
        private IconImage _Icone;
        private Label _Texto;
        private bool _Marcado;
        private EventHandler _AoClicar;

        public EventHandler AoClicar { 
            get{
                return _AoClicar;
            } 
            set{
                _AoClicar = value;
                if (_AoClicar != null) {
                    var tapGestureRecognizer = new TapGestureRecognizer();
                    tapGestureRecognizer.Tapped += _AoClicar;
                    _Fundo.GestureRecognizers.Add(tapGestureRecognizer);
                    _Icone.GestureRecognizers.Add(tapGestureRecognizer);
                    _Texto.GestureRecognizers.Add(tapGestureRecognizer);
                }
            } 
        }

        public bool Marcado {
            get {
                return _Marcado;
            }
            set {
                _Marcado = value;
                if (_Marcado) {
                    _Fundo.BackgroundColor = Estilo.Current.PrimaryColor;
                    _Icone.IconColor = Color.White;
                    _Texto.TextColor = Color.White;
                }
                else {
                    _Fundo.BackgroundColor = Color.Transparent;
                    _Icone.IconColor = Estilo.Current.PrimaryColor;
                    _Texto.TextColor = Estilo.Current.PrimaryColor;
                }
            }
        }

        private void inicializarComponente(string icone, string texto) {
            _Icone = new IconImage
            {
                HorizontalOptions = LayoutOptions.Center,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(0, 5, 0, 0),
                Icon = icone,
                IconSize = 24,
                IconColor = Estilo.Current.BotaoPrincipal.TextColor
            };
            _Texto = new Label
            {
                HorizontalOptions = LayoutOptions.Center,
                VerticalOptions = LayoutOptions.Start,
                Text = texto,
                Margin = new Thickness(0, 0, 0, 5),
                FontSize = 12,
                TextColor = Estilo.Current.BotaoPrincipal.TextColor
            };
        }

        public ProdutoButton(string icone, string texto, bool marcado = false)
        {
            inicializarComponente(icone, texto);
            _Fundo = new StackLayout {
                WidthRequest = 70,
                HeightRequest = 50,
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                BackgroundColor = Estilo.Current.PrimaryColor,
                Padding = new Thickness(3, 5),
                Children = {
                    _Icone,
                    _Texto
                }
            };
            Content = _Fundo;
            Marcado = marcado;
        }
    }
}


using System;
using FormsPlugin.Iconize;
using EmagineFrete.Utils;
using Xamarin.Forms;
using Emagine.Base.Estilo;

namespace EmagineFrete.Controls
{
    public class MenuButton : ContentView
    {
        private StackLayout _Fundo;
        private IconImage _Icone;
        private Label _Texto;
        private EventHandler _AoClicar;

        public EventHandler AoClicar
        {
            get
            {
                return _AoClicar;
            }
            set
            {
                _AoClicar = value;
                if (_AoClicar != null)
                {
                    var tapGestureRecognizer = new TapGestureRecognizer();
                    tapGestureRecognizer.Tapped += _AoClicar;
                    _Fundo.GestureRecognizers.Add(tapGestureRecognizer);
                    _Icone.GestureRecognizers.Add(tapGestureRecognizer);
                    _Texto.GestureRecognizers.Add(tapGestureRecognizer);
                }
            }
        }

        private void inicializarComponente(string icone, string texto)
        {
            _Icone = new IconImage
            {
                HorizontalOptions = LayoutOptions.Center,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(0, 0, 10, 0),
                Icon = icone,
                IconSize = 20,
                IconColor = Estilo.Current.PrimaryColor
            };
            _Texto = new Label
            {
                HorizontalOptions = LayoutOptions.Center,
                VerticalOptions = LayoutOptions.Start,
                HorizontalTextAlignment = TextAlignment.Center,
                Text = texto,
                //Margin = new Thickness(0, 0, 0, 10),
                FontSize = 18,
                TextColor = Estilo.Current.PrimaryColor
            };
        }

        public MenuButton(string icone, string texto)
        {
            inicializarComponente(icone, texto);
            _Fundo = new StackLayout
            {
                Orientation = StackOrientation.Horizontal,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Padding = new Thickness(10, 20),
                Children = {
                    _Icone,
                    _Texto
                }
            };
            Content = _Fundo;
        }
    }
}


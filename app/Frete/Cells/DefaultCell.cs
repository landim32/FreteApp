using System;
using FormsPlugin.Iconize;
using EmagineFrete.Utils;
using Xamarin.Forms;
using Emagine.Base.Estilo;

namespace EmagineFrete.Cells
{
    public class DefaultCell : ViewCell
    {
        private Label _Text;

        public DefaultCell()
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
                                VerticalOptions = LayoutOptions.Start,
                                Icon = "fa-map-marker",
                                IconSize = 22,
                                WidthRequest = 30,
                                IconColor = Estilo.Current.PrimaryColor
                            },
                            _Text
                        }
                    }
                }
            };
        }


        private void inicializarComponente()
        {
            _Text = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.CenterAndExpand,
                FontSize = 20
            };
            _Text.SetBinding(Label.TextProperty, new Binding("Text"));
        }
    }
}

using System;
using Xamarin.Forms;

namespace Frete.Pages
{
    public class PoliticaDePrivacidade : ContentPage
    {
        public PoliticaDePrivacidade()
        {
            Title = "TERMOS DE ADESÃO";
            var wv = 
            Content = new WebView
            {
                VerticalOptions = LayoutOptions.FillAndExpand,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                Source = new UrlWebViewSource { Url = "file:///android_asset/Html/Contrato.html" }
            };
        }
    }
}

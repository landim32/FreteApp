using System;
using Xamarin.Forms;

namespace EmagineFrete.Pages
{
    public class PoliticaDePrivacidade : ContentPage
    {
        public PoliticaDePrivacidade(bool parceiro)
        {
            Title = "Termos e Condições";
            var wv = 
            Content = new WebView
            {
                VerticalOptions = LayoutOptions.FillAndExpand,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                Source = new UrlWebViewSource { Url = parceiro ? "file:///android_asset/Html/ContratoParceiro.html" : "file:///android_asset/Html/Contrato.html" }
            };
        }
    }
}

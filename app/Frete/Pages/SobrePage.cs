using System;
using System.Text;
using Xamarin.Forms;

namespace EmagineFrete.Pages
{
    public class SobrePage : ContentPage
    {
        private WebView _textoWebView;

        public SobrePage()
        {
            Title = "Sobre nós";
            inicializarComponente();
            Content = new ScrollView
            {
                Orientation = ScrollOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Fill,
                Content = new StackLayout()
                {
                    Padding = new Thickness(10, 20),
                    Spacing = 5,
                    VerticalOptions = LayoutOptions.FillAndExpand,
                    HorizontalOptions = LayoutOptions.FillAndExpand,
                    Orientation = StackOrientation.Vertical,
                    Children = {
                        new Image{
                            Source = "logo.png",
                            HeightRequest = 120,
                            VerticalOptions = LayoutOptions.Start,
                            HorizontalOptions = LayoutOptions.CenterAndExpand
                        },
                        _textoWebView
                    }
                }
            };
        }

        private void inicializarComponente()
        {
            _textoWebView = new WebView
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.FillAndExpand,
                Source = new HtmlWebViewSource
                {
                    Html = pegarTextoTermo()
                }
            };
        }

        private string pegarTextoTermo()
        {
            var sb = new StringBuilder();
            sb.AppendLine("<html>");
            sb.AppendLine("<head><style type=\"text/css\">");
            sb.AppendLine("body { font-size: 14px; }");
            sb.AppendLine("</style></head><body>");
            sb.AppendLine("<h3>Missão</h3>");
            sb.AppendLine("<p>Atender as necessidades de entregas e coletas em tempo real, integralizando logística e tecnologia, através de plataforma virtual.</p>");
            sb.AppendLine("<h3>Visão</h3>");
            sb.AppendLine("<p>Ser a maior provedora logistica, utilizando inovações tecnológicas para atender as diversas necessidades de mercado e emergências, tendo como foco rapidez, praticidade e segurança.</p>");
            sb.AppendLine("<h3>Valores</h3>");
            sb.AppendLine("<p>Compromisso, respeito e responsabilidade com nossos colaboradores e clientes.</p>");
            sb.AppendLine("</body></html>");
            return sb.ToString();
        }
    }
}


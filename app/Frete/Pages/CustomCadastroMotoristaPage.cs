using Emagine.Frete.Pages;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Frete.Pages
{
    public class CustomCadastroMotoristaPage: CadastroMotoristaPage
    {
        public CustomCadastroMotoristaPage() : base() {
            _VeiculoEntry.Placeholder = "Nome da Embarcação";
            _PlacaEntry.Placeholder = "Nº de Registro da Embarcação";
            _TipoVeiculoEntry.Placeholder = "Tipo de embarcação";
            _mainLayout.Children.Remove(_CarroceriaEntry);
            _mainLayout.Children.Remove(_ANTTEntry);
            _mainLayout.Children.Insert(0, new Label {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                FontSize = 12,
                Text = "Para trabalharmos juntos, precisamos saber um pouco mais sobre você."
            });
            _mainLayout.Children.Insert(1, new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                FontSize = 12,
                Text = "Preencha o formulário a seguir com seus dados e tenha em mãos seus " +
                "documentos de marinheiro e de embarcação para a prôxima etapa do cadastro."
            });
        }
    }
}

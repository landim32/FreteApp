using Emagine.Base.Utils;
using Emagine.Frete.Pages;
using Emagine.Login.Pages;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Frete.Pages
{
    public class CustomUsuarioFormPage: UsuarioFormPage
    {
        private bool _motorista = false;
        private Label _apresentacaoLabel;

        public bool Motorista {
            get {
                return _motorista;
            }
            set {
                _motorista = value;
                if (_motorista) {
                    _apresentacaoLabel.Text = textoMotorista();
                }
                else {
                    _apresentacaoLabel.Text = textoCliente();
                }
            }
        }

        private string textoCliente()
        {
            var texto = "";
            texto += "Para lhe atender, precisamos saber um pouco mais sobre você. Preencha o " + 
                "formulário a seguir com seus dados e tenha em mãos seus documentos.\n";
            return texto;
        }

        private string textoMotorista() {
            var texto = "";
            texto += "Para trabalharmos juntos, precisamos saber um pouco mais sobre você.\n";
            texto += "Preencha o formulário a seguir com seus dados e tenha em mãos seus " +
                "documentos de marinheiro e de embarcação para a prôxima etapa do cadastro.\n";
            return texto;
        }

        protected override void inicializarComponente()
        {
            base.inicializarComponente();
            _apresentacaoLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                FontSize = 12,
                Text = textoCliente()
            };
        }

        public CustomUsuarioFormPage() : base()
        {
            _mainStack.Children.Insert(0, _apresentacaoLabel);
        }
    }
}

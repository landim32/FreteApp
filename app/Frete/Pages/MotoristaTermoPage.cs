using Emagine.Base.Estilo;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Frete.Pages
{
    public class MotoristaTermoPage : ContentPage
    {
        private WebView _textoWebView;
        private Button _cadastroButton;

        public event EventHandler AoCadastrar;

        public MotoristaTermoPage()
        {
            Title = "Condições de Cadastro";
            inicializarComponente();
            Content = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Fill,
                Padding = 5,
                Children = {
                    _textoWebView,
                    _cadastroButton
                }
            };
        }

        private void inicializarComponente() {
            _textoWebView = new WebView {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.FillAndExpand,
                Source = new HtmlWebViewSource {
                    Html = pegarTextoTermo()
                }
            };

            _cadastroButton = new Button()
            {
                Text = "ENVIAR",
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.End,
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL]
            };
            _cadastroButton.Clicked += CadastroClicked;
        }

        private string pegarTextoTermo() {
            var texto = "";
            texto += "<html>";
            texto += "<head><style type=\"text/css\">\n";
            texto += "body { font-size: 12px; }\n";
            texto += "</style></head><body>\n";
            texto += "<p>Somos muito rigorosos no processo de cadastro de novos marinheiros. " + 
                "Por favor, leia a lista a seguir e nos envie os documentos digitalizados para o endereço: " + 
                "cadastromarinheiro@easybarcos.com.br.</p>\n";
            texto += "<ol>\n";
            texto += "<li>Para embarcações de construção artesanal deverá ser apresentada a <strong>declaração de " + 
                "construção artesanal</strong>, com os dados responsável técnico pela construção, com firmas reconhecidas " + 
                "e ART devidamente paga. </li>\n";
            texto += "<li>";
            texto += "Para embarcações que não possuam Nota Fiscal e tenham mais de 5 anos deverão apresentar:\n";
            texto += "<ul>\n";
            texto += "<li><strong>Declaração de propriedade</strong> da embarcação registrada em Cartório de Títulos e " + 
                "Documentos, onde esteja qualificado o Declarante e perfeitamente caracterizada a embarcação, e seu motor, " + 
                "caso este exista. Essa declaração não será aceita para inscrição de moto aquática;</li>\n";
            texto += "</ul>\n";
            texto += "</li>\n";
            texto += "<li>Cópia autenticada legível da Carteira de Identidade e do CPF (pessoa física) ou CNPJ(cópia do " + 
                "Contrato Social, caso pessoa jurídica) <strong>(dispensada a autenticação, se for apresentado o original junto com a " + 
                "cópia)</strong>;</li>\n";
            texto += "<li>Cópia autenticada do Comprovante de Residência - água, luz ou telefone. Caso esteja em nome de " + 
                "terceiros ou não possua, deverá ser apresentada <strong>(declaração de residência)</strong>;</li>\n";
            texto += "<li>Seguro Obrigatório DPEM Atualizado - <strong>(original e fotocópia legível e autenticada)</strong>;</li>\n";
            texto += "<li>Foto colorida da embarcação no tamanho 15 x 21 cm, datada, mostrando-a pelo través, de forma que " + 
                "apareça total e claramente de proa a popa, preenchendo o comprimento da foto;</li>\n";
            texto += "<li>Registro na ANTAQ.</li>\n";
            texto += "</ol>\n";
            texto += "</body></html>";
            return texto;
        }

        protected virtual void CadastroClicked(object sender, EventArgs e)
        {
            AoCadastrar?.Invoke(this, e);
        }
    }
}
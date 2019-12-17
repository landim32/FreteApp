using System;
using System.Collections.Generic;
using Acr.UserDialogs;
using Emagine.Base.BLL;
using Emagine.Base.Estilo;
using Emagine.Base.Model;
using Emagine.Login.BLL;
using Emagine.Login.Factory;
using Xamarin.Forms;

namespace Frete.Pages
{
    public class FormMailNvoid : ContentPage
    {
        private Entry _NomeEntry;
        private Entry _TelefoneEntry;
        private Entry _EmailEntry;
        private DatePicker _DataInicialPicker;
        private DatePicker _DataFinalPicker;
        private Picker _TipoPessoa;
        private Entry _DescricaoEntry;
        private Button _EnviarButton;
        private Label _ObservacaoLbl;

        public FormMailNvoid()
        {
            Title = "#Exclusive";
            inicializarComponente();
            Content = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Padding = 5,
                Children = {
                    new Label {
                        VerticalOptions = LayoutOptions.Start,
                        HorizontalOptions = LayoutOptions.Fill,
                        Text = "nVoid disponibiliza o serviço #Exclusive para atender a necessidade da urgência em serviço dedicado nas demandas de transporte"  
                    },
                    _NomeEntry,
                    _TelefoneEntry,
                    _EmailEntry,
                    _DataInicialPicker,
                    new StackLayout{
                        Orientation = StackOrientation.Horizontal,
                        VerticalOptions = LayoutOptions.Start,
                        HorizontalOptions = LayoutOptions.Fill,
                        Children = {
                            new Label(){
                                HorizontalOptions = LayoutOptions.Start,
                                VerticalOptions = LayoutOptions.Center,
                                Text = "Data incial do frete"
                            },
                            _DataInicialPicker
                        }
                    },
                    new StackLayout{
                        Orientation = StackOrientation.Horizontal,
                        VerticalOptions = LayoutOptions.Start,
                        HorizontalOptions = LayoutOptions.Fill,
                        Children = {
                            new Label(){
                                HorizontalOptions = LayoutOptions.Start,
                                VerticalOptions = LayoutOptions.Center,
                                Text = "Data final do frete"
                            },
                            _DataFinalPicker
                        }
                    },
                    _TipoPessoa,
                    _DescricaoEntry,
                    _EnviarButton,
                    _ObservacaoLbl
                }
            };
        }
        private bool validarCampos(){
            if (_TelefoneEntry != null && string.IsNullOrEmpty(_TelefoneEntry.Text))
            {
                return false;
            }
            if (_EmailEntry != null && string.IsNullOrEmpty(_EmailEntry.Text))
            {
                return false;
            }
            if (_DescricaoEntry != null && string.IsNullOrEmpty(_DescricaoEntry.Text))
            {
                return false;
            }
            if(_TipoPessoa.SelectedItem == null)
            {
                return false;
            }
            return true;
        }
        private void inicializarComponente()
        {
            var regraUsuario = UsuarioFactory.create();
            var usuario = regraUsuario.pegarAtual();

            _NomeEntry = new Entry
            {
                Placeholder = "Nome",
                Keyboard = Keyboard.Telephone,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Text = usuario.Nome
            };
            _TelefoneEntry = new Entry
            {
                Placeholder = "Telefone",
                Keyboard = Keyboard.Telephone,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill
            };
            _EmailEntry = new Entry
            {
                Placeholder = "Email",
                Keyboard = Keyboard.Email,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill
            };
            _DescricaoEntry = new Entry
            {
                Placeholder = "Descrição",
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill
            };
            _DataInicialPicker = new DatePicker
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                
            };
            _DataFinalPicker = new DatePicker
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.FillAndExpand
            };
            _TipoPessoa = new Picker
            {
                Title = "Selecione o tipo de pessoa (PF ou PJ)",
                ItemsSource = new List<string>(){
                    "Pessoa Física",
                    "Pessoa Jurídica"
                }
            };
            _ObservacaoLbl = new Label
            {
                Text = "Observação: #exclusive apresenta taxas diferenciadas pelo tipo de serviço solicitado.",
                FontSize = 9,
                TextColor = Color.DarkGray,
                Margin = 10
            };
            _EnviarButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                Text = "Enviar Pedido"
            };
            _EnviarButton.Clicked += async (sender, e) =>
            {
                if(validarCampos()){
                    UserDialogs.Instance.ShowLoading("Enviando...");
                    //var user = new UsuarioBLL().pegarAtual();
                    var msg = new MensagemInfo()
                    {
                        Assunto = "Frete especial",
                        Mensagem = "Nome: " + _NomeEntry.Text + "<BR>" +
                                                  "Telefone: " + _TelefoneEntry.Text + "<BR>" +
                                                  "Email: " + _EmailEntry.Text + "<BR>" +
                                                  "Data inicial do frete: " + _DataInicialPicker.Date.ToString("dd/MM/yyyy") + "<BR>" +
                                                  "Data final do frete: " + _DataFinalPicker.Date.ToString("dd/MM/yyyy") + "<BR>" +
                                                  "Tipo de pessoa: " + (string)_TipoPessoa.SelectedItem + "<BR>" +
                                                  "Descricao: " + _DescricaoEntry.Text + "<BR>"

                    };
                    if ((await new MensagemBLL().enviar(msg)))
                    {
                        UserDialogs.Instance.HideLoading();
                        DisplayAlert("Sucesso", "Pedido enviado com sucesso.", "Ok");
                    }
                    else
                    {
                        UserDialogs.Instance.HideLoading();
                        DisplayAlert("Falha", "Ocorreu um erro ao tentar enviar o pedido.", "Ok");
                    }   
                } else {
                    DisplayAlert("Atenção", "Preenha todos os campos.", "Entendi");
                }
            };
           
        }
    }
}


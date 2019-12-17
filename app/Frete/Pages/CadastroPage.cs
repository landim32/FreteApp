using System;
using System.Collections.Generic;
using System.Threading.Tasks;
using Acr.UserDialogs;
using Emagine;
using Emagine.Base.Estilo;
using Emagine.Base.Pages;
using Emagine.Frete.Factory;
using Emagine.Frete.Model;
using Emagine.Login.Factory;
using Emagine.Login.Model;
using EmagineFrete.Controls;
using EmagineFrete.Model;
using EmagineFrete.Utils;
using Plugin.Media;
using Xamarin.Forms;
using Xfx;

namespace EmagineFrete.Pages
{
    public class CadastroPage : ContentPage
    {
        public CadastroTipoEnum Tipo { get; set; }
        private bool _inicializado = false;
        private IList<TipoVeiculoInfo> _tiposVeiculo;

        private XfxEntry _NomeEntry;
        private XfxEntry _SobrenomeEntry;
        private XfxEntry _CelularEntry;
        private XfxEntry _EmailEntry;
        private XfxEntry _SenhaEntry;
        private XfxEntry _ConfirmaEntry;
        private XfxEntry _PlacaEntry;
        private XfxEntry _VeiculoEntry;
        private Picker _TipoSwitch;
        private Picker _TipoPessoa;
        private FotoImageButton _FotoCNHButton;
        private FotoImageButton _FotoResidenciaButton;
        private FotoImageButton _FotoVeiculoButton;
        private FotoImageButton _FotoCPFButton;
        private Button _CadastroButton;
        private Button _Privacidade;
        private Switch _Aceito;

        public async Task<TipoVeiculoInfo> pegarTipoVeiculoSelecionado() {
            if (_TipoSwitch.SelectedIndex <= 0) {
                await DisplayAlert("Aviso", "Selecione o tipo de veículo.", "Entendi");
                _TipoSwitch.Focus();
                return null;
            }
            return _tiposVeiculo[_TipoSwitch.SelectedIndex - 1];
        }

        public CadastroPage(CadastroTipoEnum tipo)
        {
            Tipo = tipo;
            Title = "Cadastro";

            inicializarComponente();

            var mainStack = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Padding = 5,
                Children = {
                    new Image{
                        Source = "logo.png",
                        HeightRequest = 80,
                        VerticalOptions = LayoutOptions.Start,
                        HorizontalOptions = LayoutOptions.Center
                    }/*,
                    _NomeEntry,
                    _SobrenomeEntry,
                    _CelularEntry,
                    _EmailEntry,
                    */
                }
            };
            if (_NomeEntry != null) 
            {
                mainStack.Children.Add(_NomeEntry);
            }
            if (_SobrenomeEntry != null)
            {
                mainStack.Children.Add(_SobrenomeEntry);
            }
            if (_CelularEntry != null)
            {
                mainStack.Children.Add(_CelularEntry);
            }
            if (_EmailEntry != null)
            {
                mainStack.Children.Add(_EmailEntry);
            }
            if (_TipoSwitch != null)
            {
                mainStack.Children.Add(_TipoSwitch);
            }
            if (_VeiculoEntry != null)
            {
                mainStack.Children.Add(_VeiculoEntry);
            }
            if (_PlacaEntry != null)
            {
                mainStack.Children.Add(_PlacaEntry);
            }
            if (_TipoPessoa != null)
            {
                mainStack.Children.Add(_TipoPessoa);
            }

            if (Tipo == CadastroTipoEnum.Fornecedor || Tipo == CadastroTipoEnum.ApenasFornecedor) {

                var gridLayout = new Grid
                {
                    HorizontalOptions = LayoutOptions.Fill,
                    VerticalOptions = LayoutOptions.Start
                };
                gridLayout.Children.Add(_FotoCNHButton, 0, 0);
                gridLayout.Children.Add(_FotoResidenciaButton, 1, 0);
                gridLayout.Children.Add(_FotoVeiculoButton, 0, 1);
                gridLayout.Children.Add(_FotoCPFButton, 1, 1);
                gridLayout.RowDefinitions.Add(new RowDefinition {
                    Height = new GridLength(140)
                });
                gridLayout.RowDefinitions.Add(new RowDefinition
                {
                    Height = new GridLength(140)
                });
                mainStack.Children.Add(gridLayout);
            }

            /*
            mainStack.Children.Add(new Label
            {
                Text = "Digite uma senha mínimo 6 dígitos",
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start
            });
            */
            if (_SenhaEntry != null)
            {
                mainStack.Children.Add(_SenhaEntry);
            }
            /*
            mainStack.Children.Add(new Label
            {
                Text = "Repita",
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start
            });
            */
            if (_ConfirmaEntry != null)
            {
                mainStack.Children.Add(_ConfirmaEntry);
            }
            if (_Aceito != null)
            {
                mainStack.Children.Add(new StackLayout
                {
                    Orientation = StackOrientation.Horizontal,
                    HorizontalOptions = LayoutOptions.Fill,
                    Spacing = 5,
                    Children = {
                        new Label {
                            Text = "Li e concordo os termos e condições"
                        },
                        _Aceito
                    }
                });
                mainStack.Children.Add(_Privacidade);
            }

            mainStack.Children.Add(_CadastroButton);


            Content = new ScrollView{
                Orientation = ScrollOrientation.Vertical,
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Content = mainStack
            };

            /*
            _NomeEntry.Text = "Marcos";
            _SobrenomeEntry.Text = "Paulo";
            _CelularEntry.Text = "062996257590";
            _SenhaEntry.Text = "pikpro6";
            _ConfirmaEntry.Text = "pikpro6";
            _EmailEntry.Text = "marcos.thx1138@gmail.com";
            */
        }

        private void inicializarComponente() {
            if (Tipo == CadastroTipoEnum.Fornecedor || Tipo == CadastroTipoEnum.Usuario)
            {
                _NomeEntry = new XfxEntry
                {
                    Placeholder = "Nome",
                    VerticalOptions = LayoutOptions.Start,
                    HorizontalOptions = LayoutOptions.Fill,
                    ErrorDisplay = ErrorDisplay.None
                };
                _SobrenomeEntry = new XfxEntry
                {
                    Placeholder = "Sobrenome",
                    VerticalOptions = LayoutOptions.Start,
                    HorizontalOptions = LayoutOptions.Fill,
                    ErrorDisplay = ErrorDisplay.None
                };
                _EmailEntry = new XfxEntry
                {
                    Placeholder = "Email",
                    Keyboard = Keyboard.Email,
                    VerticalOptions = LayoutOptions.Start,
                    HorizontalOptions = LayoutOptions.Fill,
                    ErrorDisplay = ErrorDisplay.None
                };
                _CelularEntry = new XfxEntry
                {
                    Placeholder = "Celular",
                    Keyboard = Keyboard.Telephone,
                    VerticalOptions = LayoutOptions.Start,
                    HorizontalOptions = LayoutOptions.Fill,
                    ErrorDisplay = ErrorDisplay.None
                };
                _Aceito = new Switch
                {
                    IsToggled = false
                };
            }
            if (Tipo == CadastroTipoEnum.Fornecedor || Tipo == CadastroTipoEnum.ApenasFornecedor) {

                _TipoSwitch = new Picker { 
                    VerticalOptions = LayoutOptions.Start,
                    HorizontalOptions = LayoutOptions.Fill
                    /*
                    ItemsSource = new List<string>() {
                        "--Tipo de Veículo--",
                        "Moto",
                        "Carro",
                        "Caminhenete",
                        "Caminhão"
                    },
                    SelectedIndex = 0
                    */
                };
                _PlacaEntry = new XfxEntry { 
                    Placeholder = "Placa do seu Veículo",
                    Keyboard = Keyboard.Default,
                    VerticalOptions = LayoutOptions.Start,
                    HorizontalOptions = LayoutOptions.Fill,
                    ErrorDisplay = ErrorDisplay.None
                };
                _VeiculoEntry = new XfxEntry
                {
                    Placeholder = "Descrição do Veículo",
                    Keyboard = Keyboard.Default,
                    VerticalOptions = LayoutOptions.Start,
                    HorizontalOptions = LayoutOptions.Fill,
                    ErrorDisplay = ErrorDisplay.None
                };

                _FotoCNHButton = new FotoImageButton() {
                    HorizontalOptions = LayoutOptions.Fill,
                    VerticalOptions = LayoutOptions.Start,
                    Source = "FotoCNH.png"
                };

                _FotoResidenciaButton = new FotoImageButton()
                {
                    HorizontalOptions = LayoutOptions.Fill,
                    VerticalOptions = LayoutOptions.Start,
                    Source = "FotoResidencia.png"
                };

                _FotoVeiculoButton = new FotoImageButton()
                {
                    HorizontalOptions = LayoutOptions.Fill,
                    VerticalOptions = LayoutOptions.Start,
                    Source = "FotoVeiculo.png"
                };

                _FotoCPFButton = new FotoImageButton()
                {
                    HorizontalOptions = LayoutOptions.Fill,
                    VerticalOptions = LayoutOptions.Start,
                    Source = "FotoCPF.png"
                };

                _TipoPessoa = new Picker
                {
                    Title = "Selecione o tipo de pessoa (PF ou PJ)",
                    ItemsSource = new List<string>(){
                        "Pessoa Física",
                        "Pessoa Jurídica"
                    }
                };
            }

            if (Tipo == CadastroTipoEnum.Fornecedor || Tipo == CadastroTipoEnum.Usuario)
            {
                _SenhaEntry = new XfxEntry
                {
                    Placeholder = "Preencha sua senha",
                    IsPassword = true,
                    VerticalOptions = LayoutOptions.Start,
                    HorizontalOptions = LayoutOptions.Fill,
                    ErrorDisplay = ErrorDisplay.None
                };
                _ConfirmaEntry = new XfxEntry
                {
                    Placeholder = "Confirme sua senha",
                    IsPassword = true,
                    VerticalOptions = LayoutOptions.Start,
                    HorizontalOptions = LayoutOptions.Fill,
                    ErrorDisplay = ErrorDisplay.None
                };
            }

            _CadastroButton = new Button()
            {
                Text = "CADASTRAR",
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL]
            };
            _Privacidade = new Button()
            {
                Text = "TERMOS E CONDIÇÕES",
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL]
            };
            _Privacidade.Clicked += (sender, e) => {
                Navigation.PushAsync(new PoliticaDePrivacidade(Tipo == CadastroTipoEnum.Usuario ? false : true));
            };
            _CadastroButton.Clicked += async (sender, e) => {

                if (_NomeEntry != null && string.IsNullOrEmpty(_NomeEntry.Text))
                {
                    await DisplayAlert("Aviso", "Preencha o seu nome.", "Fechar");
                    return;
                }
                if (_SobrenomeEntry != null && string.IsNullOrEmpty(_SobrenomeEntry.Text))
                {
                    await DisplayAlert("Aviso", "Preencha o seu sobrenome.", "Fechar");
                    return;
                }
                if (_CelularEntry != null &&  string.IsNullOrEmpty(_CelularEntry.Text))
                {
                    await DisplayAlert("Aviso", "Preencha o seu celular.", "Fechar");
                    return;
                }
                if (_EmailEntry != null && string.IsNullOrEmpty(_EmailEntry.Text))
                {
                    await DisplayAlert("Aviso", "Preencha o seu email.", "Fechar");
                    return;
                }
                if (_SenhaEntry != null && string.IsNullOrEmpty(_SenhaEntry.Text))
                {
                    await DisplayAlert("Aviso", "Preencha a senha.", "Fechar");
                    return;
                }
                if (_ConfirmaEntry != null && string.IsNullOrEmpty(_ConfirmaEntry.Text))
                {
                    await DisplayAlert("Aviso", "Preencha a confirmação de senha.", "Fechar");
                    return;
                }
                if (_SenhaEntry != null && _ConfirmaEntry != null && string.Compare(_SenhaEntry.Text, _ConfirmaEntry.Text) != 0)
                {
                    await DisplayAlert("Aviso", "A senha não está batendo com a confirmação.", "Fechar");
                    return;
                }
                if (_TipoSwitch != null && _TipoSwitch.SelectedIndex <= 0)
                {
                    await DisplayAlert("Aviso", "Selecione o tipo do veículo.", "Fechar");
                    return;
                }
                if (_TipoPessoa != null && _TipoPessoa.SelectedIndex < 0)
                {
                    await DisplayAlert("Aviso", "Selecione o tipo do pessoa.", "Fechar");
                    return;
                }
                if (_VeiculoEntry != null && string.IsNullOrEmpty(_VeiculoEntry.Text))
                {
                    await DisplayAlert("Aviso", "Preencha a descrição do veículo.", "Fechar");
                    return;
                }
                if (_PlacaEntry != null && string.IsNullOrEmpty(_PlacaEntry.Text))
                {
                    await DisplayAlert("Aviso", "Preencha a placa do veículo.", "Fechar");
                    return;
                }
                if(_Aceito != null && !_Aceito.IsToggled){
                    await DisplayAlert("Aviso", "É preciso aceitar os termos e condições", "Fechar");
                    return;
                }

                var regraUsuario = UsuarioFactory.create();
                if (Tipo == CadastroTipoEnum.Usuario || Tipo == CadastroTipoEnum.Fornecedor)
                {
                    var usuario = new UsuarioInfo
                    {
                        Nome = _NomeEntry.Text + " " + _SobrenomeEntry.Text,
                        Email = _EmailEntry.Text,
                        Telefone = _CelularEntry.Text,
                        Senha = _SenhaEntry.Text,
                        Situacao = SituacaoEnum.Ativo
                    };
                    if(_TipoPessoa != null){
                        usuario.Preferencias.Add(new UsuarioPreferenciaInfo
                        {
                            Chave = "TipoPessoa",
                            Valor = _TipoPessoa.SelectedIndex.ToString()
                        });
                    }
                    UserDialogs.Instance.ShowLoading("Enviando...");
                    try {
                        var id_usuario = await regraUsuario.inserir(usuario);
                        var usuarioCadastrado = await regraUsuario.pegar(id_usuario);
                        /*
                        if (usuarioCadastrado != null) {
                            var motorista = new MotoristaInfo { 
                                Id = usuarioCadastrado.Id,
                                //FotoCpfBase64
                            };
                        }
                        */
                            
                        regraUsuario.gravarAtual(usuarioCadastrado);
                        UserDialogs.Instance.HideLoading();
                        if (usuarioCadastrado != null)
                        {
                            regraUsuario.gravarAtual(usuarioCadastrado);
                            if (Tipo == CadastroTipoEnum.Usuario){
                                App.Current.MainPage = App.gerarRootPage(new PrincipalPage());
                                /*
                                var mn = new Pages.Menu();
                                mn.setMenuUsuario();
                                RootPage.init(mn);
                                App.Current.MainPage = RootPage.root;    
                                */
                            }

                        }
                        else
                        {
                            string mensagem = string.Format("Nenhum usuário encontrado com o ID {0}.", id_usuario);
                            await DisplayAlert("Aviso", mensagem, "Fechar");
                        }
                    }
                    catch (Exception erro)
                    {
                        UserDialogs.Instance.HideLoading();
                        await UserDialogs.Instance.AlertAsync(erro.Message, "Erro", "Entendi");
                        //UserDialogs.Instance.ShowError(erro.Message, 8000);
                    }
                }
                if (Tipo == CadastroTipoEnum.ApenasFornecedor || Tipo == CadastroTipoEnum.Fornecedor)
                {
                    UserDialogs.Instance.ShowLoading("Enviando...");
                    try
                    {
                        var usuario = regraUsuario.pegarAtual();
                        if (usuario == null)
                        { 
                            throw new Exception("Você não está logado com seu usuário. Não pode se tornar um parceiro.");
                        }
                        /*
                        var tipo = TipoVeiculoEnum.Carro;
                        switch (_TipoSwitch.SelectedIndex) {
                            case 1:
                                tipo = TipoVeiculoEnum.Moto;
                                break;
                            case 2:
                                tipo = TipoVeiculoEnum.Carro;
                                break;
                            case 3:
                                tipo = TipoVeiculoEnum.Caminhonete;
                                break;
                            case 4:
                                tipo = TipoVeiculoEnum.Caminhao;
                                break;
                        }
                        */
                        var tipo = await pegarTipoVeiculoSelecionado();
                        if (tipo == null) {
                            UserDialogs.Instance.HideLoading();
                            return;
                        }
                        var motorista = new MotoristaInfo
                        {
                            Id = usuario.Id,
                            IdTipo = tipo.Id,
                            //Tipo = tipo,
                            Placa = _PlacaEntry.Text,
                            Veiculo = _VeiculoEntry.Text,
                            FotoCpfBase64 = _FotoCPFButton.getBase64(),
                            FotoCarteiraBase64 = _FotoCNHButton.getBase64(),
                            FotoVeiculoBase64 = _FotoVeiculoButton.getBase64(),
                            FotoEnderecoBase64 = _FotoResidenciaButton.getBase64(),
                            Situacao = MotoristaSituacaoEnum.AguardandoAprovacao
                        };
                        var regraMotorista = MotoristaFactory.create();
                        await regraMotorista.inserir(motorista);
                        motorista = await regraMotorista.pegar(usuario.Id);
                        regraMotorista.gravarAtual(motorista);

                        UserDialogs.Instance.HideLoading();
                        if (motorista.Situacao == MotoristaSituacaoEnum.Ativo)
                        {
                            UserDialogs.Instance.Alert("Cadastro aprovado com sucesso.");
                        }
                        if (motorista.Situacao == MotoristaSituacaoEnum.AguardandoAprovacao) {
                            UserDialogs.Instance.Alert("Cadastro enviado. Aguarde a aprovação.");
                        }
                        //((RootPage)App.Current.MainPage).atualizarMenu();
                        App.Current.MainPage = App.gerarRootPage(new PrincipalPage());
                    }
                    catch (Exception erro)
                    {
                        UserDialogs.Instance.HideLoading();
                        await UserDialogs.Instance.AlertAsync(erro.Message, "Erro", "Entendi");
                    }
                }
            };

        }

        protected override async void OnAppearing()
        {
            base.OnAppearing();
            if (Tipo == CadastroTipoEnum.Fornecedor && !_inicializado)
            {
                UserDialogs.Instance.ShowLoading("Enviando...");
                try
                {
                    var regraTipoVeiculo = TipoVeiculoFactory.create();
                    _tiposVeiculo = await regraTipoVeiculo.listar();
                    _TipoSwitch.Items.Clear();
                    _TipoSwitch.Items.Add("--Tipo de Veículo--");
                    foreach (var tipo in _tiposVeiculo)
                    {
                        _TipoSwitch.Items.Add(tipo.Nome);
                    }
                    _TipoSwitch.SelectedIndex = 0;
                    UserDialogs.Instance.HideLoading();
                }
                catch (Exception erro)
                {
                    UserDialogs.Instance.HideLoading();
                    await UserDialogs.Instance.AlertAsync(erro.Message, "Erro", "Entendi");
                }
                _inicializado = true;
            }
        }
    }
}

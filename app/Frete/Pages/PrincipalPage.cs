using System;
using Acr.UserDialogs;
using Emagine.Base.Controls;
using Emagine.Base.Estilo;
using Emagine.Base.Model;
using Emagine.Base.Utils;
using Emagine.Frete.BLL;
using Emagine.Frete.Model;
using Emagine.Login.BLL;
using EmagineFrete.Controls;
using EmagineFrete.Model;
using EmagineFrete.Utils;
using Xamarin.Forms;

namespace EmagineFrete.Pages
{
    public class PrincipalPage : ContentPage
    {
        //private Image _EnviarProdutoButton;
        private PrincipalButton _EnviarProdutoButton;
        private PrincipalButton _RastrearMercadoriaButton;
        private PrincipalButton _FaleConoscoButton;
        private PrincipalButton _MeuPedidoButton;
        private PrincipalButton _SobreAplicativoButton;
        private PrincipalButton _ConfiguracaoButton;
        private Button _MotoristaButton;
        private Label _SituacaoLabel;
        private Grid _mainLayout;
        private bool _motoristaVerificado = false;

        public PrincipalPage()
        {
            var usuario = new UsuarioBLL().pegarAtual();
            Title = "Olá " + usuario.Nome;

            inicializarComponente();

            _mainLayout = new Grid{
                Margin = new Thickness(10, 10),
                RowSpacing = 10,
                ColumnSpacing = 10
            };
            //adicionarBotaoMotorista();

            Content = _mainLayout;
        }

        protected override async void OnAppearing()
        {
            base.OnAppearing();
            PermissaoUtils.pedirPermissao();
            if (!_motoristaVerificado)
            {
                var usuario = new UsuarioBLL().pegarAtual();
                var motorista = new MotoristaBLL().pegarAtual();
                if (motorista == null)
                {
                    try
                    {
                        UserDialogs.Instance.ShowLoading("carregando...");
                        motorista = await new MotoristaBLL().pegar(usuario.Id);
                        if (motorista != null)
                        {
                            //var mn = new Menu();
                            //mn.setMenuMotorista();
                            //RootPage.root.Master = (ContentPage)mn;
                            new MotoristaBLL().gravarAtual(motorista);
                            if (motorista.Situacao != MotoristaSituacaoEnum.Ativo) {
                                adicionarSituacao(motorista.Situacao);
                            }
                        }
                        else
                        {
                            //var mn = new Menu();
                            //mn.setMenuUsuario();
                            //RootPage.root.Master = (ContentPage)mn;
                            _mainLayout.RowDefinitions.Add(new RowDefinition { Height = new GridLength(1, GridUnitType.Star) });
                            _mainLayout.RowDefinitions.Add(new RowDefinition { Height = new GridLength(1, GridUnitType.Star) });
                            _mainLayout.RowDefinitions.Add(new RowDefinition { Height = new GridLength(1, GridUnitType.Star) });
                            _mainLayout.ColumnDefinitions.Add(new ColumnDefinition { Width = new GridLength(1, GridUnitType.Star) });
                            _mainLayout.ColumnDefinitions.Add(new ColumnDefinition { Width = new GridLength(1, GridUnitType.Star) });

                            _mainLayout.Children.Add(_EnviarProdutoButton, 0, 0);
                            _mainLayout.Children.Add(_RastrearMercadoriaButton, 1, 0);
                            _mainLayout.Children.Add(_FaleConoscoButton, 0, 1);
                            _mainLayout.Children.Add(_MeuPedidoButton, 1, 1);
                            _mainLayout.Children.Add(_SobreAplicativoButton, 0, 2);
                            _mainLayout.Children.Add(_ConfiguracaoButton, 1, 2);
                            adicionarBotaoMotorista();
                        }
                        UserDialogs.Instance.HideLoading();
                    }
                    catch (Exception e)
                    {
                        UserDialogs.Instance.HideLoading();
                        UserDialogs.Instance.ShowError(e.Message, 8000);
                    }
                }
                _motoristaVerificado = true;
            }
        }

        private void inicializarComponente() {
            if (GlobalUtils.getAplicacaoAtual() == AplicacaoEnum.APLICACAO01)
                _EnviarProdutoButton = new PrincipalButton(ImageSource.FromFile("btnEnviarProduto.png"));
            else
                _EnviarProdutoButton = new PrincipalButton("fa-car", "Enviar\nProduto");
            _EnviarProdutoButton.AoClicar += (sender, e) => {
                Navigation.PushAsync(new ProdutoPage() {
                    Title = "Enviar Produto"
                });
            };

            if (GlobalUtils.getAplicacaoAtual() == AplicacaoEnum.APLICACAO01)
                _RastrearMercadoriaButton = new PrincipalButton(ImageSource.FromFile("btnRastrear.png"));
            else
                _RastrearMercadoriaButton = new PrincipalButton("fa-search", "Rastrear\nMercadoria");
            _RastrearMercadoriaButton.AoClicar += (sender, e) => {
                Navigation.PushAsync(new FreteListaPage(false) {
                    Title = "Rastrear Mercadoria"
                });
            };

            if (GlobalUtils.getAplicacaoAtual() == AplicacaoEnum.APLICACAO01)
                _FaleConoscoButton = new PrincipalButton(ImageSource.FromFile("btnFaleConosco.png"));
            else
                _FaleConoscoButton = new PrincipalButton("fa-envelope", "Fale\nConosco");
            _FaleConoscoButton.AoClicar += (sender, e) => {
                Device.OpenUri(new Uri("mailto:rodrigo@emagine.com.br"));
            };

            if (GlobalUtils.getAplicacaoAtual() == AplicacaoEnum.APLICACAO01)
                _MeuPedidoButton = new PrincipalButton(ImageSource.FromFile("btnMeuPedido.png"));
            else
                _MeuPedidoButton = new PrincipalButton("fa-gift", "Meus\nPedidos");
            _MeuPedidoButton.AoClicar += (sender, e) => {
                Navigation.PushAsync(new FreteListaPage(true) {
                    Title = "Meus Pedidos"
                });
            };

            if (GlobalUtils.getAplicacaoAtual() == AplicacaoEnum.APLICACAO01)
                _SobreAplicativoButton = new PrincipalButton(ImageSource.FromFile("btnSobre.png"));
            else
                _SobreAplicativoButton = new PrincipalButton("fa-exclamation-circle", "Sobre o\nAplicativo");
            _SobreAplicativoButton.AoClicar += (sender, e) => {
                Navigation.PushAsync(new SobrePage() {
                    Title = "Sobre o Aplicativo"
                });
            };

            if (GlobalUtils.getAplicacaoAtual() == AplicacaoEnum.APLICACAO01)
                _ConfiguracaoButton = new PrincipalButton(ImageSource.FromFile("btnConfiguracao.png"));
            else
                _ConfiguracaoButton = new PrincipalButton("fa-cogs", "Minhas\nConfigurações");
            _ConfiguracaoButton.AoClicar += (sender, e) => {
                //Navigation.PushAsync(new ConfiguracaoPage());
            };   
        }

        private void adicionarBotaoMotorista() {
            _MotoristaButton = new Button
            {
                Text = "Seja um parceiro!",
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                HeightRequest = 50
            };
            _MotoristaButton.Clicked += (sender, e) => {
                Navigation.PushAsync(new CadastroPage(CadastroTipoEnum.ApenasFornecedor));
            };

            _mainLayout.RowDefinitions.Add(new RowDefinition { Height = new GridLength(50, GridUnitType.Absolute) });
            _mainLayout.Children.Add(_MotoristaButton, 0, 3);
            Grid.SetColumnSpan(_MotoristaButton, 2);
        }

        private void adicionarSituacao(MotoristaSituacaoEnum situacao)
        {
            _SituacaoLabel = new Label
            {
                Text = "",
                HorizontalTextAlignment = TextAlignment.Center,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                HeightRequest = 30
            };
            switch (situacao) {
                case MotoristaSituacaoEnum.AguardandoAprovacao:
                    _SituacaoLabel.Text = "Aguardando aprovação!";
                    _SituacaoLabel.BackgroundColor = Color.Yellow;
                    break;
                case MotoristaSituacaoEnum.Reprovado:
                    _SituacaoLabel.Text = "Cadastro reprovado!";
                    _SituacaoLabel.BackgroundColor = Color.Red;
                    break;
                default:
                    _SituacaoLabel.Text = "Cadastro aprovado!";
                    _SituacaoLabel.BackgroundColor = Color.Green;
                    break;
            }

            _mainLayout.RowDefinitions.Add(new RowDefinition { Height = new GridLength(50, GridUnitType.Absolute) });
            _mainLayout.Children.Add(_SituacaoLabel, 0, 3);
            Grid.SetColumnSpan(_SituacaoLabel, 2);
        }
    }
}


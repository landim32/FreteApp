using Acr.UserDialogs;
using Emagine.Base.Controls;
using Emagine.Base.Estilo;
using Emagine.Login.Factory;
using Emagine.Pagamento.Factory;
using Emagine.Pagamento.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Pagamento.Pages
{
    public class PagamentoMetodoPage : ContentPage
    {
        private Grid _mainLayout;
        private MenuButton _creditoButton;
        private MenuButton _debitoButton;
        private MenuButton _dinheiroButton;
        private MenuButton _boletoButton;
        private MenuButton _cartaoOfflineButton;

        private bool _usaCredito = true;
        private bool _usaDebito = true;
        private bool _usaDinheiro = true;
        private bool _usaBoleto = true;
        private bool _usaCartaoOffline = true;

        private bool _usaCredito = true;
        private bool _usaDebito = true;
        private bool _usaDinheiro = true;
        private bool _usaBoleto = true;

        public bool UsaCredito {
            get {
                return _usaCredito;
            }
            set {
                _usaCredito = value;
                atualizarTela();
            }
        }

        public bool UsaDebito {
            get {
                return _usaDebito;
            }
            set {
                _usaDebito = value;
                atualizarTela();
            }
        }

        public bool UsaDinheiro {
            get {
                return _usaDinheiro;
            }
            set {
                _usaDinheiro = value;
                atualizarTela();
            }
        }

        public bool UsaBoleto {
            get {
                return _usaBoleto;
            }
            set {
                _usaBoleto = value;
                atualizarTela();
            }
        }

        public PagamentoInfo Pagamento { get; set; }

        public bool UsaCredito {
            get {
                return _usaCredito;
            }
            set {
                _usaCredito = value;
                atualizarTela();
            }
        }

        public bool UsaDebito {
            get {
                return _usaDebito;
            }
            set
            {
                _usaDebito = value;
                atualizarTela();
            }
        }

        public bool UsaDinheiro {
            get {
                return _usaDinheiro;
            }
            set {
                _usaDinheiro = value;
                atualizarTela();
            }
        }

        public bool UsaBoleto {
            get {
                return _usaBoleto;
            }
            set {
                _usaBoleto = value;
                atualizarTela();
            }
        }

        public bool UsaCartaoOffline {
            get
            {
                return _usaCartaoOffline;
            }
            set
            {
                _usaCartaoOffline = value;
                atualizarTela();
            }
        }

        public event EventHandler<PagamentoInfo> AoEfetuarPagamento;

        public PagamentoMetodoPage()
        {
            Title = "Forma de Pagamento";
            Style = Estilo.Current[Estilo.TELA_PADRAO];

            inicilizarComponente();
            _mainLayout = new Grid
            {
                Margin = new Thickness(10, 10),
                RowSpacing = 10,
                ColumnSpacing = 10
            };

            _mainLayout.RowDefinitions.Add(new RowDefinition { Height = new GridLength(1, GridUnitType.Star) });
            _mainLayout.RowDefinitions.Add(new RowDefinition { Height = new GridLength(1, GridUnitType.Star) });
            _mainLayout.RowDefinitions.Add(new RowDefinition { Height = new GridLength(1, GridUnitType.Star) });
            _mainLayout.ColumnDefinitions.Add(new ColumnDefinition { Width = new GridLength(1, GridUnitType.Star) });
            _mainLayout.ColumnDefinitions.Add(new ColumnDefinition { Width = new GridLength(1, GridUnitType.Star) });

            atualizarTela();

            Content = _mainLayout;
        }

        protected void adicionarBotao(MenuButton botao, ref int x, ref int y)
        {
            _mainLayout.Children.Add(botao, x, y);
            x++;
            if (x > 1)
            {
                x = 0;
                y++;
            }
        }

        protected virtual void atualizarTela()
        {
            _mainLayout.Children.Clear();
            int x = 0, y = 0;
            if (UsaCredito)
            {
                adicionarBotao(_creditoButton, ref x, ref y);
            }
            if (UsaDebito)
            {
                adicionarBotao(_debitoButton, ref x, ref y);
            }
            if (UsaDinheiro)
            {
                adicionarBotao(_dinheiroButton, ref x, ref y);
            }
            if (UsaBoleto)
            {
                adicionarBotao(_boletoButton, ref x, ref y);
            }
            if (UsaCartaoOffline)
            {
                adicionarBotao(_cartaoOfflineButton, ref x, ref y);
            }
        }

        private void inicilizarComponente() {
            _creditoButton = new MenuButton {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Icon = "fa-credit-card",
                Text = "Cartão de\nCrédito",
                Style = Estilo.Current[Estilo.BTN_ROOT]
            };
            _creditoButton.Click += creditoOnlineClicked;
            _debitoButton = new MenuButton
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Icon = "fa-credit-card-alt",
                Text = "Cartão de\nDébito",
                Style = Estilo.Current[Estilo.BTN_ROOT]
            };
            _debitoButton.Click += debitoOnlineClicked;
            _dinheiroButton = new MenuButton
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Icon = "fa-money",
                Text = "Em\nDinheiro",
                Style = Estilo.Current[Estilo.BTN_ROOT]
            };
            _dinheiroButton.Click += dinheiroClicked;
            _boletoButton = new MenuButton
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Icon = "fa-barcode",
                Text = "Boleto\nBancário",
                Style = Estilo.Current[Estilo.BTN_ROOT]
            };
            _boletoButton.Click += boletoClicked;
            _cartaoOfflineButton = new MenuButton
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Icon = "fa-ticket",
                Text = "Vale/Ticket\n/Cartão",
                Style = Estilo.Current[Estilo.BTN_ROOT]
            };
            _cartaoOfflineButton.Click += cartaoOfflineClicked;
        }

        private async void creditoOnlineClicked(object sender, EventArgs e) {
            if (Pagamento == null)
            {
                await DisplayAlert("Erro", "Nenhum pagamento informado!", "Fechar");
                return;
            }
            UserDialogs.Instance.ShowLoading("Carregando...");
            try
            {
                Pagamento.Tipo = TipoPagamentoEnum.CreditoOnline;

                var regraUsuario = UsuarioFactory.create();
                var usuario = regraUsuario.pegarAtual();

                var regraCartao = PagamentoCartaoFactory.create();
                var cartoes = await regraCartao.listar(usuario.Id);
                if (cartoes != null && cartoes.Count() > 0)
                {
                    var cartaoListaPage = new CartaoListaPage()
                    {
                        Title = "Meus Cartões",
                        Pagamento = Pagamento,
                        Cartoes = cartoes
                    };
                    cartaoListaPage.AoEfetuarPagamento += (s2, pagamento) => {
                        AoEfetuarPagamento?.Invoke(sender, pagamento);
                    };
                    await Navigation.PushAsync(cartaoListaPage);
                }
                else
                {
                    var cartaoPage = new CartaoPage
                    {
                        Title = "Cartão de Crédito",
                        UsaCredito = true,
                        UsaDebito = false,
                        Pagamento = Pagamento
                    };
                    cartaoPage.AoEfetuarPagamento += (s2, pagamento) =>
                    {
                        AoEfetuarPagamento?.Invoke(sender, pagamento);
                    };
                    await Navigation.PushAsync(cartaoPage);
                }
                UserDialogs.Instance.HideLoading();
            }
            catch (Exception erro)
            {
                UserDialogs.Instance.HideLoading();
                UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
            }
        }

        private async void debitoOnlineClicked(object sender, EventArgs e)
        {
            if (Pagamento == null)
            {
                await DisplayAlert("Erro", "Nenhum pagamento informado!", "Fechar");
                return;
            }
            Pagamento.Tipo = TipoPagamentoEnum.DebitoOnline;
            var cartaoPage = new CartaoPage
            {
                Title = "Cartão de Débito",
                UsaCredito = false,
                UsaDebito = true,
                Pagamento = Pagamento
            };
            cartaoPage.AoEfetuarPagamento += (s2, pagamento) =>
            {
                AoEfetuarPagamento?.Invoke(sender, pagamento);
            };
            await Navigation.PushAsync(cartaoPage);
        }

        private async void dinheiroClicked(object sender, EventArgs e)
        {
            if (Pagamento == null)
            {
                await DisplayAlert("Erro", "Nenhum pagamento informado!", "Fechar");
                return;
            }
            Pagamento.Tipo = TipoPagamentoEnum.Dinheiro;
            var dinheiroPage = new DinheiroPage
            {
                Title = "Em dinheiro",
                Pagamento = Pagamento
            };
            dinheiroPage.AoEfetuarPagamento += (s2, pagamento) =>
            {
                AoEfetuarPagamento?.Invoke(this, pagamento);
            };
            await Navigation.PushAsync(dinheiroPage);
        }

        private async void boletoClicked(object sender, EventArgs e)
        {
            if (Pagamento == null)
            {
                await DisplayAlert("Erro", "Nenhum pagamento informado!", "Fechar");
                return;
            }
            UserDialogs.Instance.ShowLoading("Enviando...");
            try
            {
                Pagamento.Tipo = TipoPagamentoEnum.Boleto;
                var regraPagamento = PagamentoFactory.create();
                var retorno = await regraPagamento.pagar(Pagamento);
                if (retorno.Situacao == SituacaoPagamentoEnum.AguardandoPagamento)
                {
                    Pagamento = await regraPagamento.pegar(retorno.IdPagamento);
                    UserDialogs.Instance.HideLoading();
                    AoEfetuarPagamento?.Invoke(this, Pagamento);
                }
                else
                {
                    UserDialogs.Instance.HideLoading();
                    UserDialogs.Instance.Alert(retorno.Mensagem, "Erro", "Fechar");
                }
            }
            catch (Exception erro)
            {
                UserDialogs.Instance.HideLoading();
                UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
            }
        }

        private async void dinheiroClicked(object sender, EventArgs e)
        {
            if (Pagamento == null)
            {
                await DisplayAlert("Erro", "Nenhum pagamento informado!", "Fechar");
                return;
            }
            Pagamento.Tipo = TipoPagamentoEnum.Dinheiro;
            var dinheiroPage = new DinheiroPage
            {
                Title = "Em dinheiro",
                Pagamento = Pagamento
            };
            dinheiroPage.AoEfetuarPagamento += (s2, pagamento) =>
            {
                AoEfetuarPagamento?.Invoke(this, pagamento);
            };
            await Navigation.PushAsync(dinheiroPage);
        }

        private async void cartaoOfflineClicked(object sender, EventArgs e)
        {
            if (Pagamento == null)
            {
                await DisplayAlert("Erro", "Nenhum pagamento informado!", "Fechar");
                return;
            }
            UserDialogs.Instance.ShowLoading("Enviando...");
            try
            {
                Pagamento.Tipo = TipoPagamentoEnum.CartaoOffline;
                var regraPagamento = PagamentoFactory.create();
                var retorno = await regraPagamento.pagar(Pagamento);
                Pagamento = await regraPagamento.pegar(retorno.IdPagamento);
                UserDialogs.Instance.HideLoading();
                AoEfetuarPagamento?.Invoke(this, Pagamento);
            }
            catch (Exception erro)
            {
                UserDialogs.Instance.HideLoading();
                UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
            }
        }
    }
}
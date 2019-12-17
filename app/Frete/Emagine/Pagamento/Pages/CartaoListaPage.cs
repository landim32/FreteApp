using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Base.Utils;
using Emagine.Login.Factory;
using Emagine.Pagamento.Cells;
using Emagine.Pagamento.Factory;
using Emagine.Pagamento.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Pagamento.Pages
{
    public class CartaoListaPage : ContentPage
    {
        private PagamentoInfo _pagamento;
        private IList<PagamentoCartaoInfo> _cartoes;

        private StackLayout _mainLayout;
        private ListView _cartaoListView;
        private Button _NovoButton;

        public event EventHandler<PagamentoInfo> AoEfetuarPagamento;

        public CartaoListaPage()
        {
            Title = "Meus Cartões";
            Style = Estilo.Current[Estilo.TELA_PADRAO];

            _cartoes = new List<PagamentoCartaoInfo>();
            inicializarComponente();
            _mainLayout = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Fill
            };
            atualizarTela();
            Content = _mainLayout;
        }

        private void atualizarTela() {
            _mainLayout.Children.Clear();
            _mainLayout.Children.Add(_cartaoListView);
            if (_pagamento != null)
            {
                _mainLayout.Children.Add(_NovoButton);
            }
        }

        public PagamentoInfo Pagamento {
            get {
                return _pagamento;
            }
            set {
                _pagamento = value;
                atualizarTela();
                if (_pagamento != null)
                {
                    _cartaoListView.ItemTapped += cartaoListaItemTapped;
                }
                else {
                    _cartaoListView.ItemTapped -= cartaoListaItemTapped;
                }
            }
        }

        public IList<PagamentoCartaoInfo> Cartoes {
            get {
                return _cartoes;
            }
            set {
                _cartaoListView.ItemsSource = null;
                _cartoes = value;
                _cartaoListView.ItemsSource = _cartoes;
            }
        }

        private void inicializarComponente()
        {
            _NovoButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.End,
                Margin = new Thickness(8, 0),
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                Text = "Novo cartão"
            };
            _NovoButton.Clicked += (sender, e) => {
                Navigation.PushAsync(new CartaoPage {
                    Pagamento = _pagamento
                });
            };
            _cartaoListView = new ListView {
                HasUnevenRows = true,
                RowHeight = -1,
                SeparatorVisibility = SeparatorVisibility.None,
                ItemTemplate = new DataTemplate(typeof(CartaoCell))
            };
            _cartaoListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
        }

        private async void cartaoListaItemTapped(object sender, ItemTappedEventArgs e)
        {
            if (e == null)
                return;

            PagamentoCartaoInfo entrInfo = (PagamentoCartaoInfo)((ListView)sender).SelectedItem;
            UserDialogs.Instance.ShowLoading("Enviando...");
            try
            {
                var regraPagamento = PagamentoFactory.create();
                var retorno = await regraPagamento.pagarComToken(_pagamento);
                if (retorno.Situacao == SituacaoPagamentoEnum.Pago)
                {
                    var pagamento = await regraPagamento.pegar(retorno.IdPagamento);
                    UserDialogs.Instance.HideLoading();
                    AoEfetuarPagamento?.Invoke(this, pagamento);
                }
                else
                {
                    UserDialogs.Instance.HideLoading();
                    await DisplayAlert("Erro", retorno.Mensagem, "Entendi");
                }
            }
            catch (Exception erro)
            {
                UserDialogs.Instance.HideLoading();
                UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
            }
        }
    }
}
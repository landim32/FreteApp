using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Produto.Factory;
using Emagine.Produto.Model;
using Emagine.Produto.Pages;
using Emagine.Produto.Utils;
using Emagine.Veiculo.Cells;
using Emagine.Veiculo.Pages;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Produto.Pages
{
    public class ProdutoBuscaPage : ContentPage
    {
        private SearchBar _palavraChaveSearchBar;
        private ListView _ProdutoListView;
        private Label _TotalLabel;

        public ProdutoBuscaPage()
        {
            ToolbarItems.Add(new IconToolbarItem
            {
                Text = "Carrinho",
                Icon = "fa-shopping-cart",
                IconColor = Estilo.Current.BarTitleColor,
                Order = ToolbarItemOrder.Primary,
                Command = new Command(() => {
                    Navigation.PushAsync(CarrinhoUtils.gerarCarrinhoParaEntrega());
                })
            });

            Title = "Lista";
            Style = Estilo.Current[Estilo.TELA_PADRAO];
            inicializarComponente();
            Content = new StackLayout
            {
                //Margin = new Thickness(3, 3),
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    _palavraChaveSearchBar,
                    _ProdutoListView,
                    new Frame {
                        Style = Estilo.Current[Estilo.TOTAL_FRAME],
                        Content = new StackLayout {
                            Orientation = StackOrientation.Horizontal,
                            VerticalOptions = LayoutOptions.Start,
                            HorizontalOptions = LayoutOptions.CenterAndExpand,
                            Spacing = 5,
                            Children = {
                                new Label {
                                    VerticalOptions = LayoutOptions.Center,
                                    HorizontalOptions = LayoutOptions.Start,
                                    Style = Estilo.Current[Estilo.TOTAL_LABEL],
                                    Text = "Total: "
                                },
                                _TotalLabel
                            }
                        }
                    },
                }
            };
        }

        protected override void OnAppearing()
        {
            base.OnAppearing();
            var regraCarrinho = CarrinhoFactory.create();
            regraCarrinho.AoAtualizar += CarrinhoAoAtualizar;
            _TotalLabel.Text = "R$ " + regraCarrinho.getTotal().ToString("N2");
        }

        protected override void OnDisappearing()
        {
            var regraCarrinho = CarrinhoFactory.create();
            regraCarrinho.AoAtualizar -= CarrinhoAoAtualizar;
            base.OnDisappearing();
        }

        private void CarrinhoAoAtualizar(object sender, double e)
        {
            _TotalLabel.Text = "R$ " + e.ToString("N2");
        }

        private void inicializarComponente() {
            _palavraChaveSearchBar = new SearchBar
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Placeholder = "Buscar produto...",
                SearchCommand = new Command(() => {
                    executarBusca(_palavraChaveSearchBar.Text);
                })
            };
            _ProdutoListView = new ListView {
                HasUnevenRows = true,
                RowHeight = -1,
                SeparatorVisibility = SeparatorVisibility.None,
                ItemTemplate = new DataTemplate(typeof(ProdutoCell))
            };
            _ProdutoListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            _ProdutoListView.ItemTapped += (sender, e) => {
                if (e == null)
                    return;
                var veiculo = (ProdutoInfo)((ListView)sender).SelectedItem;
                _ProdutoListView.SelectedItem = null;
                /*
                ImovelInfo imovel = (ImovelInfo)((ListView)sender).SelectedItem;

                //var perfil = PreferenciaUtils.Perfil;
                Navigation.PushAsync(new ImovelExibePage(imovel));
                _imovelListView.SelectedItem = null;
                */
            };

            _TotalLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.TOTAL_TEXTO],
                Text = "R$ 0,00"
            };
        }

        private async void executarBusca(string palavraChave)
        {
            try {
                UserDialogs.Instance.ShowLoading("Buscando...");
                var regraProduto = ProdutoFactory.create();
                var regraLoja = LojaFactory.create();
                var loja = regraLoja.pegarAtual();
                _ProdutoListView.ItemsSource = await regraProduto.buscar(loja.Id, palavraChave);
                UserDialogs.Instance.HideLoading();
            }
            catch (Exception erro)
            {
                UserDialogs.Instance.HideLoading();
                UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
            }
        }
    }
}
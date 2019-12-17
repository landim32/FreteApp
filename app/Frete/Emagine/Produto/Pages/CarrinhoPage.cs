using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Pagamento.Model;
using Emagine.Pagamento.Pages;
using Emagine.Produto.Factory;
using Emagine.Produto.Model;
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
    public class CarrinhoPage : ContentPage
    {
        private ListView _ProdutoListView;
        private Label _TotalLabel;
        private Button _FinalizarCompraButton;

        public event EventHandler<IList<ProdutoInfo>> AoFinalizar;

        public CarrinhoPage()
        {
            Title = "Meu Carrinho";

            ToolbarItems.Add(new IconToolbarItem
            {
                Text = "Limpar",
                Icon = "fa-trash",
                IconColor = Estilo.Current.BarTitleColor,
                Order = ToolbarItemOrder.Primary,
                Command = new Command(() => {
                    UserDialogs.Instance.Confirm(new ConfirmConfig
                    {
                        Title = "Aviso",
                        Message = "Tem certeza?",
                        OkText = "Sim",
                        CancelText = "Não",
                        OnAction = (confirmado) =>
                        {
                            if (confirmado)
                            {
                                var regraCarrinho = CarrinhoFactory.create();
                                regraCarrinho.limpar();
                                _ProdutoListView.ItemsSource = regraCarrinho.listar();
                            }
                        }
                    });
                })
            });

            Style = Estilo.Current[Estilo.TELA_PADRAO];
            //BackgroundColor = Color.FromHex("#d9d9d9");
            inicializarComponente();
            Content = new StackLayout
            {
                //Margin = new Thickness(3, 3),
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    _ProdutoListView,
                    new Frame {
                        Style = Estilo.Current[Estilo.TOTAL_FRAME],
                        Content = new StackLayout {
                            Orientation = StackOrientation.Horizontal,
                            VerticalOptions = LayoutOptions.Start,
                            HorizontalOptions = LayoutOptions.CenterAndExpand,
                            Spacing = 2,
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
                    _FinalizarCompraButton
                }
            };
        }

        protected override void OnAppearing()
        {
            base.OnAppearing();
            var regraCarrinho = CarrinhoFactory.create();
            regraCarrinho.AoAtualizar += CarrinhoAoAtualizar;
            _TotalLabel.Text = "R$ " + regraCarrinho.getTotal().ToString("N2");
            _ProdutoListView.ItemsSource = regraCarrinho.listar();
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
            _ProdutoListView = new ListView {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.FillAndExpand,
                HasUnevenRows = true,
                RowHeight = -1,
                SeparatorVisibility = SeparatorVisibility.None,
                ItemTemplate = new DataTemplate(typeof(ProdutoCell))
            };
            _ProdutoListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            /*
            _ProdutoListView.ItemTapped += (sender, e) => {
                if (e == null)
                    return;
                var veiculo = (ProdutoInfo)((ListView)sender).SelectedItem;
                _ProdutoListView.SelectedItem = null;
            };
            */

            _TotalLabel = new Label {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.TOTAL_TEXTO],
                Text = "R$ 0,00",
            };

            _FinalizarCompraButton = new Button {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.End,
                Text = "Finalizar Compra",
                Margin = new Thickness(4, 0, 4, 3),
                Style = Estilo.Current[Estilo.BTN_SUCESSO]
            };
            _FinalizarCompraButton.Clicked += FinalizarCompraButtonClicked;
        }

        private void FinalizarCompraButtonClicked(object sender, EventArgs e)
        {
            if (AoFinalizar != null)
            {
                var regraCarrinho = CarrinhoFactory.create();
                var produtos = regraCarrinho.listar();
                AoFinalizar(this, produtos);
            }
        }
    }
}
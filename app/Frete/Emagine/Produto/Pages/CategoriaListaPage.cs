using Emagine.Base.Estilo;
using Emagine.Produto.Cells;
using Emagine.Produto.Factory;
using Emagine.Produto.Model;
using Emagine.Produto.Utils;
using Emagine.Veiculo.Cells;
using Emagine.Veiculo.Pages;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Veiculo.Pages
{
    public class CategoriaListaPage : ContentPage
    {
        private ListView _categoriaListView;

        public CategoriaListaPage()
        {
            Title = "Departamentos";
            Style = Estilo.Current[Estilo.TELA_EM_BRANCO];
            inicializarComponente();
            Content = new StackLayout
            {
                Margin = new Thickness(3, 3),
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    _categoriaListView
                }
            };
        }

        public int? IdCategoria { get; set; }

        protected override async void OnAppearing()
        {
            base.OnAppearing();
            var regraLoja = LojaFactory.create();
            var loja = regraLoja.pegarAtual();
            if (loja != null) {
                var regraCategoria = CategoriaFactory.create();
                if (IdCategoria.HasValue) {
                    _categoriaListView.ItemsSource = await regraCategoria.listarPorCategoria(loja.Id, IdCategoria.Value);
                }
                else {
                    _categoriaListView.ItemsSource = await regraCategoria.listarPai(loja.Id);
                }
            }
            else {
                await DisplayAlert("Aviso", "Nenhuma loja selecionada.", "Fechar");
            }
        }

        private void inicializarComponente() {
            _categoriaListView = new ListView {
                HasUnevenRows = true,
                RowHeight = -1,
                SeparatorVisibility = SeparatorVisibility.Default,
                SeparatorColor = Estilo.Current.PrimaryColor,
                ItemTemplate = new DataTemplate(typeof(CategoriaCell))
            };
            _categoriaListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            _categoriaListView.ItemTapped += async (sender, e) => {
                if (e == null)
                    return;
                var categoria = (CategoriaInfo)((ListView)sender).SelectedItem;
                _categoriaListView.SelectedItem = null;

                var regraLoja = LojaFactory.create();
                var loja = regraLoja.pegarAtual();
                if (loja == null)
                {
                    await DisplayAlert("Aviso", "Nenhuma loja selecionada.", "Fechar");
                    return;
                }

                var regraCategoria = CategoriaFactory.create();
                var categoriasFilho = await regraCategoria.listarPorCategoria(loja.Id, categoria.Id);

                if (categoriasFilho.Count > 0)
                {
                    await Navigation.PushAsync(new CategoriaListaPage
                    {
                        Title = categoria.Nome,
                        IdCategoria = categoria.Id
                    });
                }
                else {
                    var produtoPage = ProdutoUtils.gerarProdutoListaPorCategoria(categoria);
                    await Navigation.PushAsync(produtoPage);
                }
            };
        }
    }
}
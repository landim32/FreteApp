using Emagine.Base.BLL;
using Emagine.Base.Utils;
using Emagine.Produto.Factory;
using Emagine.Produto.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Web;

namespace Emagine.Produto.BLL
{
    public class ProdutoBLL: RestAPIBase
    {
        private void atualizarDoCarrinho(IList<ProdutoInfo> produtos) {
            var regraCarrinho = CarrinhoFactory.create();
            foreach (var produto in produtos) {
                var produtoDoCarrinho = regraCarrinho.pegar(produto.Id);
                if (produtoDoCarrinho != null) {
                    produto.QuantidadeCarrinho = produtoDoCarrinho.QuantidadeCarrinho;
                }
            }
        }

        public async Task<IList<ProdutoInfo>> buscar(int idLoja, string palavraChave)
        {
            string url = GlobalUtils.URLAplicacao + "/api/produto/buscar/" + idLoja.ToString();
            if (!string.IsNullOrEmpty(palavraChave))
            {
                url += "?p=" + HttpUtility.UrlEncode(palavraChave);
            }
            var produtos = await queryGet<IList<ProdutoInfo>>(url);
            atualizarDoCarrinho(produtos);
            return produtos;
        }

        public async Task<IList<ProdutoInfo>> listar(int idLoja, int idCategoria = 0) {
            string url = GlobalUtils.URLAplicacao + "/api/produto/listar/" + idLoja.ToString();
            if (idCategoria > 0) {
                url += "/" + idCategoria.ToString();
            }
            var produtos = await queryGet<IList<ProdutoInfo>>(url);
            atualizarDoCarrinho(produtos);
            return produtos;
        }

        public async Task<IList<ProdutoInfo>> listarPorFiltro(ProdutoFiltroInfo filtro)
        {
            string url = GlobalUtils.URLAplicacao + "/api/produto/listar-por-filtro";
            var args = new List<object>() { filtro };
            var produtos = await queryPut<IList<ProdutoInfo>>(url, args.ToArray());
            atualizarDoCarrinho(produtos);
            return produtos;
        }

        public async Task<IList<ProdutoInfo>> listarDestaque(int idLoja)
        {
            var produtos = await queryGet<IList<ProdutoInfo>>(GlobalUtils.URLAplicacao + "/api/produto/listar-destaque/" + idLoja.ToString());
            atualizarDoCarrinho(produtos);
            return produtos;
        }
    }
}

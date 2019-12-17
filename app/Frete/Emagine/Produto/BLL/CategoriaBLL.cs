using Acr.UserDialogs;
using Emagine.Base.BLL;
using Emagine.Base.Utils;
using Emagine.Produto.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Loja.Emagine.Produto.BLL
{
    public class CategoriaBLL : RestAPIBase
    {
        private IList<CategoriaInfo> _categorias = null;

        public async Task<IList<CategoriaInfo>> listar(int idLoja)
        {
            if (_categorias == null)
            {
                UserDialogs.Instance.ShowLoading("Carregando...");
                try
                {
                    _categorias = await queryGet<IList<CategoriaInfo>>(GlobalUtils.URLAplicacao + "/api/categoria/listar/" + idLoja);
                    UserDialogs.Instance.HideLoading();
                }
                catch (Exception erro)
                {
                    UserDialogs.Instance.HideLoading();
                    UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
                }
            }
            return _categorias;
        }

        public async Task<IList<CategoriaInfo>> listarPai(int idLoja)
        {
            var categorias = await listar(idLoja);
            return (
                from categoria in categorias
                where categoria.IdLoja == idLoja && categoria.IdPai == null
                select categoria
            ).ToList();
        }

        public async Task<IList<CategoriaInfo>> listarPorCategoria(int idLoja, int idCategoria)
        {
            var categorias = await listar(idLoja);
            return (
                from categoria in categorias
                where categoria.IdLoja == idLoja && categoria.IdPai == idCategoria
                select categoria
            ).ToList();
        }
    }
}

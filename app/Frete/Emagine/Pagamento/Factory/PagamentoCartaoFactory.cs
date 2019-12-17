using Emagine.Pagamento.BLL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Pagamento.Factory
{
    public static class PagamentoCartaoFactory
    {
        private static PagamentoCartaoBLL _Cartao;

        public static PagamentoCartaoBLL create()
        {
            if (_Cartao == null)
            {
                _Cartao = new PagamentoCartaoBLL();
            }
            return _Cartao;
        }

    }
}

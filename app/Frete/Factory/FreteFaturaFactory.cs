using Frete.BLL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Frete.Factory
{
    public static class FreteFaturaFactory
    {
        private static FreteFaturaBLL _Fatura;

        public static FreteFaturaBLL create()
        {
            if (_Fatura == null)
            {
                _Fatura = new FreteFaturaBLL();
            }
            return _Fatura;
        }
    }
}

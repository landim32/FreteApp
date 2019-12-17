using Emagine.Login.BLL;
using Emagine.Produto.BLL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Produto.Factory
{
    public static class LojaFactory
    {
        private static LojaBLL _loja;

        public static LojaBLL create()
        {
            if (_loja == null)
            {
                _loja = new LojaBLL();
            }
            return _loja;
        }

    }
}

using Emagine.Login.BLL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Login.Factory
{
    public static class UsuarioFactory
    {
        private static UsuarioBLL _Usuario;

        public static UsuarioBLL create()
        {
            if (_Usuario == null)
            {
                _Usuario = new UsuarioBLL();
            }
            return _Usuario;
        }

    }
}

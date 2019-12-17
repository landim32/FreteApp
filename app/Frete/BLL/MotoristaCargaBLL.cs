using Emagine.Base.Utils;
using Emagine.Frete.BLL;
using Emagine.Login.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Frete.BLL
{
    public class MotoristaCargaBLL: MotoristaBLL
    {
        public async Task<int> logar(string email, string senha)
        {
            var args = new List<object>();
            args.Add(new LoginInfo
            {
                Email = email,
                Senha = senha
            });
            var url = GlobalUtils.URLAplicacao + "/api/motorista/logar";
            return await queryPut<int>(url, args.ToArray());
        }
    }
}

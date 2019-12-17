using System;
using System.Collections.Generic;
using System.Threading.Tasks;
using Emagine.Base.BLL;
using Emagine.Base.Utils;
using Emagine.Frete.Model;
using Frete.Model;

namespace Frete.BLL
{
    public class FreteFaturaBLL: RestAPIBase
    {
        public async Task<List<FreteFaturaInfo>> listar(int idUsuario)
        {
            var url = GlobalUtils.URLAplicacao + "/api/fatura/listar/" + idUsuario.ToString();
            return await queryGet<List<FreteFaturaInfo>>(url);
        }
    }
}

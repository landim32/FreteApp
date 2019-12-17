using Acr.UserDialogs;
using Emagine.Frete.Factory;
using Emagine.Login.Model;
using Emagine.Login.Pages;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Frete.Pages
{
    public class CustomUsuarioGerenciaPage: UsuarioGerenciaPage
    {
        private CustomCadastroMotoristaPage _motoristaPage;

        public CustomUsuarioGerenciaPage() : base() {
            if (Children.Count() > 0)
            {
                Children.Insert(1, _motoristaPage);
            }
        }

        protected override void inicializarComponente() {
            base.inicializarComponente();
            _motoristaPage = new CustomCadastroMotoristaPage
            {
                Title = "Marinheiro",
                Gravar = true
            };
            _motoristaPage.AoCompletar += (sender, motorista) => {
                UserDialogs.Instance.Alert("Dados alterados com sucesso.", "Sucesso", "Entendi");
            };
        }

        /*
        protected override async Task<UsuarioInfo> carregarUsuario() {
            var usuario = await base.carregarUsuario();
            var regraMotorista = MotoristaFactory.create();
            var motorista = await regraMotorista.pegar(usuario.Id);
            if (motorista != null)
            {
                _motoristaPage.Motorista = motorista;
            }
            return usuario;
        }
        */
    }
}

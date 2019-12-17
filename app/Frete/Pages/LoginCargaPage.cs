using Emagine.Frete.Factory;
using Emagine.Frete.Model;
using Emagine.Login.Factory;
using Emagine.Login.Pages;
using Frete.BLL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Frete.Pages
{
    public class LoginCargaPage : LoginPage
    {
        public event EventHandler<MotoristaInfo> AoLogarMotorista;

        protected async Task executarLoginMotorista() {
            var regraMotorista = (MotoristaCargaBLL) MotoristaFactory.create();
            var id_motorista = await regraMotorista.logar(Email, Senha);
            if (id_motorista > 0)
            {
                var motorista = await regraMotorista.pegar(id_motorista);
                if (motorista != null)
                {
                    var regraUsuario = UsuarioFactory.create();
                    regraUsuario.gravarAtual(motorista.Usuario);
                    regraMotorista.gravarAtual(motorista);
                    AoLogarMotorista?.Invoke(this, motorista);
                }
                else
                {
                    string mensagem = string.Format("Nenhum usuário encontrado com o ID {0}.", id_motorista);
                    await DisplayAlert("Aviso", mensagem, "Fechar");
                }
            }
            else {
                await DisplayAlert("Aviso", "Usuário ou senha inválida.", "Entendi");
            }
        }

        protected override async Task executarLogin()
        {
            try
            {
                var regraUsuario = UsuarioFactory.create();
                var id_usuario = await regraUsuario.logar(Email, Senha);
                if (id_usuario > 0) {
                    var usuario = await regraUsuario.pegar(id_usuario);
                    if (usuario != null)
                    {
                        regraUsuario.gravarAtual(usuario);
                        executarEventoLogar(usuario);
                    }
                    else
                    {
                        string mensagem = string.Format("Nenhum usuário encontrado com o ID {0}.", id_usuario);
                        await DisplayAlert("Aviso", mensagem, "Fechar");
                    }
                }
                else {
                    await DisplayAlert("Aviso", "Usuário ou senha inválida.", "Entendi");
                }
            }
            catch (Exception erro) {
                await executarLoginMotorista();
            }
        }
    }
}
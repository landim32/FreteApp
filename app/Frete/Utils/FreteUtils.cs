using Acr.UserDialogs;
using Emagine;
using Emagine.Base.Pages;
using Emagine.Frete.Factory;
using Emagine.Frete.Model;
using Emagine.Frete.Pages;
using Emagine.Login.Factory;
using Emagine.Pagamento.Model;
using Emagine.Pagamento.Pages;
using Frete.Pages;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Frete.Utils
{
    public static class FreteUtils
    {
        public static async Task criarUsuario(Action aoCriar)
        {
            var selecionePage = new UsuarioSelecionePage();
            selecionePage.AoSelecionaCliente += (s1, e1) => {
                var cadastroPage = (CustomUsuarioFormPage)UsuarioFormPageFactory.create();
                cadastroPage.Title = "NOVO PASSAGEIRO";
                cadastroPage.AoCadastrar += (s2, e2) => {
                    aoCriar?.Invoke();
                };
                ((Page)s1).Navigation.PushAsync(cadastroPage);
            };
            selecionePage.AoSelecionaMotorista += (s1, e1) => {
                var cadastroPage = (CustomUsuarioFormPage)UsuarioFormPageFactory.create();
                cadastroPage.Title = "NOVO MARINHEIRO";
                cadastroPage.Gravar = false;
                cadastroPage.Motorista = true;
                cadastroPage.AoCadastrar += (s2, usuario) => {
                    var motoristaPage = CadastroMotoristaPageFactory.create();
                    motoristaPage.Usuario = usuario;
                    motoristaPage.Gravar = false;
                    motoristaPage.AoCompletar += (s3, motorista) =>
                    {
                        var termoPage = new MotoristaTermoPage();
                        termoPage.AoCadastrar += async (s4, e4) =>
                        {
                            UserDialogs.Instance.ShowLoading("Enviando...");
                            var regraUsuario = UsuarioFactory.create();
                            try
                            {
                                int id_usuario = usuario.Id;
                                if (id_usuario > 0)
                                {
                                    await regraUsuario.alterar(usuario);
                                    id_usuario = usuario.Id;
                                }
                                else
                                {
                                    id_usuario = await regraUsuario.inserir(usuario);
                                }
                                var usuarioCadastrado = await regraUsuario.pegar(id_usuario);
                                regraUsuario.gravarAtual(usuarioCadastrado);
                                if (App.Current.MainPage is RootPage)
                                {
                                    ((RootPage)App.Current.MainPage).atualizarMenu();
                                }

                                var regraMotorista = MotoristaFactory.create();
                                var motoristaAtual = await regraMotorista.pegar(id_usuario);
                                if (motoristaAtual != null)
                                {
                                    await regraMotorista.alterar(motorista);
                                }
                                else
                                {
                                    motorista.Id = id_usuario;
                                    motorista.Situacao = MotoristaSituacaoEnum.AguardandoAprovacao;
                                    await regraMotorista.inserir(motorista);
                                }
                                var motoristaCadastrado = await regraMotorista.pegar(id_usuario);
                                regraMotorista.gravarAtual(motoristaCadastrado);

                                UserDialogs.Instance.HideLoading();
                                var pagamento = new PagamentoInfo
                                {
                                    IdUsuario = usuarioCadastrado.Id,
                                    Observacao = "Taxa de Adesão",
                                    Tipo = TipoPagamentoEnum.CreditoOnline,
                                    Situacao = SituacaoPagamentoEnum.Aberto
                                };
                                pagamento.Itens.Add(new PagamentoItemInfo
                                {
                                    Descricao = "Taxa de Adesão",
                                    Quantidade = 1,
                                    //Valor = 50
                                    Valor = 1
                                });
                                var cartaoPage = new CartaoPage
                                {
                                    Pagamento = pagamento,
                                    UsaCredito = true,
                                    UsaDebito = false,
                                    TotalVisivel = true
                                };
                                cartaoPage.AoEfetuarPagamento += async (s5, pagamentoRetorno) => {
                                    //aoCriar?.Invoke();
                                    var sucessoPage = new PagamentoSucessoPage();
                                    //await ((Page)s5).Navigation.PopToRootAsync();
                                    await ((Page)s5).Navigation.PushAsync(sucessoPage);
                                };
                                await ((Page)s4).Navigation.PushAsync(cartaoPage);

                            }
                            catch (Exception erro)
                            {
                                UserDialogs.Instance.HideLoading();
                                UserDialogs.Instance.Alert(erro.Message, "Erro", "Entendi");
                            }
                        };
                        ((Page)s3).Navigation.PushAsync(termoPage);
                    };
                    ((Page)s1).Navigation.PushAsync(motoristaPage);
                };
                ((UsuarioSelecionePage)s1).Navigation.PushAsync(cadastroPage);
            };
            var atualPage = App.Current.MainPage;
            await atualPage.Navigation.PushAsync(selecionePage);
        }
    }
}

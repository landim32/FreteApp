using System;
using System.Threading.Tasks;
using Frete.Pages;
using Xamarin.Forms;
using System.Linq;
using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Base.Model;
using Emagine.Base.Pages;
using System.Collections.Generic;
using Emagine.Login.Factory;
using Emagine.Login.Pages;
using Emagine.Frete.BLL;
using Emagine.Base.Utils;
using FormsPlugin.Iconize;
using Emagine.Frete.Pages;
using Emagine.Frete.Factory;
using Emagine.Login.BLL;
using Frete.BLL;

namespace Emagine
{
    public class App : Application
    {
        
        public App()
        {
            GlobalUtils.URLAplicacao = "http://emagine.com.br/mais-cargas";
            MotoristaFactory.Tipo = typeof(MotoristaCargaBLL);

            var regraUsuario = UsuarioFactory.create();
            var usuario = regraUsuario.pegarAtual();
            var estilo = criarEstilo();
            if (usuario != null)
            {
                MainPage = new RootPage
                {
                    NomeApp = "Mais Cargas",
                    PaginaAtual = new AvisoPage(),
                    Menus = gerarMenu()
                };
                //Controls.RootPage.init(new Pages.Menu());
                //MainPage = Controls.RootPage.root;
            }
            else {
                MainPage = new IconNavigationPage(new InicialPage()) {
                    BarBackgroundColor = Estilo.Current.BarBackgroundColor,
                    BarTextColor = Estilo.Current.BarTitleColor
                };
            }
        }

        private Estilo criarEstilo()
        {
            var estilo = Estilo.Current;
            estilo.PrimaryColor = Color.FromHex("#5ca7e1");
            estilo.SuccessColor = Color.FromHex("#a2c760");
            estilo.WarningColor = Color.FromHex("#e2944a");
            estilo.BarBackgroundColor = estilo.PrimaryColor;
            estilo.TelaPadrao.BackgroundColor = Color.FromHex("#d9d9d9");
            estilo.BgPadrao.BackgroundColor = Color.FromHex("#ffffff");
            estilo.BgRoot.BackgroundColor = Color.FromHex("#d9d9d9");
            estilo.BotaoRoot.BackgroundColor = Color.White;
            estilo.BgInvestido.BackgroundColor = Color.FromHex("#c3c1c2");
            estilo.MenuPagina.BackgroundColor = estilo.PrimaryColor;
            estilo.MenuLista.SeparatorColor = Color.White;
            estilo.MenuLista.BackgroundColor = estilo.PrimaryColor;
            //estilo.ListaPadrao.UsaSeparador = (Device.OS == TargetPlatform.Android ? SeparatorVisibility.Default : SeparatorVisibility.None);
            estilo.MenuTexto.TextColor = Color.White;
            estilo.MenuTexto.FontSize = 18;
            estilo.MenuIcone.IconColor = Color.White;
            estilo.MenuIcone.IconSize = 22;
            estilo.EntryMaterial = new EstiloXfxEntry {
                Margin = new Thickness(3, 0),
                //BackgroundColor = Color.Red
            };
            estilo.BotaoPrincipal.TextColor = Color.White;
            estilo.BotaoPrincipal.FontSize = 20;
            estilo.BotaoPrincipal.FontAttributes = FontAttributes.Bold;
            estilo.BotaoPrincipal.BackgroundColor = estilo.PrimaryColor;
            estilo.BotaoSucesso.TextColor = Color.White;
            estilo.BotaoSucesso.FontSize = 20;
            estilo.BotaoSucesso.FontAttributes = FontAttributes.Bold;
            estilo.BotaoSucesso.BackgroundColor = estilo.SuccessColor;
            estilo.BotaoPadrao.TextColor = Color.White;
            estilo.BotaoPadrao.FontSize = 16;
            estilo.BotaoPadrao.FontAttributes = FontAttributes.None;
            estilo.BotaoPadrao.BackgroundColor = Color.FromHex("#78909c");
            estilo.BotaoInfo.BackgroundColor = Color.FromHex("#33b5e5");
            estilo.Titulo1.TextColor = Color.FromHex("#7c7c7c");
            estilo.Titulo1.FontAttributes = FontAttributes.Bold;
            estilo.Titulo1.FontSize = 22;
            estilo.Titulo2.TextColor = Color.FromHex("#7c7c7c");
            estilo.Titulo2.FontAttributes = FontAttributes.None;
            estilo.Titulo2.FontSize = 20;
            estilo.Titulo3.TextColor = Color.FromHex("#7c7c7c");
            estilo.Titulo3.FontAttributes = FontAttributes.Italic;
            estilo.Titulo3.FontSize = 18;
            estilo.LabelControle.TextColor = Color.FromHex("#7c7c7c");
            estilo.LabelSwitch.TextColor = Color.FromHex("#7c7c7c");
            estilo.LabelSwitch.FontSize = 18;
            estilo.IconePadrao.IconSize = 20;
            App.Current.Resources = estilo.gerar();
            return estilo;
        }

        public IList<MenuItemInfo> gerarMenu()
        {
            var regraUsuario = UsuarioFactory.create();
            var usuario = regraUsuario.pegarAtual();

            var regraMotorista = MotoristaFactory.create();
            var motorista = regraMotorista.pegarAtual();

            var menus = new List<MenuItemInfo>();

            menus.Add(new MenuItemInfo
            {
                IconeFA = "fa-home",
                Titulo = "Início",
                aoClicar = (sender, e) =>
                {
                    ((RootPage)MainPage).PushAsync(new AvisoPage());
                }
            });


            if (motorista != null)
            {
                menus.Add(new MenuItemInfo
                {
                    IconeFA = "fa-search",
                    Titulo = "Buscar Fretes",
                    aoClicar = (sender, e) =>
                    {
                        ((RootPage)MainPage).PushAsync(new FreteMotoristaListaPage());
                    }
                });

                menus.Add(new MenuItemInfo
                {
                    IconeFA = "fa-search",
                    Titulo = "Meus fretes Emp",
                    aoClicar = (sender, e) =>
                    {
                        ((RootPage)MainPage).PushAsync(new FreteClienteListaPage());
                    }
                });

                menus.Add(new MenuItemInfo
                {
                    IconeFA = "fa-plus",
                    Titulo = "Criar Frete",
                    aoClicar = (sender, e) =>
                    {
                        ((RootPage)MainPage).PushAsync(new FreteFormPage());
                    }
                });

                menus.Add(new MenuItemInfo
                {
                    IconeFA = "fa-user",
                    Titulo = "Apontar disponibilidade",
                    aoClicar = (sender, e) =>
                    {
                        ((RootPage)MainPage).PushAsync(new DisponibilidadeListaPage());
                    }
                });

                menus.Add(new MenuItemInfo
                {
                    IconeFA = "fa-money",
                    Titulo = "Faturas",
                    aoClicar = (sender, e) =>
                    {
                        ((RootPage)MainPage).PushAsync(new FreteFaturaListaPage());
                    }
                });

                /*menus.Add(new MenuItemInfo
                {
                    IconeFA = "fa-refresh",
                    Titulo = "Histórico dos fretes",
                    aoClicar = async (sender, e) =>
                    {
                        //var loginPage = new LoginPage();
                        //((RootPage)MainPage).PushAsync(loginPage);
                    }
                });*/
            }
            else {
                menus.Add(new MenuItemInfo {
                    IconeFA = "fa-search",
                    Titulo = "Meus fretes",
                    aoClicar = (sender, e) =>
                    {
                        ((RootPage)MainPage).PushAsync(new FreteClienteListaPage());
                    }
                });
                menus.Add(new MenuItemInfo
                {
                    IconeFA = "fa-plus",
                    Titulo = "Criar Frete",
                    aoClicar = (sender, e) =>
                    {
                        ((RootPage)MainPage).PushAsync(new FreteFormPage());
                    }
                });
            }

            menus.Add(new MenuItemInfo
            {
                IconeFA = "fa-user-plus",
                Titulo = "Meu cadastro",
                aoClicar = (sender, e) =>
                {
                    ((RootPage)MainPage).PushAsync(new FreteUsuarioFormPage
                    {
                        Title = "Alterar meus dados",
                        Usuario = UsuarioFactory.create().pegarAtual()
                    });
                }
            });

            menus.Add(new MenuItemInfo
            {
                IconeFA = "fa-question",
                Titulo = "Sobre Mais Cargas",
                aoClicar = (sender, e) =>
                {

                }
            });

            menus.Add(new MenuItemInfo
            {
                IconeFA = "fa-remove",
                Titulo = "Sair",
                aoClicar = async (sender, e) => {
                    var localMotorista = MotoristaFactory.create();
                    await localMotorista.limparAtual();
                    var localUsuario = UsuarioFactory.create();
                    await localUsuario.limparAtual();
                    MainPage = new IconNavigationPage(new InicialPage())
                    {
                        BarBackgroundColor = Estilo.Current.BarBackgroundColor,
                        BarTextColor = Estilo.Current.BarTitleColor
                    };
                }
            });

            return menus;
        }

        protected override void OnStart()
        {
            // Handle when your app starts
            var regraMotorista = MotoristaFactory.create();
            var motorista = regraMotorista.pegarAtual();
            if(motorista != null){
                AtualizacaoFrete.start();   
            }
        }


        protected override void OnSleep()
        {
            
            // Handle when your app sleeps
        }

        protected override void OnResume()
        {
            // Handle when your app resumes
        }
    }
}

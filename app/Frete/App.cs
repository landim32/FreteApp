using System;
using System.Threading.Tasks;
using EmagineFrete.Pages;
using Xamarin.Forms;
using System.Linq;
using EmagineFrete.Model;
using Acr.UserDialogs;
using Emagine.Base.Utils;
using Emagine.Base.Pages;
using Emagine.Base.Model;
using FormsPlugin.Iconize;
using Emagine.Base.Estilo;
using System.Collections.Generic;
using EmagineFrete.Popups;
using Emagine.Frete.Factory;
using Emagine.Login.Factory;
using Emagine.Frete.Pages;
using Emagine.Login.Pages;
using Emagine.Frete.Utils;
using Emagine.GPS.Utils;

namespace Emagine
{
    public class App : Application
    {

        public App()
        {
            //GlobalUtils.URLAplicacao = "http://emagine.com.br/frete-simples";
            GlobalUtils.URLAplicacao = "http://emagine.com.br/nvoid";
            GlobalUtils.setAplicacaoAtual(AplicacaoEnum.APLICACAO02);
            //GlobalUtils.setAplicacaoAtual(AplicacaoEnum.APLICACAO01);
            var estilo = criarEstilo();

            GPSUtils.UsaLocalizacao = true;
            GPSUtils.Current.DistanciaMinima = 0;
            GPSUtils.Current.TempoMinimo = 10;

            var regraUsuario = UsuarioFactory.create();
            var usuario = regraUsuario.pegarAtual();

            if (usuario != null)
            {
                MainPage = gerarRootPage(new PrincipalPage());
            }
            else
            {
                MainPage = new IconNavigationPage(new InicialPage())
                {
                    BarBackgroundColor = Estilo.Current.BarBackgroundColor,
                    BarTextColor = Estilo.Current.BarTitleColor
                };
            }
        }

        public static Page gerarRootPage(Page mainPage)
        {
            mainPage.Appearing += MotoristaUtils.inicializar;
            var rootPage = new RootPage
            {
                NomeApp = "Frete Simples",
                PaginaAtual = mainPage,
                Menus = gerarMenu()
            };
            rootPage.AoAtualizarMenu += (sender, e) =>
            {
                ((RootPage)sender).Menus = gerarMenu();
            };
            return rootPage;
        }

        private Estilo criarEstilo()
        {
            var estilo = Estilo.Current;
            estilo.PrimaryColor = Color.FromHex("#c55a11"); //Color.FromRgb(197, 90, 17);
            estilo.SuccessColor = Color.FromHex("#3c706a"); //Color.FromHex("#a2c760");
            estilo.InfoColor = estilo.PrimaryColor;
            estilo.WarningColor = Color.FromHex("#e2944a");
            estilo.DangerColor = Color.FromHex("#d9534f");
            estilo.DefaultColor = Color.FromHex("#78909c"); //#535b63
            estilo.BarTitleColor = Color.White;
            estilo.BarBackgroundColor = estilo.PrimaryColor;

            estilo.TelaPadrao = new EstiloPage
            {
                //BackgroundColor = Color.FromHex("#d9d9d9")
                //BackgroundImage = "fundo.jpg"
            };
            estilo.TelaEmBranco = new EstiloPage
            {
                //BackgroundColor = Color.White
                //BackgroundImage = "fundo.jpg"
            };
            estilo.BgPadrao.BackgroundColor = Color.FromHex("#ffffff");
            estilo.BgRoot = new EstiloStackLayout
            {
                BackgroundColor = estilo.TelaPadrao.BackgroundColor
            };
            estilo.BotaoRoot = new EstiloMenuButton
            {
                FontFamily = estilo.FontDefaultRegular,
                BackgroundColor = Color.White,
                FontSize = 18
            };
            estilo.BgInvestido = new EstiloStackLayout
            {
                BackgroundColor = estilo.PrimaryColor
            };
            estilo.MenuPagina = new EstiloPage
            {
                BackgroundColor = estilo.BarBackgroundColor
            };
            estilo.MenuTexto = new EstiloLabel
            {
                FontFamily = estilo.FontDefaultRegular,
                TextColor = Color.White,
                FontSize = 18
            };
            estilo.MenuLista = new EstiloListView
            {
                SeparatorColor = estilo.MenuTexto.TextColor
            };
            estilo.MenuIcone = new EstiloIcon
            {
                IconColor = estilo.MenuTexto.TextColor,
                IconSize = 22
            };
            estilo.EntryPadrao = new EstiloEntry
            {
                FontFamily = estilo.FontDefaultRegular
            };
            estilo.SearchBar = new EstiloSearch
            {
                FontFamily = estilo.FontDefaultBold,
                FontSize = 18,
                FontAttributes = FontAttributes.Bold
            };
            estilo.BotaoPrincipal = new EstiloBotao
            {
                FontFamily = estilo.FontDefaultBold,
                BackgroundColor = estilo.PrimaryColor,
                TextColor = Color.White,
                CornerRadius = 10
            };
            estilo.BotaoSucesso = new EstiloBotao
            {
                FontFamily = estilo.FontDefaultBold,
                BackgroundColor = estilo.SuccessColor,
                TextColor = Color.White,
                CornerRadius = 10
            };
            estilo.BotaoInfo = new EstiloBotao
            {
                FontFamily = estilo.FontDefaultRegular,
                BackgroundColor = estilo.InfoColor,
                TextColor = Color.White,
                CornerRadius = 10
            };
            estilo.BotaoPadrao = new EstiloBotao
            {
                FontFamily = estilo.FontDefaultRegular,
                BackgroundColor = estilo.DefaultColor,
                TextColor = Color.White,
                CornerRadius = 10
            };
            estilo.Titulo1 = new EstiloLabel
            {
                FontFamily = estilo.FontDefaultBold,
                TextColor = Color.Black,
                FontAttributes = FontAttributes.Bold,
                FontSize = 24
            };
            estilo.Titulo2 = new EstiloLabel
            {
                FontFamily = estilo.FontDefaultBold,
                TextColor = Color.Black,
                FontAttributes = FontAttributes.Bold,
                FontSize = 20
            };
            estilo.Titulo3 = new EstiloLabel
            {
                FontFamily = estilo.FontDefaultBold,
                TextColor = Color.Black,
                FontAttributes = FontAttributes.Bold,
                FontSize = 16
            };
            estilo.LabelControle = new EstiloLabel
            {
                FontFamily = estilo.FontDefaultRegular,
                TextColor = Color.FromHex("#7c7c7c"),
                FontSize = 12
            };
            estilo.LabelCampo = new EstiloLabel
            {
                FontFamily = estilo.FontDefaultRegular,
                TextColor = Color.Black,
                FontSize = 16,
                FontAttributes = FontAttributes.Bold
            };
            estilo.ListaItem = new EstiloLabel
            {
                FontFamily = estilo.FontDefaultBold,
                FontSize = 24,
                FontAttributes = FontAttributes.Bold
            };
            estilo.ListaBadgeTexto = new EstiloLabel
            {
                FontFamily = estilo.FontDefaultItalic,
                FontSize = 11,
                TextColor = estilo.BarTitleColor
            };
            estilo.ListaBadgeFundo = new EstiloFrame
            {
                WidthRequest = 60,
                HorizontalOptions = LayoutOptions.End,
                VerticalOptions = LayoutOptions.Center,
                CornerRadius = 10,
                BackgroundColor = estilo.BarBackgroundColor,
                Padding = new Thickness(4, 3),
            };
            estilo.IconePadrao = new EstiloIcon
            {
                IconSize = 20
            };
            estilo.TotalFrame = new EstiloFrame
            {
                Margin = new Thickness(4, 0, 4, 5),
                Padding = new Thickness(3, 2),
                CornerRadius = 10,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                BackgroundColor = estilo.BarBackgroundColor
            };
            estilo.TotalLabel = new EstiloLabel
            {
                FontFamily = estilo.FontDefaultRegular,
                FontSize = 11,
                TextColor = estilo.BarTitleColor
            };
            estilo.TotalTexto = new EstiloLabel
            {
                FontFamily = estilo.FontDefaultBold,
                FontAttributes = FontAttributes.Bold,
                FontSize = 20,
                TextColor = estilo.BarTitleColor
            };
            estilo.EnderecoItem = new EstiloLabel
            {
                FontFamily = estilo.FontDefaultBold,
                FontSize = 20,
                FontAttributes = FontAttributes.Bold
            };
            estilo.EnderecoFrame = new EstiloFrame
            {
                CornerRadius = 5,
                Padding = 2,
                Margin = new Thickness(2, 2),
                BackgroundColor = Color.White,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
            };
            estilo.EnderecoTitulo = new EstiloLabel
            {
                FontFamily = estilo.FontDefaultBold,
                FontSize = 16,
                FontAttributes = FontAttributes.Bold
            };
            estilo.EnderecoCampo = new EstiloLabel
            {
                FontFamily = estilo.FontDefaultBold,
                FontSize = 14,
                FontAttributes = FontAttributes.Bold
            };
            estilo.EnderecoLabel = new EstiloLabel
            {
                FontFamily = estilo.FontDefaultRegular,
                FontSize = 12
            };
            App.Current.Resources = estilo.gerar();
            return estilo;
        }

        public static IList<MenuItemInfo> gerarMenu()
        {
            var regraUsuario = UsuarioFactory.create();
            var usuario = regraUsuario.pegarAtual();

            var regraMotorista = MotoristaFactory.create();
            var motorista = regraMotorista.pegarAtual();

            var menus = new List<MenuItemInfo>();

            if (motorista != null)
            {
                menus.Add(new MenuItemInfo
                {
                    IconeFA = "fa-car",
                    Titulo = "Corridas em espera",
                    aoClicar = (sender, e) =>
                    {
                        ((RootPage)App.Current.MainPage).PushAsync(new CorridasEmEspera());
                        //((RootPage)App.Current.MainPage).PushAsync(new FreteMotoristaListaPage());
                    }
                });
                menus.Add(new MenuItemInfo
                {
                    IconeFA = "fa-search",
                    Titulo = "Minhas Entregas",
                    aoClicar = (sender, e) =>
                    {
                        ((RootPage)App.Current.MainPage).PushAsync(new FreteListaPage(false));
                    }
                });
            }
            else
            {
                menus.Add(new MenuItemInfo
                {
                    IconeFA = "fa-car",
                    Titulo = "Enviar Produto",
                    aoClicar = (sender, e) =>
                    {
                        ((RootPage)App.Current.MainPage).PushAsync(new ProdutoPage());
                    }
                });

                menus.Add(new MenuItemInfo
                {
                    IconeFA = "fa-search",
                    Titulo = "Rastrear Mercadoria",
                    aoClicar = (sender, e) =>
                    {
                        var listaFretePage = new FreteListaPage(false) {
                            Title = "Ratrear Mercadoria"
                        };
                        ((RootPage)App.Current.MainPage).PushAsync(listaFretePage);
                    }
                });

                menus.Add(new MenuItemInfo
                {
                    IconeFA = "fa-gift",
                    Titulo = "Meus Pedidos",
                    aoClicar = (sender, e) =>
                    {
                        var listaFretePage = new FreteListaPage(true)
                        {
                            Title = "Meus Pedidos"
                        };
                        ((RootPage)App.Current.MainPage).PushAsync(listaFretePage);
                    }
                });

                /*
                menus.Add(new MenuItemInfo
                {
                    IconeFA = "fa-cogs",
                    Titulo = "Minhas Configurações",
                    aoClicar = (sender, e) =>
                    {
                        //((RootPage)MainPage).PushAsync(new ConfiguracaoPage());
                    }
                });
                */

                menus.Add(new MenuItemInfo
                {
                    IconeFA = "fa-user",
                    Titulo = "Cadastro",
                    aoClicar = (sender, e) =>
                    {
                        //((RootPage)App.Current.MainPage).PushAsync(new CadastroTipoPopup(false));
                        var usuarioGerenciaPage = new UsuarioGerenciaPage {
                            EnderecoVisivel = false
                        };
                        ((RootPage)App.Current.MainPage).PushAsync(usuarioGerenciaPage);
                    }
                });

            }

            menus.Add(new MenuItemInfo
            {
                IconeFA = "fa-envelope",
                Titulo = "Fale Conosco",
                aoClicar = (sender, e) =>
                {
                    Device.OpenUri(new Uri("mailto:rodrigo@emagine.com.br"));
                }
            });

            menus.Add(new MenuItemInfo
            {
                IconeFA = "fa-exclamation-circle",
                Titulo = "Sobre o Aplicativo",
                aoClicar = (sender, e) =>
                {
                    ((RootPage)App.Current.MainPage).PaginaAtual = new SobrePage();
                }
            });
            menus.Add(new MenuItemInfo
            {
                IconeFA = "fa-file-text",
                Titulo = "Termos e Condições",
                aoClicar = (sender, e) =>
                {
                    var motoristaLocal = MotoristaFactory.create().pegarAtual();
                    if (motoristaLocal == null)
                        ((RootPage)App.Current.MainPage).PushAsync(new PoliticaDePrivacidade(false));
                    else
                        ((RootPage)App.Current.MainPage).PushAsync(new PoliticaDePrivacidade(true));
                }
            });

            menus.Add(new MenuItemInfo
            {
                IconeFA = "fa-arrow-left",
                Titulo = "Sair",
                aoClicar = async (sender, e) => {
                    await UsuarioFactory.create().limparAtual();
                    await MotoristaFactory.create().limparAtual();
                    await FreteFactory.create().limparAtual();
                    App.Current.MainPage = new IconNavigationPage(new InicialPage())
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

            //AtualizacaoEntrega.start();
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

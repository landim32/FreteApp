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
using Emagine.Frete.Utils;
using Emagine.GPS.Utils;
using Emagine.GPS.Model;
using Emagine.Frete.Model;
using Emagine.Pagamento.Model;
using Emagine.Pagamento.Pages;
using Emagine.Mapa.Pages;
using Emagine.Login.Model;
using Emagine.Login.Utils;

namespace Emagine
{
    public class App : Application
    {
        
        public App()
        {
            GlobalUtils.URLAplicacao = "http://emagine.com.br/frete";

            GPSUtils.UsaLocalizacao = true;
            GPSUtils.Current.DistanciaMinima = 0;
            GPSUtils.Current.TempoMinimo = 10;

            //UsuarioFormPageFactory.Tipo = typeof(CustomUsuarioFormPage);
            //UsuarioFormPageFactory.Tipo = typeof(FreteUsuarioFormPage);
            //CadastroMotoristaPageFactory.Tipo = typeof(CustomCadastroMotoristaPage);

            var estilo = criarEstilo();
            //MainPage = new NavigationPage(new BlankPage());
            inicializarApp();
            //CaronaUtils.inicializarFrete();

            /*
            ((RootPage)MainPage).PaginaAtual = new RotaSelecionaPage {
                Title = "Selecione a rota",
                TituloPadrao = "teste",
                IniciarEmPosicaoAtual = true
            };
            */

            //CaronaUtils.inicializar();

            /*
            var regraUsuario = UsuarioFactory.create();
            var usuario = regraUsuario.pegarAtual();
            if (usuario != null)
            {
                inicializarApp();
            }
            else {
                var inicialPage = new CustomInicialPage();
                //inicialPage.Appearing += InicialPageAppearing;
                MainPage = new IconNavigationPage(inicialPage) {
                    BarBackgroundColor = Estilo.Current.BarBackgroundColor,
                    BarTextColor = Estilo.Current.BarTitleColor
                };
            }
            */
        }

        public void inicializarApp() {
            var inicialPage = new BlankPage
            {
                Title = "Emagine Carona"
            };
            inicialPage.Appearing += InicialPageAppearing;
            MainPage = gerarRootPage(inicialPage);
        }

        private async void InicialPageAppearing(object sender, EventArgs e)
        {
            PermissaoUtils.pedirPermissao();
            if (await GPSUtils.Current.inicializar())
            {
                var regraMotorista = MotoristaFactory.create();
                var motorista = regraMotorista.pegarAtual();
                if (motorista != null)
                {
                    GPSUtils.Current.aoAtualizarPosicao += atualizarPosicao;
                }
            }
            else {
                await UserDialogs.Instance.AlertAsync("Ative seu GPS!", "Erro", "Entendi");
            }
            //CaronaUtils.inicializar();
            CaronaUtils.inicializarFrete();
        }

        private void atualizarPosicao(object sender, GPSLocalInfo local)
        {
            Device.BeginInvokeOnMainThread(() => {
                CaronaUtils.atualizarPosicao(local);
            });
        }

        public static Page gerarRootPage(Page mainPage)
        {
            var regraUsuario = UsuarioFactory.create();
            var usuario = regraUsuario.pegarAtual();
            var rootPage = new RootPage
            {
                NomeApp = "Emagine Carona",
                PaginaAtual = mainPage,
                Usuario = usuario,
                Menus = gerarMenu(usuario)
            };
            rootPage.AoAtualizarMenu += (sender, e) =>
            {
                var usuarioAtual = regraUsuario.pegarAtual();
                ((RootPage)sender).Usuario = usuarioAtual;
                ((RootPage)sender).Menus = gerarMenu(usuarioAtual);
            };
            return rootPage;
        }

        private Estilo criarEstilo()
        {
            var estilo = Estilo.Current;
            /*
            estilo.PrimaryColor = Color.FromHex("#ffc500");
            estilo.SuccessColor = Color.FromHex("#00c851");
            estilo.InfoColor = estilo.PrimaryColor;
            estilo.WarningColor = Color.FromHex("#f80");
            estilo.DangerColor = Color.FromHex("#d9534f");
            estilo.DefaultColor = Color.FromHex("#33b5e5");
            estilo.BarTitleColor = Color.White;
            estilo.BarBackgroundColor = Color.FromHex("#2d2d30");
            */
            estilo.PrimaryColor = Color.FromHex("#1da9df");
            estilo.SuccessColor = Color.FromHex("#5cb85c");
            estilo.InfoColor = Color.FromHex("#5bc0de");
            estilo.WarningColor = Color.FromHex("#f0ad4e");
            estilo.DangerColor = Color.FromHex("#d9534f");
            estilo.DefaultColor = Color.Gray;
            estilo.BarTitleColor = Color.FromHex("#ffffff");
            estilo.BarBackgroundColor = Color.FromHex("#197da6");

            estilo.FontDefaultRegular = Device.OnPlatform<string>(
                "Raleway-Regular.ttf",
                "Raleway-Regular.ttf#Raleway-Regular",
                "Raleway-Regular.ttf"
            );
            estilo.FontDefaultBold = Device.OnPlatform<string>(
                "Raleway-Bold.ttf",
                "Raleway-Bold.ttf#Raleway-Bold",
                "Raleway-Bold.ttf"
            );
            estilo.FontDefaultItalic = Device.OnPlatform<string>(
                "Raleway-Italic.ttf",
                "Raleway-Italic.ttf#Raleway-Italic",
                "Raleway-Italic.ttf"
            );
            estilo.TelaPadrao = new EstiloPage
            {
                BackgroundColor = Color.FromHex("#d9d9d9")
            };
            estilo.TelaEmBranco = new EstiloPage
            {
                BackgroundColor = Color.White
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
                //TextColor = Color.FromHex("#ffc500"),
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
            estilo.ListaFramePadrao = new EstiloFrame
            {
                Margin = new Thickness(4, 0, 4, 6),
                Padding = new Thickness(6, 4),
                CornerRadius = 10,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                BackgroundColor = Color.White
            };
            estilo.ListaItem = new EstiloLabel
            {
                FontFamily = estilo.FontDefaultBold,
                //FontSize = 24,
                FontSize = 18,
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

            estilo.Total.Frame = new EstiloFrame
            {
                Margin = new Thickness(4, 0, 4, 5),
                //Padding = new Thickness(3, 2),
                Padding = new Thickness(3, 10),
                //CornerRadius = 10,
                CornerRadius = 5,
                //BorderWidth = 1,
                //BorderColor = Color.FromHex("#7a7a7a"),
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                //BackgroundColor = estilo.BarBackgroundColor
                BackgroundColor = Color.FromHex("#bced8c"),
            };
            estilo.Total.Label = new EstiloLabel
            {
                FontFamily = estilo.FontDefaultRegular,
                FontSize = 12,
                //TextColor = estilo.BarTitleColor
                //TextColor = estilo.BarTitleColor,
                TextColor = Color.Black,
            };
            estilo.Total.Texto = new EstiloLabel
            {
                FontFamily = estilo.FontDefaultBold,
                FontAttributes = FontAttributes.Bold,
                FontSize = 14,
                //TextColor = estilo.BarTitleColor
                TextColor = Color.Black,
            };

            estilo.Produto.Frame = new EstiloFrame
            {
                CornerRadius = 7,
                Padding = 2,
                //Margin = new Thickness(2, 2),
                //BackgroundColor = Color.FromHex("#feecd6")
                BackgroundColor = Color.White
            };
            estilo.Produto.Foto = new EstiloImage
            {
                WidthRequest = 80,
                HeightRequest = 110,
                Aspect = Aspect.AspectFit
            };
            estilo.Produto.Titulo = new EstiloLabel
            {
                FontFamily = Estilo.Current.FontDefaultBold,
                //FontSize = 20,
                FontSize = 12,
                FontAttributes = FontAttributes.Bold,
                //LineBreakMode = LineBreakMode.TailTruncation,
                //TextColor = Estilo.Current.PrimaryColor
                TextColor = estilo.BarBackgroundColor
            };
            estilo.Produto.Descricao = new EstiloLabel
            {
                FontAttributes = FontAttributes.Bold,
                TextColor = Color.FromHex("#777777")
            };
            estilo.Produto.Volume = new EstiloLabel
            {
                FontAttributes = FontAttributes.Bold,
                TextColor = Color.FromHex("#777777")
            };
            estilo.Produto.Label = new EstiloLabel
            {
                FontAttributes = FontAttributes.None,
                FontSize = 9
            };
            estilo.Produto.Quantidade = new EstiloLabel
            {
                FontAttributes = FontAttributes.Bold,
                TextColor = Color.FromHex("#ff0000"),
                FontSize = 10
            };
            estilo.Produto.PrecoMoeda = new EstiloLabel
            {
                //FontSize = 11
                FontSize = 7
            };
            estilo.Produto.PrecoValor = new EstiloLabel
            {
                FontFamily = Estilo.Current.FontDefaultBold,
                FontAttributes = FontAttributes.Bold,
                FontSize = 12
                //FontSize = 24
            };
            estilo.Produto.PromocaoMoeda = new EstiloLabel
            {
                //FontSize = 11
                FontSize = 7,
                //TextColor = Color.FromHex("#ff0000"),
            };
            estilo.Produto.PromocaoValor = new EstiloLabel
            {
                FontFamily = Estilo.Current.FontDefaultBold,
                FontAttributes = FontAttributes.Bold,
                FontSize = 12,
                //TextColor = Color.FromHex("#ff0000"),
                //FontSize = 24
            };
            estilo.Produto.Icone = new EstiloIcon
            {
                IconColor = Color.FromHex("#ffc500"),
                IconSize = 22
                //IconSize = 24
            };
            estilo.Produto.Carrinho = new EstiloBotao
            {
                FontAttributes = FontAttributes.Bold,
                FontSize = 14,
                BackgroundColor = Color.FromHex("#bced8c"),
                TextColor = Color.Black,
                CornerRadius = 5,
                BorderWidth = 1,
                BorderColor = Color.FromHex("#7a7a7a")
            };
            estilo.Quantidade.AdicionarBotao = new EstiloFrame
            {
                BackgroundColor = Estilo.Current.BotaoInfo.BackgroundColor,
            };
            estilo.Quantidade.AdicionarIcone = new EstiloIcon
            {
                IconColor = Color.Black,
                IconSize = 20,
            };
            estilo.Quantidade.RemoverBotao = new EstiloFrame
            {
                BackgroundColor = Estilo.Current.BotaoInfo.BackgroundColor,
            };
            estilo.Quantidade.RemoverIcone = new EstiloIcon
            {
                IconColor = Color.Black,
                IconSize = 20,
            };
            estilo.Quantidade.Fundo = new EstiloFrame
            {
                Padding = 5,
                BackgroundColor = Color.Silver,
            };
            estilo.Quantidade.QuantidadeTexto = new EstiloLabel
            {
                FontSize = 16,
                FontAttributes = FontAttributes.Bold
            };

            App.Current.Resources = estilo.gerar();
            return estilo;
        }

        public static IList<MenuItemInfo> gerarMenu(UsuarioInfo usuario)
        {
            var regraUsuario = UsuarioFactory.create();
            var regraMotorista = MotoristaFactory.create();
            var motorista = regraMotorista.pegarAtual();

            var menus = new List<MenuItemInfo>();

            /*
            menus.Add(new MenuItemInfo
            {
                IconeFA = "fa-home",
                Titulo = "Início",
                aoClicar = (sender, e) =>
                {
                    CaronaUtils.inicializarFrete();
                }
            });
            */

            menus.Add(new MenuItemInfo
            {
                IconeFA = "fa-car",
                Titulo = "Pedir Carona",
                aoClicar = (sender, e) =>
                {
                    CaronaUtils.inicializarFrete();
                }
            });


            if (motorista != null)
            {
                menus.Add(new MenuItemInfo
                {
                    IconeFA = "fa-truck",
                    Titulo = "Meus atendimentos",
                    aoClicar = (sender, e) =>
                    {
                        CaronaUtils.listarMeuFreteComoMotorista();
                    }
                });

                menus.Add(new MenuItemInfo
                {
                    IconeFA = "fa-search",
                    Titulo = "Buscar corridas",
                    aoClicar = (sender, e) =>
                    {
                        CaronaUtils.buscarFreteComoMotorista();
                    }
                });

                menus.Add(new MenuItemInfo
                {
                    IconeFA = "fa-user",
                    Titulo = "Meu cadastro",
                    aoClicar = (sender, e) =>
                    {
                        var usuarioPage = new CustomUsuarioGerenciaPage
                        {
                            EnderecoVisivel = false
                        };
                        ((RootPage)Current.MainPage).PaginaAtual = usuarioPage;
                    }
                });
            }
            else if (usuario != null) {
                menus.Add(new MenuItemInfo
                {
                    IconeFA = "fa-user",
                    Titulo = "Meus dados",
                    aoClicar = (sender, e) =>
                    {
                        var usuarioAtual = regraUsuario.pegarAtual();

                        var usuarioPage = UsuarioFormPageFactory.create();
                        usuarioPage.Title = "Meus Dados";
                        usuarioPage.Usuario = usuarioAtual;
                        ((RootPage)Current.MainPage).PaginaAtual = usuarioPage;
                    }
                });
                menus.Add(new MenuItemInfo
                {
                    IconeFA = "fa-truck",
                    Titulo = "Minhas corridas",
                    aoClicar = (sender, e) =>
                    {
                        CaronaUtils.listarMeuFreteComoCliente();
                    }
                });
                menus.Add(new MenuItemInfo
                {
                    IconeFA = "fa-car",
                    Titulo = "Seja um motorista!",
                    aoClicar = (sender, e) =>
                    {
                        //var regraUsuario = UsuarioFactory.create();
                        //var regraMotorista = MotoristaFactory.create();

                        var usuario2 = regraUsuario.pegarAtual();
                        var motorista2 = regraMotorista.pegarAtual();
                        var cadastroMotoristaPage = CadastroMotoristaPageFactory.create();
                        cadastroMotoristaPage.Usuario = usuario2;
                        cadastroMotoristaPage.Motorista = motorista2;
                        cadastroMotoristaPage.AoCompletar += (s2, e2) => {
                            UserDialogs.Instance.AlertAsync("Seu cadastro foi enviado. Estamos analizando e retornaremos!");
                        };
                        ((RootPage)Current.MainPage).PushAsync(cadastroMotoristaPage);
                    }
                });
            }
            else
            {
                menus.Add(new MenuItemInfo
                {
                    IconeFA = "fa-user",
                    Titulo = "Entrar",
                    aoClicar = (sender, e) =>
                    {
                        var loginPage = new LoginPage {
                            Title = "Login"
                        };
                        loginPage.AoLogar += (s, u) => {
                            CaronaUtils.inicializarFrete();
                        };
                        ((RootPage)Current.MainPage).PushAsync(loginPage);
                    }
                });
                menus.Add(new MenuItemInfo
                {
                    IconeFA = "fa-user-plus",
                    Titulo = "Criar Conta",
                    aoClicar = (sender, e) =>
                    {
                        /*
                        ((RootPage)Current.MainPage).PushAsync(Login.Utils.LoginUtils.gerarCadastro((u) => {
                            CaronaUtils.inicializarFrete();
                        }));
                        */
                        var cadastroPage = LoginUtils.gerarCadastro((usuario2) => {
                            CaronaUtils.inicializarFrete();
                        });
                        ((RootPage)Current.MainPage).PushAsync(cadastroPage);
                        /*
                        CaronaUtils.criarUsuario(() => {
                            CaronaUtils.inicializarFrete();
                        });
                        */
                    }
                });
            }

            menus.Add(new MenuItemInfo
            {
                IconeFA = "fa-question",
                Titulo = "Sobre a Emagine",
                aoClicar = (sender, e) =>
                {
                    var sobrePage = new DocumentoPage {
                        NomeArquivo = "sobre.html"
                    };
                    ((RootPage)Current.MainPage).PushAsync(sobrePage);
                }
            });

            menus.Add(new MenuItemInfo
            {
                IconeFA = "fa-comment",
                Titulo = "Fale Conosco",
                aoClicar = (sender, e) =>
                {
                    Device.OpenUri(new Uri("mailto:rodrigo@emagine.com.br"));
                }
            });

            if (usuario != null) {
                menus.Add(new MenuItemInfo {
                    IconeFA = "fa-remove",
                    Titulo = "Sair",
                    aoClicar = async (sender, e) => {
                        var regraFrete = FreteFactory.create();
                        await regraFrete.limparAtual();
                        await regraUsuario.limparAtual();
                        await regraMotorista.limparAtual();
                        ((RootPage)App.Current.MainPage).atualizarMenu();
                        CaronaUtils.inicializarFrete();
                    }
                });
            }

            return menus;
        }

        protected override void OnStart()
        {
            // Handle when your app starts
            /*
            var regraMotorista = MotoristaFactory.create();
            var motorista = regraMotorista.pegarAtual();
            if(motorista != null){
                AtualizacaoFrete.start();   
            }
            */
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

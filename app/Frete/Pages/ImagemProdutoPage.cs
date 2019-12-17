using System;
using System.IO;
using System.Threading.Tasks;
using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Base.Utils;
using Emagine.Frete.BLL;
using Emagine.Frete.Model;
using EmagineFrete.Controls;
using EmagineFrete.Model;
using EmagineFrete.Utils;
using Plugin.Media;
using Xamarin.Forms;

namespace EmagineFrete.Pages
{
    public class ImagemProdutoPage : ContentPage
    {

        private Image _Miniatura;
        private Button _FotoProdutoButton;
        private Entry _Observacao;
        private FreteInfo _FreteInfo;
        private Button _EnviarButton;
        public Stream _Imagem;

        public ImagemProdutoPage(FreteInfo FreteInfo)
        {
            _FreteInfo = FreteInfo;
            Title = "Foto do produto";
            inicializarComponente();
            Content = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Children = {
                    _FotoProdutoButton,
                    _Observacao,
                    _EnviarButton
                }
            };
        }

        private async Task setImageMiniaturaAsync()
        {
            await CrossMedia.Current.Initialize();

            if (!CrossMedia.Current.IsCameraAvailable || !CrossMedia.Current.IsTakePhotoSupported)
            {
                UserDialogs.Instance.Alert("No Camera", ":( No camera available.", "OK");
                return;
            }

            var file = await CrossMedia.Current.TakePhotoAsync(new Plugin.Media.Abstractions.StoreCameraMediaOptions
            {
                Directory = "EmagineFrete",
                Name = "foto_temp.jpg"
            });
            if (file == null)
                return;
            _Imagem = file.GetStream();
            _Miniatura.Source = ImageSource.FromStream(() => {
                //var stream = file.GetStream();
                MemoryStream mm = new MemoryStream();
                file.GetStream().CopyTo(mm);
                byte[] bytedata = mm.ToArray();
                bytedata = ImagemUtils.redimencionar(bytedata, 480);
                var retorno = new MemoryStream(bytedata);
                file.Dispose();
                return retorno;
            });
            ((StackLayout)this.Content).Children.Insert(0, _Miniatura);
        }

        public void inicializarComponente(){
            _Miniatura = new Image()
            {
                Aspect = Aspect.AspectFit,
                HeightRequest = 250,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                Margin = new Thickness(15)
            };
            _FotoProdutoButton = new Button()
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(8, 0),
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                Text = "Adicionar foto do produto"
            };
            _FotoProdutoButton.Pressed += (sender, e) => {
                setImageMiniaturaAsync();
            };
            _Observacao = new Entry()
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                FontSize = 14,
                HorizontalTextAlignment = TextAlignment.End,
            };
            _EnviarButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(8, 0),
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                Text = "Próximo"
            };
            _EnviarButton.Clicked += (sender, e) => {
                continuaPedidoAsync();
            };
        }

        public async Task continuaPedidoAsync()
        {
            UserDialogs.Instance.ShowLoading("Enviando...");
            try{
                if (validaPedido())
                {
                    var reader = new StreamReader(this._Imagem);
                    byte[] bytedata = System.Text.Encoding.UTF8.GetBytes(reader.ReadToEnd());
                    _FreteInfo.FotoBase64 = Convert.ToBase64String(bytedata);
                    _FreteInfo.Observacao = _Observacao.Text;
                    var idProduto = await new FreteBLL().inserir(_FreteInfo);
                    var retInfo = await new FreteBLL().pegar(idProduto);
                    UserDialogs.Instance.HideLoading();
                    Navigation.PushAsync(new DetalheProdutoPage(retInfo));
                }
                else
                {
                    UserDialogs.Instance.HideLoading();
                    DisplayAlert("Atenção", "Dados inválidos, verifique todas as entradas.", "Entendi");
                }
            } catch(Exception e){
                UserDialogs.Instance.HideLoading();
                UserDialogs.Instance.Alert(e.Message);
            }

        }

        private bool validaPedido()
        {
            if(_Imagem == null){
                return false;
            }
            return true;
        }

    }
}


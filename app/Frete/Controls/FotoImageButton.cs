using System;
using System.IO;
using Acr.UserDialogs;
using Emagine.Base.Utils;
using FormsPlugin.Iconize;
using Plugin.Media;
using Xamarin.Forms;

namespace EmagineFrete.Controls
{
    public class FotoImageButton : ContentView
    {
        private Image _Imagem;
        private IconImage _CheckImage;

        public FotoImageButton()
        {
            inicializarComponente();

            AbsoluteLayout.SetLayoutFlags(_Imagem, AbsoluteLayoutFlags.All);
            AbsoluteLayout.SetLayoutBounds(_Imagem, new Rectangle(0, 0, 1, 1));

            AbsoluteLayout.SetLayoutFlags(_CheckImage, AbsoluteLayoutFlags.All);
            AbsoluteLayout.SetLayoutBounds(_CheckImage, new Rectangle(0, 0, 1, 1));

            Content = new AbsoluteLayout {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                Children = {
                    _Imagem,
                    _CheckImage
                }
            };
        }

        public MemoryStream Imagem { get; set; }
        public bool RedimenciarFoto { get; set; } = true;

        public ImageSource Source {
            get {
                if (_Imagem != null)
                {
                    return _Imagem.Source;
                }
                return null;
            }
            set {
                if (_Imagem != null)
                {
                    _Imagem.Source = value;
                }
            }
        }

        private void inicializarComponente() {

            var tapGestureRecognizer = new TapGestureRecognizer();
            tapGestureRecognizer.Tapped += ImagemButtonTapped;

            if (_Imagem == null)
            {
                _Imagem = new Image()
                {
                    HorizontalOptions = LayoutOptions.Fill,
                    VerticalOptions = LayoutOptions.Start,
                    Aspect = Aspect.AspectFill
                };
                _Imagem.GestureRecognizers.Add(tapGestureRecognizer);
            }

            if (_CheckImage == null) {
                _CheckImage = new IconImage()
                {
                    HorizontalOptions = LayoutOptions.Center,
                    VerticalOptions = LayoutOptions.Center,
                    IconSize = 50,
                    IconColor = Color.Green,
                    Icon = "fa-check",
                    IsVisible = false
                };
                _CheckImage.GestureRecognizers.Add(tapGestureRecognizer);
            }
        }

        protected async void ImagemButtonTapped(object sender, EventArgs e)
        {
            await CrossMedia.Current.Initialize();

            if (!CrossMedia.Current.IsCameraAvailable || !CrossMedia.Current.IsTakePhotoSupported)
            {
                UserDialogs.Instance.Alert("No Camera", ":( No camera available.", "OK");
                return;
            }

            var file = await CrossMedia.Current.TakePhotoAsync(new Plugin.Media.Abstractions.StoreCameraMediaOptions
            {
                Directory = "EmgineFrete",
                Name = "foto_temp.jpg"
            });
            if (file == null)
                return;
            if (RedimenciarFoto)
            {
                MemoryStream mm = new MemoryStream();
                file.GetStream().CopyTo(mm);
                byte[] bytedata = mm.ToArray();
                mm.Dispose();
                mm = null;
                bytedata = ImagemUtils.redimencionar(bytedata, 480);
                Imagem = new MemoryStream(bytedata);
            }
            else
            {
                Imagem = new MemoryStream();
                file.GetStream().CopyTo(Imagem);
            }
            file.Dispose();
            _CheckImage.IsVisible = true;
        }

        public string getBase64() {
            if (Imagem != null)
            {
                return Convert.ToBase64String(Imagem.ToArray());
            }
            return null;
        }
    }
}


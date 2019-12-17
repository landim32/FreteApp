using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;

using Android.App;
using Android.Content;
using Android.Graphics;
using Android.OS;
using Android.Runtime;
using Android.Views;
using Android.Widget;
using Emagine.Base.IBLL;
using Frete.Droid;
using Xamarin.Forms;

[assembly: Dependency(typeof(ImagemAndroid))]

namespace Frete.Droid
{
    public class ImagemAndroid: IImagemBLL
    {
        public byte[] redimencionar(byte[] data, float width, float height) {
            Bitmap imagemOriginal = BitmapFactory.DecodeByteArray(data, 0, data.Length);
            int larguraOriginal = imagemOriginal.Width;
            int alturaOriginal = imagemOriginal.Height;
            int larguraNova = (int)width;
            int alturaNova = (int)height;
            if (!(larguraNova > 0 && alturaNova > 0)) {
                if (larguraNova > 0) {
                    alturaNova = (alturaOriginal * larguraNova) / larguraOriginal;
                }
                else if (alturaNova > 0) {
                    larguraNova = (larguraOriginal * alturaNova) / alturaOriginal;
                }
            }
            Bitmap imagemAlterada = Bitmap.CreateScaledBitmap(imagemOriginal, larguraNova, alturaNova, false);
            using (MemoryStream ms = new MemoryStream())
            {
                imagemAlterada.Compress(Bitmap.CompressFormat.Jpeg, 90, ms);
                return ms.ToArray();
            }
        }
    }
}